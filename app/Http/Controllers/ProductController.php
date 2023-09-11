<?php namespace App\Http\Controllers;

use App\Commands\AddNewCountCampaign;
use App\Commands\QuestionAboutProduct;
use App\Http\Requests\ReviewRequest;
use App\Models\AdvertisingCampaign;
use App\Models\ImagesToReview;
use App\Models\ItemAdvertisingCampaign;
use App\Models\User;
use Cache;
use Session;
use App\Models\Menu;
use File;
use App\Models\ProductLikes;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\CatalogProduct;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use stdClass;


/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{

    /** Show product by product slug
     * @param $upi_id
     * @param CatalogProduct $product
     * @return \Illuminate\View\View
     */
    public function show($upi_id, CatalogProduct $product, Request $request)
    {
        // if not found return view
        if(!isset($product->id)){

            return view('product.index');
        }

        // find product by given slug
        $product->load(['image' => function ($query) {
                $query->orderBy('id', 'asc');
            }])
            ->load('category')
            ->load(['attribute' => function ($query) {
                $query->with(['attribute_name' => function ($query) {
                    $query->with(['products_attributes' => function ($query){
                        $query->with('product');
                    }]);
                }]);
            }]);

        //find reviews and qa for this product
        $reviews = Review::with('user')->active()
            ->typeReview()
            ->with('images')
            ->where('product_id', '=', $product->id)
            ->orderBy('created_at','desc')
            ->paginate(3);

        $qas = Review::withUser()
            ->typeQA()
            ->withAnswer()
            ->where('product_id', '=', $product->id)
            ->orderBy('created_at','desc')
            ->paginate(3);

        // set view + 1 and save it
        $product->real_views = $product->real_views + 1;
        $product->save();

        // На каждой странице товара должны быть случайные цифры покупок (от 50 до 150) и просмотров (от 100 до 300).
        // Количество купленных не должно превышать, на конкретной странице, количество просмотров

        $product->sold = rand(50, 150);
        $product->real_views = rand(100, 300);
        if ($product->sold > $product->real_views) {
            $product->sold -= 50;
        }

        // get dop products
        /*if (isset($product->category->first()->slug)) {
            $dop_products = CatalogProduct::getCategoryDopProducts($product->category->first()->slug,$product->slug);
        }*/

        if($request->ajax()) {
            if(Session::has('product_ids')) {
                $referenceIds = Session::get('product_ids');
            }else{
                abort(404);
            }
        }else{
            // forget duplicate upi_id (that has attributes)
            Session::forget('product_upi_block');
            // get product ids
            $referenceIds = CatalogProduct::getShuffleProductIds();
        }

        // get count of pages
//        $dop_products = CatalogProduct::getProducts($referenceIds, $request->page);


        // get user city for review and qa
        $user_city = null;
        if(Auth::check()){
            $user = Auth::user();
            $user->contacts = unserialize($user->contacts);
            $user->contacts = (array)$user->contacts;

            if(isset($user->contacts['d_user_city']) && $user->contacts['d_user_city'] != ''){
                $user_city = $user->contacts['d_user_city'];
            }
        }

        User::saveProductPageToUser($product->id);

        return view(
            'product.index',
            compact(
                'product',
                // 'dop_products',
                'reviews',
                'user_city',
                'qas'
            )
        );
    }

    public function dopProducts()
    {
        $dop_products = CatalogProduct::getRandomDopProducts();

        $html = view('product.dopProducts', compact(
            'dop_products'
        ))->render();

        if (count($dop_products)){
            return response()->json([
                'status' => 'OK',
                'list' => $html
            ]);
        }

    }

    /** Used for get method of product url and for load reviews and qa pagination
     * @param $upi_id
     * @param CatalogProduct $product
     * @param Request $request
     * @return array|\Illuminate\View\View
     */
    public function showForGetMethod(Request $request, $upi_id, $slug = null)
    {
        $product = CatalogProduct::where('upi_id', $upi_id)->first();
        if(!isset($product->id)){

            return redirect('/')
                ->withErrors(trans('home.productNotFound'));
        }
        // for product by "get" method (if user put url to address)
        if($request->has('type')){
            // get data for review or QA on product page by ajax for pagination

            return self::getReviewOrQAProduct($product, $request->get('type'));

        }else{

            $product->load(['image' => function ($query) {
                $query->orderBy('id', 'asc');
            }]);
            // get local name and image
            $local = "name_" . App::getLocale();
            $title = $product->$local;
            $local_desc = "description_" . App::getLocale();
            $description = $product->$local_desc;
            $image = $product->image->sortBy('sort')->first()->image_url;
            $local_meta_keywords = "meta_keywords_" . App::getLocale();
            $meta_keywords = $product->$local_meta_keywords;

            $product_open_modal = $product;
        }
        // get left menu
        $menu = Menu::show();

        // get product ids
        $referenceIds = CatalogProduct::getShuffleProductIds();

        // get count of pages
        $products_count = ceil(count($referenceIds)/config('app.count_per_page'));
        $products = CatalogProduct::getProducts($referenceIds, is_null($request->page) ? 1 : $request->page);

        // check for banner on home page
        if(app('Setting')->checkSetting(CatalogProduct::SETTING_NAME_BANNER,true)){
            $banner = true;
        }

        $this->dispatch(new AddNewCountCampaign($request, 0, $product->id, ItemAdvertisingCampaign::NUMBER_VISITS));

        return view('catalog.index', compact(
            'products',
            'products_count',
            'product_open_modal',
            'description',
            'meta_keywords',
            'title',
            'menu',
            'image',
            'banner'
        ));
    }

    /**
     * Used to give paginated reviews and qa
     * @param CatalogProduct $product
     * @param $type
     * @return \Illuminate\View\View
     */
    public static function getReviewOrQAProduct(CatalogProduct $product, $type)
    {
        // if given type is review we search for available reviews with pagination
        if ($type == 'review') {

            $reviews = Review::with(['user' => function ($query) {
                $query->select('id', 'name', 'email', 'image');
            }])->active()
                ->with('images')
                ->typeReview()
                ->where('product_id', '=', $product->id)
                ->orderBy('created_at','desc')
                ->paginate(3);

            return view('product.reviewItem', compact('reviews'));
            // if qa - do same but with qa
        }elseif($type == 'qa') {

            $qas = Review::withUser()
                ->typeQA()
                ->withAnswer()
                ->where('product_id', '=', $product->id)
                ->orderBy('created_at','desc')
                ->paginate(3);

            return view('product.qaItem', compact('qas'));
        }
    }

    /**
     * @param CatalogProduct $product_id
     * @return \Illuminate\View\View
     */
    public function addReview(CatalogProduct $product_id)
    {
        $product = $product_id;

        return view('user.order.reviewModal', compact('product'));
    }

    /** Store review or QA by ajax
     * @param ReviewRequest $request
     * @return string
     */
    public function storeReviewOrQA(ReviewRequest $request)
    {
        $review = new Review();
        if (Auth::check()) {
            $review->user_id = Auth::user()->id;
            $review->email = Auth::user()->email;
        }else{
            $review->quest = $request->name;
            $review->email = $request->email;
        }
        //$review->city = $request->city;
        $review->product_id = $request->product_id;
        $review->text = $request->text;

        if($request->type == 'review'){
            $review->rating = $request->rating;
            $review->save();
            if($request->image_review) {
                $moved_images = CatalogProduct::saveReviewImages($request->image_review);
                $images_to_save = [];
                foreach ($moved_images as $image) {
                    $images_to_save[] = new ImagesToReview(['image' => $image]);
                }
                $review->images()->saveMany($images_to_save);
            }

            return trans('home.reviews-save');
        }else{
            $review->type = $request->type;
            $review->save();

            $qa =  $review->load(['product', 'user']);

            $this->dispatch(new QuestionAboutProduct($qa));

            return trans('home.qa-save');
        }
    }

    /** Redirect to product page by found upi_id
     * @param CatalogProduct $upi_id_route
     * @return mixed
     */
    public function showByUpi(CatalogProduct $upi_id_route)
    {
        if(!isset($upi_id_route->id)){

            return redirect('/')
                ->withErrors(trans('home.productNotFound'));
        }
        return Redirect::route('product.url',[$upi_id_route->upi_id, $upi_id_route->slug]);
    }

    /** Add like on product page
     * @param Request $request
     * @return string
     */
    public function addLike(Request $request)
    {
        if($request->ajax()) {
            if (Auth::check()) {
                ProductLikes::addLike($request->product_id);

                return response()->json([
                    'message' => trans('product.likeAdded')
                ]);
            }else{
                return response()->json([
                    'quest'   => true,
                    'message' => trans('product.loginFirst')
                ]);
            }
        }
    }

    /**
     * @param $slug
     * @param $token
     * @return mixed
     */
    public function viewProductByToken(Request $request, $slug, $token)
    {
        $campaign = AdvertisingCampaign::active()
            ->where('slug', $slug)
            ->where('token', $token)
            ->first();

        if($campaign){
            $product = CatalogProduct::where('id', $campaign->product_id)->first();

            $this->dispatch(new AddNewCountCampaign($request, $campaign->id, $campaign->product_id, ItemAdvertisingCampaign::NUMBER_VISITS));

            return redirect('product/'.$product->upi_id . '/' .$product->slug);

        }else{
            return redirect('/');
        }
    }
}
