<?php namespace App\Http\Controllers;

use App\Commands\AddNewCountCampaign;
use App\Http\Requests\MailingRequest;
use App\Http\Requests\WaitingProductRequest;
use App\Commands\SendCallMeNumber;
use App\Models\AdvertisingCampaign;
use App\Models\CallMe;
use App\Models\CatalogCategory;
use App\Models\ItemAdvertisingCampaign;
use App\Models\JobLog;
use App\Models\LiqPay;
use App\Models\Mailing;
use App\Models\ProductLog;
use App\Models\Settings;
use App\Models\WaitingProductUser;
use Carbon\Carbon;
use File;
use App\Models\CatalogProduct;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RegisterProductCategory;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Support\Facades\Validator;
use SimpleXMLElement;
use Activity;
use Auth;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class AdminController
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    use SluggableTrait;

    protected $sorting_array = [
        'real_views' => 'Просмотры',
        /*'sold'          => 'Продано',
        'likes'         => 'Лайки',*/
        'created_at' => 'По дате',
        'active' => 'Активные',
        'notActive' => 'Не активные'
    ];

    protected $availableMimeTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png'
    ];

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * @param $status
     * @return \Illuminate\View\View
     */
    public function orders($status = null)
    {
        $orders = Order::with(['order_item' => function ($query) {
            $query->with('product');
        }])
            ->notDeleted();

        if ($status) {
            $orders = $orders->where('order_status', '=', $status);
        }
        $orders = $orders->orderBy('created_at', 'desc')
            ->paginate(20);

        $orders_update = collect($orders);
        $orders_ids = collect($orders_update['data'])->lists('id');

        Order::where('new', '=', true)
            ->whereIn('id', $orders_ids)
            ->update([
                'new' => false
            ]);

        return view('admin.orders.index', compact(
            'orders',
            'status'
        ));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function searchOrder()
    {
        if (Input::get('search') != '') {
            $search = Input::get('search');
            $orders = Order::with('order_item')
                ->notDeleted()
                ->where('orders.id', $search)
                ->orWhere('payment_id', $search)
                ->get();

            return view('admin.orders.index', compact(
                'orders',
                'search'
            ));
        }
        return redirect()->route('orders');
    }

    /**
     * @param Order $order
     * @return \Illuminate\View\View
     */
    public function editOrder(Order $order)
    {
        if (!isset($order->id)) {

            return redirect()->route('orders')->withErrors([
                'order' => 'Order not found'
            ]);
        }
        $order->load('order_item');

        $order->contacts = unserialize($order->contacts);
        $order->contacts = (array)$order->contacts;

        foreach ($order->contactFields as $field) {
            isset($order->contacts[$field]) ? $order->$field = $order->contacts[$field] : $order->$field = '';
        }

        $cities = User::getCities();
        $users = User::limit(30)->lists('name', 'id');

        return view('admin.orders.edit', compact('cities', 'order', 'users'));
    }

    /**
     * @param Order $order
     * @return
     */
    public function updateOrder(Order $order)
    {
        if (!isset($order->id)) {

            return redirect()->route('orders')->withErrors([
                'order' => 'Order already deleted!'
            ]);
        }

        $order->load('order_item');
        $order->fill(Input::all());

        $contacts = [];
        $contacts['d_user_name'] = Input::get('d_user_name');
        $contacts['d_user_region'] = Order::getRegionNameByCityId(Input::get('d_user_city'));
        $contacts['d_user_city'] = Order::getCityName(Input::get('d_user_city'));
        $contacts['d_user_address'] = Input::get('d_user_address');
        $contacts['d_user_index'] = Input::get('d_user_index');
        $contacts['d_user_phone'] = Input::get('d_user_phone');
        $contacts['d_user_email'] = Input::get('d_user_email');

        $order->contacts = serialize(
            Order::getOrderContacts($contacts)
        );

        foreach ($order->order_item as $order_item) {
            $quantity = 'product_quantity_' . $order_item->id;
            if (Input::has($quantity)) {
                $order_item->product_quantity = Input::get($quantity);
            }
            $order_item->save();
        }

        is_null(Input::get('api')) ? $order->api = false : $order->api = true;
        Input::get('last_office_index') != "" ?: $order->last_office_index = null;
        Input::get('user_buyer') <= 0 ?: $order->user_id = Input::get('user_buyer');

        $order->save();

        Order::recalculateOrder($order);

        return redirect()->route('orders',
            [
                'status' => Input::get('order_status')
            ])
            ->with('success_update', 'success');
    }

    /**
     * @param Request $request
     */
    public function deleteOrder(Request $request)
    {
        foreach ($request->items as $order) {
            Order::where('id', '=', $order['itemId'])
                ->update([
                    'deletion_mark' => true
                ]);
        }
    }

    /**
     * @param Request $request
     */
    public function deleteOrderItems(Request $request)
    {
        $order_items = collect($request->items);
        $order_item = OrderItem::where('id', '=', $order_items->first()['itemId'])
            ->first();

        OrderItem::whereIn('id', $order_items->lists('itemId'))
            ->delete();

        Order::recalculateOrder($order_item->order);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function checkOrderStatusOfLiqpayForm()
    {
        return view('admin.payments.liqpay');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function checkOrderStatusOfLiqpay(Request $request)
    {
        $order = Order::where('payment_id', $request->payment_id)
            ->first();

        if ($order) {
            $public_key = config('app.public_key_liqpay');
            $private_key = config('app.private_key_liqpay');

            $liqpay = new LiqPay($public_key, $private_key);

            $result = $liqpay->api("payment/status", array(
                'version' => '3',
                'order_id' => $order->id
            ));
            $result->url_to_order = url('administrator/orders/edit/' . $order->id);
        } else {
            $result = new \stdClass();
            $result->status = 'Unknown';
            $result->err_description = 'Не найден такой заказ в системе';
        }

        return compact('result');
    }


    /**
     * @return \Illuminate\View\View
     */
    public function getReviews()
    {
        $reviews = Review::has('product')->allowProduct()
            ->withUser()
            ->typeReview()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $review_ids = collect($reviews);
        $review_ids = collect($review_ids['data'])->lists('id');

        Review::where('new', '=', true)
            ->whereIn('id', $review_ids)
            ->update([
                'new' => false
            ]);

        return view('admin.reviews.index', compact(
            'reviews'
        ));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getTrashedReviews()
    {
        $reviews = Review::has('product')->allowProduct()
            ->withUser()
            ->typeReview()
            ->onlyTrashed()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reviews.index', compact(
            'reviews'
        ));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function archiveReview(Request $request)
    {
        foreach ($request->items as $review) {
            Review::where('id', '=', $review['itemId'])
                ->delete();
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function restoreReview(Request $request)
    {
        foreach ($request->items as $review) {
            Review::where('id', '=', $review['itemId'])
                ->restore();
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateReview(Request $request)
    {
        if ($request->ajax()) {
            $obj = Review::find($request->item_id);
            $obj->active = $request->active;
            $obj->save();
        }
    }


    /**
     * @param Request $request
     */
    public function storeReview(Request $request)
    {
        $obj = Review::find($request->id);
        $obj->answer = $request->answer;
        $obj->save();

        return Redirect::back()
            ->with('success', trans('home.reviews-save-admin'));
    }

    /**
     * @param Request $request
     */
    public function deleteReview(Request $request)
    {
        foreach ($request->items as $review) {
            Review::where('id', '=', $review['itemId'])
                ->forcedelete();
        }
    }

    /**
     * @return mixed
     */
    public function clearCache()
    {
        Cache::flush();

        CatalogProduct::clearSessionAndCache();

        return Redirect::to('administrator/settings')
            ->with('success', (trans('home.clearCache')));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function showUser()
    {
        $users = User::orderBy('created_at', 'desc')
            ->paginate(20);

        $activities = Activity::users()->get();
        $users_online = $activities->lists('user', 'user_id');

        return view('admin.users.index', compact('users', 'users_online'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function showUsersOnline()
    {
        $activities = Activity::users()->get();
        $users_online = $activities->lists('user', 'user_id');

        $users = User::orderBy('created_at', 'desc')
            ->whereIn('id', array_keys($users_online))
            ->paginate(20);

        $numberOfGuests = Activity::guests()->count();

        return view('admin.users.index', compact(
            'users',
            'users_online',
            'numberOfGuests'
        ));
    }

    /**
     * @param Request $request
     */
    public function deleteUser(Request $request)
    {
        foreach ($request->items as $user) {
            User::where('id', '=', $user['itemId'])
                ->delete();
        }

    }

    /**
     * @return \Illuminate\View\View
     */
    public function createUser()
    {

        return view('admin.users.create');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function storeUser(Request $request)
    {
        $obj = new User();

        $contacts = [];
        $contacts['d_user_region'] = Order::getRegionNameByCityId($request->d_user_city);
        $contacts['d_user_city'] = Order::getCityName($request->d_user_city);
        $contacts['d_user_address'] = $request->d_user_address;
        $contacts['d_user_index'] = $request->d_user_index;
        $contacts['d_user_phone'] = $request->d_user_phone;

        $obj->contacts = serialize(
            User::getUserContacts($contacts)
        );
        $obj->name = $request->name;
        $obj->email = $request->email;
        is_null($request->active) ? $obj->active = 0 : $obj->active = $request->active;
        $obj->password = bcrypt($request->password);
        $obj->save();

        return Redirect::to('administrator/users')
            ->with('success', trans('admin.updateUserSuccess'));
    }

    /**
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function editUser(User $user)
    {
        $user->load(['order' => function ($query) {
            $query->notDeleted();
        }]);

        $user->contacts = unserialize($user->contacts);
        $user->contacts = (array)$user->contacts;

        foreach ($user->contactFields as $field) {
            isset($user->contacts[$field]) ? $user->$field = $user->contacts[$field] : $user->$field = '';
        }
        $cities = User::getCities();

        // get product pages that user have visited
        $product_pages = json_decode($user->product_pages);
        if (is_array($product_pages)) {
            $products = CatalogProduct::getProductWithAllRel();
            $products = $products->whereIn('id', $product_pages)
                ->get();
        }

        return view('admin.users.edit', compact(
            'cities',
            'user',
            'products'
        ));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateUser(Request $request)
    {
        if ($request->ajax()) {
            $obj = User::find($request->item_id);
            $obj->active = $request->active;
            $obj->save();
        } else {
            $user = User::find($request->id);

            $contacts = [];
            if ($request->d_user_city > 0) {
                $contacts['d_user_region'] = Order::getRegionNameByCityId($request->d_user_city);
                $contacts['d_user_city'] = Order::getCityName($request->d_user_city);
            }
            $contacts['d_user_address'] = $request->d_user_address;
            $contacts['d_user_index'] = $request->d_user_index;
            $contacts['d_user_phone'] = $request->d_user_phone;

            $user->contacts = serialize(
                User::getUserContacts($contacts)
            );
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $request->password == '' ?: $user->password = bcrypt($request->password);
            $user->save();

            return Redirect::to('administrator/users')
                ->with('success', trans('admin.updateUserSuccess'));
        }

    }

    /**
     * @param Request $request
     */
    public function updateUserActive(Request $request)
    {
        if ($request->ajax()) {
            $obj = User::find($request->item_id);
            $obj->isActive = $request->active;
            $obj->save();
        }
    }

    /**
     * @param Request $request
     */
    public function updateProductActive(Request $request)
    {
        RegisterProductCategory::where('product_id', '=', $request->item_id)
            ->update(['active' => $request->active]);
    }


    /**
     * @param Request $request
     */
    public function updateProductHidden(Request $request)
    {
        CatalogProduct::where('id', $request->item_id)
            ->update(['hidden' => $request->hidden]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getAllProducts()
    {
        $sort = new \stdClass();
        $sort->search_word = '';
        $sort->sort = Session::get('sorting', 'created_at');
        $sort->sorting_array = $this->sorting_array;

        $products = CatalogProduct::with('image')
            ->with('category')
            ->with('active');

        if ($sort->sort == 'active') {
            $products = $products->active();
        } elseif ($sort->sort == 'notActive') {
            $products = $products->notActive();
        } else {
            $products = $products->orderBy($sort->sort, 'desc');
        }

        $products = $products->paginate(30);

        return view(
            'admin.catalog.index',
            compact(
                'products',
                'sort'
            )
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function getSearchProduct(Request $request)
    {
        $sort = new \stdClass();
        $sort->search_word = trim($request->get('search_word'));
        $sort->sorting_array = $this->sorting_array;
        if ($sort->search_word == '') {
            Session::put('sorting', Input::get('sort'));

            return Redirect::to('administrator/catalog');
        } else {
            Session::put('sorting', Input::get('sort', 'views'));
            $sort->sort = Input::get('sort', 'views');

            $products = CatalogProduct::where(function ($query) use ($sort) {
                $query->where('upi_id', 'LIKE', '%' . $sort->search_word . '%')
                    ->orWhere('sku', 'LIKE', '%' . $sort->search_word . '%');
            })
                ->with('image')
                ->with('category')
                ->with('active');

            if ($sort->sort == 'active') {
                $products = $products->active();
            } elseif ($sort->sort == 'notActive') {
                $products = $products->notActive();
            } else {
                $products = $products->orderBy($sort->sort, 'desc');
            }
            $products = $products->get();

            if (!count($products)) {

                $products = CatalogProduct::getSearchProduct($sort->search_word);
                $products = $products->get();
                $products = $products->sortByDesc('rel');
            }
        }

        return view(
            'admin.catalog.index',
            compact(
                'products',
                'search',
                'sort'
            )
        );
    }


    /**
     * @return \Illuminate\View\View
     */
    public function getCategoriesImageForMenu()
    {
        $fileNames = self::getImages('uploads/menu');

        return view('admin.menu.menuCategoryUploadAll', compact('fileNames'));
    }

    /** List of categories first level
     * @return \Illuminate\View\View
     */
    public function menuCategoryList()
    {
        $menuCategory = CatalogCategory::where('level', '=', 0)
            ->select('name_ru', 'image', 'id', 'icon')
            ->orderBy('sort')
            ->get();

        return view('admin.menu.menuCategoryList', compact('menuCategory'));
    }

    /**
     * @param CatalogCategory $category
     * @return \Illuminate\View\View
     */
    public function editMenuCategory(CatalogCategory $category)
    {
        $fileNames = self::getImages('uploads/menu');
        $fileIconNames = self::getImages('uploads/menu-icon');

        return view('admin.menu.menuCategoryEdit', compact(
            'category',
            'fileNames',
            'fileIconNames'
        ));
    }

    /** Function to get images in path
     * @param $path
     * @return array
     */
    private function getImages($path)
    {
        $fileNames = [];
        $assetPath = $path;
        $dir = public_path($assetPath);

        if (!is_dir($dir)) {
            //Directory does not exist, so lets create it.
            File::makeDirectory($dir, $mode = 0777, true, true);
        }
        // Scan dir to find all packingList files
        $files = scandir($dir);

        foreach ($files as $file) {
            $fileFullPath = $dir . DIRECTORY_SEPARATOR . $file;
            if (!in_array($file, [".", ".."])) {

                // Find only files
                if (!is_dir($fileFullPath)) {

                    //only availableMimeTypes
                    if (in_array(
                        File::mimeType($fileFullPath),
                        $this->availableMimeTypes
                    )) {

                        $fileNames[$file] = '/' . $assetPath . '/' . $file;
                    }
                }
            }
        }

        return $fileNames;
    }

    /** Save category image
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function menuCategoryUpdate(Request $request, $id)
    {
        if ($request->has('image_radio') && $request->get('image_radio')) {

            CatalogCategory::where('id', '=', $id)
                ->update([
                    'image' => $request->get('image_radio')
                ]);
        }

        if ($request->has('image_icon_radio') && $request->get('image_icon_radio')) {

            CatalogCategory::where('id', '=', $id)
                ->update([
                    'icon' => $request->get('image_icon_radio')
                ]);
        }

        return redirect('administrator/menuCategoryList')
            ->with('success', 'Saved');
    }

    /** Delete images
     * @param $type
     * @param Request $request
     */
    public function deleteImage($type, Request $request)
    {
        if ($type == 'icon') {
            $assetPath = 'uploads/menu-icon';
        } elseif ($type == 'background') {
            $assetPath = 'uploads/menu';
        } elseif ($type == 'review') {
            $assetPath = 'uploads/review';
        }
        if (isset($assetPath)) {
            $uploadPath = public_path($assetPath);
            $file = str_replace('separator', '.', $request->id);
            File::delete($uploadPath . '/' . $file);
        }
    }

    /** Function for save uploaded files and returns array of its names
     * @return array
     */
    public function uploadImage($type)
    {
        // Grab our files input
        $files = Input::file('files');
        // We will store our uploads in public/uploads/*
        if ($type == 'icon') {
            $assetPath = 'uploads/menu-icon';
        } elseif ($type == 'background') {
            $assetPath = 'uploads/menu';
        } elseif ($type == 'review') {
            $assetPath = 'uploads/temp';
        } else {
            return array(
                'files' => []
            );
        }

        $uploadPath = public_path($assetPath);
        // We need an empty arry for us to put the files back into
        $results = array();
        $i = 1;
        foreach ($files as $file) {
            //only availableMimeTypes
            if (in_array(
                $file->getClientMimeType(),
                $this->availableMimeTypes
            )) {
                // generate new name if name in russian
                $ex = $file->getClientOriginalExtension();
                $f_name = basename($file->getClientOriginalName(), $ex);
                $f_name = str_replace('.', '', $f_name);
                $f_name = $this->generateSlug($f_name);
                $f_name = $f_name . '.' . $ex;
                // store our uploaded file in our uploads folder
                $file->move($uploadPath, $f_name);
                // set our results to have our asset path
                $name = '/' . $assetPath . '/' . $f_name;
                $id = str_replace('.', 'separator', $f_name);
                $results[] = compact('name', 'i', 'id');
                $i++;
            }
        }

        // return our results in a files object
        return array(
            'files' => $results
        );
    }


    /** Temporary
     * @return mixed
     */
    public function setRandViewSoldForProducts()
    {
        /*$list = CatalogProduct::lists('id');
        foreach ($list as $item) {

            $rand_sold1= rand(100, 700);
            $rand_sold2 = rand(100, 700);

            $rand_views1 = rand(2000, 5000);
            $rand_views2 = rand(2000, 5000);

            CatalogProduct::where('id', '=', $item)->update(
                [
                    'views' => max($rand_views1, $rand_views2),
                    'sold' => min($rand_sold1, $rand_sold2)
                ]
            );
        }

        return Redirect::to('administrator/settings')
            ->with('success', ('Success'));*/

        $lists = CatalogProduct::where('views', '<', '1000')->orWhere('sold', '<', '300')->get();

        foreach ($lists as $list) {
            $list->update([
                'views' => rand(1000, 2000),
                'sold' => rand(300, 700),
            ]);
        }

        return Redirect::to('administrator/products')
            ->with('success', ('Добавлены просмотры/заказы ' . count($lists) . ' товарам'));
    }


    /** Delete all products
     * @return mixed
     */
    public function deleteAll()
    {
        /*CatalogProduct::truncate();
        RegisterProductCategory::truncate();
        CatalogProductImage::truncate();*/
        $temp = public_path('uploads/temp');
        File::cleanDirectory($temp);

        return Redirect::to('administrator/settings')
            ->with('success', trans('admin.deleteAllSuccess'));
    }


    /** Functions for settings - add, update, delete
     */
    public function listSettings()
    {
        $settings = Settings::all();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * @param Settings $setting
     * @return \Illuminate\View\View
     */
    public function editSetting(Settings $setting)
    {

        return view('admin.settings.edit', compact('setting'));
    }

    public function addSetting()
    {
        return view('admin.settings.create');
    }

    /*
     * @param Request $request
     */
    public function storeSetting(Request $request)
    {
        if ($request->has('key_name') && $request->get('key_name')) {
            $setting = new Settings();
            $setting->key_name = $request->get('key_name');
            $setting->value = $request->get('value');
            $setting->description = $request->get('description');
            $setting->save();
        }

        return redirect('administrator/settings')
            ->with('success', trans('admin.newSettingCreateSuccess'));
    }

    /**
     * @param Settings $setting
     * @param Request $request
     * @return
     */
    public function updateSetting(Settings $setting, Request $request)
    {
        $setting->key_name = $request->get('key_name');
        $setting->value = $request->get('value');
        $setting->description = $request->get('description');
        $setting->save();

        return redirect('administrator/settings')
            ->with('success', trans('admin.newSettingCreateSuccess'));
    }

    /**
     * @param Request $request
     */
    public function deleteSetting(Request $request)
    {
        foreach ($request->items as $setting) {
            Settings::where('id', '=', $setting['itemId'])
                ->delete();
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getSearchWordsLog()
    {

        $search_words = ProductLog::orderBy('created_at', 'desc')
            ->paginate(100);

        return view('admin.catalog.searchLog', compact('search_words'));

    }

    /**
     * @return \Illuminate\View\View
     */
    public function indexJobLog()
    {
        $jobs = JobLog::orderBy('job');

        Session::forget('dayRange');
        Session::forget('selectedDateStart');
        Session::forget('selectedDateEnd');

        if (Input::has('dayRange') && Input::get('dayRange') != 0) {
            $date = Carbon::now();
            $date->subDays(Input::get('dayRange'));
            $jobs = $jobs->where('created_at', '>=', $date);
            Session::put('dayRange', Input::get('dayRange'));
        } else {

            if (Input::has('selectedDateStart')) {
                $selectedDateStart = new Carbon(Input::get('selectedDateStart'));
                $jobs = $jobs->where('created_at', '>=', $selectedDateStart);
                Session::put('selectedDateStart', Input::get('selectedDateStart'));
            }

            if (Input::has('selectedDateEnd')) {
                $selectedDateEnd = new Carbon(Input::get('selectedDateEnd'));
                $jobs = $jobs->where('created_at', '<=', $selectedDateEnd);
                Session::put('selectedDateEnd', Input::get('selectedDateEnd'));
            }
        }

        $jobs = $jobs->orderBy('created_at', 'desc')
            ->get();
        $jobs = $jobs->groupBy('job');

        $day_range = ['0' => '', '1' => '1 day', '3' => '3 days', '7' => '7 days', '14' => '14 days'];
        $dayRange = Input::has('dayRange') ? Input::get('dayRange') : 0;

        return view('admin.log.index', compact(
            'jobs',
            'day_range',
            'dayRange',
            'selectedDateEnd',
            'selectedDateStart'
        ));
    }

    /**
     * @param $jobName
     * @return \Illuminate\View\View
     */
    public function showJobLog($jobName)
    {
        $jobLog = JobLog::where('job', $jobName);
        if (Session::has('dayRange')) {
            $date = Carbon::now();
            $date->subDays(Session::get('dayRange'));
            $jobLog = $jobLog->where('created_at', '>=', $date);
        } else {

            if (Session::has('selectedDateStart')) {
                $selectedDateStart = new Carbon(Session::get('selectedDateStart'));
                $jobLog = $jobLog->where('created_at', '>=', $selectedDateStart);
            }

            if (Session::has('selectedDateEnd')) {
                $selectedDateEnd = new Carbon(Session::get('selectedDateEnd'));
                $jobLog = $jobLog->where('created_at', '<=', $selectedDateEnd);
            }
        }

        $jobLog = $jobLog->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.log.jobLog', compact(
            'jobLog',
            'jobName'
        ));
    }

    /**
     * @param Request $request
     */
    public function updateSortCategory(Request $request)
    {
        $i = 1;
        foreach ($request->menu_items as $id) {
            $menu = CatalogCategory::find($id);
            $menu->sort = $i;
            $menu->save();
            $i++;
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function mailIndex()
    {
        $mails = Mailing::paginate(20);

        return view('admin.mail.index', compact('mails'));
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function mailShow($id)
    {
        $mail = Mailing::find($id);
        if (!$mail) {

            return redirect()->back();
        }
        $user_email_list = User::lists('name', 'email');
        $email_list = [];
        if (count($user_email_list)) {
            foreach ($user_email_list as $email => $value) {
                $v = Validator::make(
                    ['email' => $email],
                    ['email' => [
                        'required',
                        'email'
                    ]
                    ]);

                if (!$v->fails()) {
                    $email_list[$email] = $value;
                }
            }
        }

        $emails_in_text = str_replace("\r", '', $mail->participants);
        $mail->participants_array = explode(PHP_EOL, $emails_in_text);

        return view('admin.mail.show', compact('mail', 'email_list'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function mailCreate()
    {
        $user_email_list = User::lists('name', 'email');
        $email_list = [];
        if (count($user_email_list)) {
            foreach ($user_email_list as $email => $value) {
                $v = Validator::make(
                    ['email' => $email],
                    ['email' => [
                        'required',
                        'email'
                    ]
                    ]);

                if (!$v->fails()) {
                    $email_list[$email] = $value;
                }
            }
        }

        return view('admin.mail.create', compact('email_list'));
    }

    /**
     * @param MailingRequest $mailingRequest
     * @return Redirect
     */
    public function mailStore(MailingRequest $mailingRequest)
    {
        if (count($mailingRequest->emails)) {
            $emails_in_text = str_replace("\r", '', $mailingRequest->participants);
            $participants_manual = explode(PHP_EOL, $emails_in_text);
            $participants = array_unique(array_merge($mailingRequest->emails, $participants_manual));
            $participants = implode(PHP_EOL, $participants);
        } else {
            $participants = $mailingRequest->participants;
        }

        $mail = new Mailing();
        $mail->subject = $mailingRequest->subject;
        $mail->body = $mailingRequest->body;
        $mail->participants = $participants;

        if ($mailingRequest->has('send')) {

            $emails_in_text = str_replace("\r", '', $participants);
            $participants = explode(PHP_EOL, $emails_in_text);
            Mailing::mailSend($participants, $mail->subject, $mail->body);
            $mail->hit = 1;
        }
        $mail->save();

        return redirect('administrator/mail');
    }

    /**
     * @param MailingRequest $mailingRequest
     * @return Redirect
     */
    public function mailUpdate(MailingRequest $mailingRequest, $id)
    {
        $mail = Mailing::find($id);
        if (!$mail) {

            return redirect()->back();
        }
        if (count($mailingRequest->emails)) {
            $emails_in_text = str_replace("\r", '', $mailingRequest->participants);
            $participants_manual = explode(PHP_EOL, $emails_in_text);
            $participants = array_unique(array_merge($mailingRequest->emails, $participants_manual));
            $participants = implode(PHP_EOL, $participants);
        } else {
            $participants = $mailingRequest->participants;
        }

        $mail->subject = $mailingRequest->subject;
        $mail->body = $mailingRequest->body;
        $mail->participants = $participants;

        if ($mailingRequest->has('send')) {

            $emails_in_text = str_replace("\r", '', $participants);
            $participants = explode(PHP_EOL, $emails_in_text);
            Mailing::mailSend($participants, $mail->subject, $mail->body);
            $mail->hit = $mail->hit + 1;
        }
        $mail->save();

        return redirect('administrator/mail');
    }


    /**
     * @return \Illuminate\View\View
     */
    public function getTrashedMails()
    {
        $mails = Mailing::onlyTrashed()
            ->paginate(20);

        return view('admin.mail.index', compact(
            'mails'
        ));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function archiveMail(Request $request)
    {
        foreach ($request->items as $review) {
            Mailing::where('id', '=', $review['itemId'])
                ->delete();
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function restoreMail(Request $request)
    {
        foreach ($request->items as $review) {
            Mailing::where('id', '=', $review['itemId'])
                ->restore();
        }
    }

    /**
     * @param Request $request
     */
    public function deleteMail(Request $request)
    {
        foreach ($request->items as $review) {
            Mailing::where('id', '=', $review['itemId'])
                ->forcedelete();
        }
    }

    /*public function getXmlProducts()
    {
        $array = CatalogProduct::take(5)->select('id','name_ru','price')->get()->toArray();
 //$array = collect($array)->groupBy('id');
 //dd($array);
 $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><mydoc></mydoc>');
 $xml_res = array_to_xml($array, $xml);

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><mydoc></mydoc>');

        $xml->addAttribute('version', '1.0');
        $xml->addChild('datetime', date('Y-m-d H:i:s'));

        $person = $xml->addChild('person');
        $person->addChild('firstname', 'Someone');
        $person->addChild('secondname', 'Something');
        $person->addChild('telephone', '123456789');
        $person->addChild('email', 'me@something.com');

        $address = $person->addchild('address');
        $address->addchild('homeaddress', 'Andersgatan 2, 432 10 Göteborg');
        $address->addChild('workaddress', 'Andersgatan 3, 432 10 Göteborg');

        $response = response($xml->asXML(), 200);
        $response->header('Content-Type', 'text/xml');

        return $response;
    }*/

    /**
     * For IE 8-
     * @return \Illuminate\View\View
     */
    public function forIe8()
    {
        JobLog::writeJob('IE8', ' ', 'Somebody comes here!!!', 1, true);

        return view('ie8');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getInfoFromUkrPoshta()
    {

        $result = Order::processCheckStatusOfOrdersUkrPoshta(true);

        return view('admin.infoUkrPoshta', compact('result'));
    }

    /**
     * @param WaitingProductRequest $request
     * @return array
     */
    public function waitForProductSave(WaitingProductRequest $request)
    {
        $waiting = WaitingProductUser::firstOrNew([
            'email' => $request->email,
            'product_id' => $request->product_id
        ]);
        $waiting->notified = false;
        $waiting->name = $request->name;
        $waiting->save();

        return [
            'btn' => trans('product.addedForNotify'),
            'info' => trans('product.notifyInfo')
        ];
    }


    /**
     * @return array
     */
    public function waitForProductSaveAuth()
    {
        $waiting = WaitingProductUser::firstOrNew([
            'email' => Auth::user()->email,
            'product_id' => Input::get('product_id')
        ]);
        $waiting->notified = false;
        $waiting->name = Auth::user()->getFullName();
        $waiting->save();

        return [
            'btn' => trans('product.addedForNotify'),
            'info' => trans('product.notifyInfo')
        ];
    }

    /**
     * @param Request $request
     */
    public function callMeSave(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|regex:/(\+38)\((0)[0-9]{2}\)\s[0-9]{3}\-[0-9]{4}/'
        ]);


        $campaign = null;

        if (Session::has('token_company')) {
            $campaigns = Session::get('token_company');

            ksort($campaigns);
            $campaign = last($campaigns);

            $campaign = AdvertisingCampaign::active()
                ->where('token', $campaign)
                ->first();
        }

        $input = Input::all();
        $callMe = new CallMe();
        $callMe->phone = $input['phone'];
        $callMe->campaign_name = $campaign ? $campaign->name : '';

        if (isset($input['qty_hours'])) {
            $time = $input['day_of_week'] . ' ' . $input['qty_hours'] . ':' . $input['qty_minuts'];
            $time = Carbon::parse($time);
            $callMe->call_time = $time->toDateTimeString();
        }

        $callMe->save();


        /*
            $customer = new Customer;
            $customer->phone = $request->get('phone');
            $customer->product_id = $request->get('product_id');
            $customer->communication_type = $request->get('btn_name');
            $customer->advertising_campaign_id = $campaign ? $campaign->id : null;
            $customer->new = true;
        */

        if ($callMe->save()) {

            $this->dispatch(new AddNewCountCampaign($request, $campaign ? $campaign->id : 0, 0, ItemAdvertisingCampaign::NUMBER_PHONES));

            $this->dispatch(new SendCallMeNumber($callMe, ItemAdvertisingCampaign::NUMBER_VISITS));

            return;

        } else {

        }
        return;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function callMeIndex()
    {
        $call_mes = CallMe::orderBy('completed')
            ->paginate(100);

        return view('admin.callMe.index', compact('call_mes'));
    }

    /**
     *
     */
    public function callMeSetCompleted()
    {
        CallMe::where('id', Input::get('call_id'))
            ->update([
                'completed' => true
            ]);

        return;
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function exportOrdersToXLS(Request $request)
    {
        $start = $request->start ? strtotime($request->start) : null;
        $end = $request->end ? strtotime($request->end) : null;

        $data = Order::select('orders.id', 'orders.order_number', 'order_items.id as order_item_id', 'orders.created_at',
            'order_items.product_upi', 'order_items.product_quantity', 'orders.contacts')
            ->leftJoin('order_items', 'order_items.order_id', '=', 'orders.id');
        if ($start) {
            $data = $data->where('orders.created_at', '>', date('Y-m-d H:i:s', $start));
        }
        if ($end) {
            $data = $data->where('orders.created_at', '<', date('Y-m-d H:i:s', $end));
        }
        $data = $data->get()->toArray();

        $result = [];
        foreach ($data as $item) {
            $orderData = unserialize($item['contacts']);


            $result[] = [
                'order-number' => $item['order_number'],
                'order_item_id' => $item['order_item_id'],
                'purchase_date' => $item['created_at'],
                'upi' => $item['product_upi'],
                'quantity' => $item['product_quantity'],
                'full-name' => isset($orderData['d_user_name']) ?  $this->transliterate($orderData['d_user_name']) : '',
                'address' => isset($orderData['d_user_address']) ? $orderData['d_user_address'] : '',
                'city' => isset($orderData['d_user_city']) ? $orderData['d_user_city'] : '',
                'state' => isset($orderData['d_user_region']) ? $orderData['d_user_region'] : '',
                'country' => 'Ukraine',
                'postal-code' => isset($orderData['d_user_index']) ? $orderData['d_user_index'] : '',
                'phone' => isset($orderData['d_user_phone']) ? $orderData['d_user_phone'] : '',
                'email' => isset($orderData['d_user_email']) ? $orderData['d_user_email'] : '',
                'shipping ' => ''
            ];
        }

        return Excel::create('Orders', function ($excel) use ($result) {
            $excel->sheet('orders', function ($sheet) use ($result) {
                $sheet->fromArray($result);
            });
        })->download('xls');

    }

    protected function transliterate($string)
    {
        $cyrillic  = [
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
            'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
        ];
        $latin = [
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya',
            'A', 'B', 'V', 'G', 'D', 'E', 'Io', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
            'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya'
        ];
        $textCyrillic = str_replace($cyrillic, $latin, $string);
        return $textCyrillic;
    }
}
