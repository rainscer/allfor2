<?php namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Mailing;
use App\Models\Review;
use Carbon\Carbon;
use Cookie;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductLikes;
use App\Models\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /*
     *
     */
    private $user_id;

    /**
     *
     */
    public function __construct()
    {
        $this->user_id = Auth::check() ? Auth::user()->id : null;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function getActivate(Request $request)
    {
        $userId = $request->userId;
        $activationCode = $request->activationCode;

        $user = User::find($userId);
        if (!$user) {
            return $this->getMessage(trans('user.wrongUrl'));
        }

        if ($user->activateUser($activationCode)) {
            Auth::login($user);
            return $this->getMessage(trans('user.successActivation'), "/");
        }

        return $this->getMessage(trans('user.wrongUrl'));
    }

    /**
     * @param $message
     * @param bool|false $redirect
     * @return \Illuminate\View\View
     */
    protected function getMessage($message, $redirect = false) {

        return view('message', compact(
            'message',
            'redirect'
        ));
    }


    /**
     * @return \Illuminate\View\View
     */
    public function getUserProfile()
    {
        $user = User::getUserWithData($this->user_id);

        $title = trans('user.title');

        return view('user.index',
            compact(
                'user',
                'title'
            )
        );
    }

    /**
     * @param Request $request
     */
    public function deleteUserProfileProductLikes(Request $request)
    {
        ProductLikes::where('product_id', '=', $request->id)
            ->where('user_id', '=', Auth::user()->id)
            ->delete();
    }

    /**
     * @param Request $request
     */
    public function deleteUserProfileProductNotPaid(Request $request)
    {
        $user_id = $this->user_id;
        // setting deletion mark for product from all orders with status "waiting" for user
        OrderItem::where('product_id','=',$request->id)
            ->whereHas('order', function($query) use ($user_id)
            {
                $query->where('user_id','=',$user_id)
                    ->where('order_status', '=' , Order::STATUS_WAITING);

            })
            ->update([
                'deletion_mark_user' => true
            ]);
    }

    public function deleteUserProfileProductCart(Request $request)
    {
        $user_id = $this->user_id;
        // setting deletion mark for product from all orders with status "waiting" for user
        CartProduct::where('product_id', '=', $request->id)
            ->whereHas(
                'cart',
                function ($query) use ($user_id) {
                    $query->where('user_id', '=', $user_id);

                }
            )->delete();
    }

    /** Add products from user page to cart
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addToCartUserProfile(Request $request)
    {
        $carts = new Cart();

        foreach ($request->products as $product)
        {
            $cart = $carts->addProduct($product['itemId'], 1);
            // if its new - save cookie
            if($cart['new']) {
                $cookies = Cookie::make('cart_uid', $cart['cart_uid']);
            }
        }
        // if we have cookie to set return empty response view with cookie
        if(isset($cookies)){

            return response()
                ->view('user.empty')
                ->withCookie($cookies);
        }
    }

    /**
     * @param $status
     * @return \Illuminate\View\View
     */
    public function getUserProfileProductByStatus($status)
    {
        $user_id = $this->user_id;
        $user = User::getUserWithData($this->user_id);
        $statuses = [
            Order::STATUS_DELIVERED,
            Order::STATUS_PAID
        ];

        if(in_array($status,$statuses)) {

            $orders = Order::where('order_status', '=', $status)
                ->wherehas('user',function($query) use ($user_id){
                    $query->where('users.id',$user_id);
                })
                ->with(
                    ['order_item' => function($query){
                        $query->activeProductWithImage();
                    }])
                ->notDeleted()
                ->get();

            $now = Carbon::now();

        }elseif($status == Order::STATUS_WAITING) {

            $orders = OrderItem::with('order')
                ->whereHas('order',function($query) use ($user_id, $status){
                    $query->where('order_status','=',$status)
                        ->with('user')
                        ->whereHas('user',function($query) use ($user_id){
                            $query->where('users.id',$user_id);
                        })->notDeleted();
                })
                ->where('deletion_mark_user', '=', false)
                ->activeProductWithImage()
                ->groupBy('product_id')
                ->get();

            $title = trans('user.title');

            $carts = $user->cart->filter(
                function ($cart) {
                    return ($cart->deletion_mark == false && $cart->posted == false && !$cart->cart_products->isEmpty());
                }
            )->lists('cart_products');

            return view('user.order.waiting',
                compact(
                    'orders',
                    'carts',
                    'title',
                    'user'
                )
            );

        }else{

            return Redirect::back();
        }
        $title = trans('user.title');

        return view('user.order.orders',
            compact(
                'orders',
                'status',
                'now',
                'title',
                'user'
            )
        );
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getUserProfileProductLikes()
    {
        $user = User::getUserWithData($this->user_id);
        $title = trans('user.title');

        return view('user.products.likes',
            compact(
                'user',
                'title'
            )
        );
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getUserProfileProductVisited()
    {
        $user = User::getUserWithData($this->user_id);
        $title = trans('user.title');

        return view('user.products.visited',
            compact(
                'products',
                'title',
                'user'
            )
        );
    }
    /**
     * @return \Illuminate\View\View
     */
    public function getUserSetting()
    {
        $user = User::getUserWithData($this->user_id);

        $user->contacts = unserialize($user->contacts);
        $user->contacts = (array)$user->contacts;

        foreach ($user->contactFields as $field) {
            isset($user->contacts[$field]) ? $user->$field = $user->contacts[$field] : $user->$field = '';
        }
        $cities = User::getCities();
        $title = trans('user.setting_title');

        return view('user.edit',compact(
            'cities',
            'title',
            'user'
        ));
    }

    /**
     * @param UserRequest $request
     * @return mixed
     */
    public function saveUserSetting(UserRequest $request)
    {
        $user = User::find(Auth::user()->id);
        $contacts = [];

        if($request->d_user_city > 0) {
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
        $request->password == '' ? : $user->password = bcrypt($request->password);

        $user->save();

        return Redirect::to('user')
            ->with('success', trans('user.successSave'));
    }

    /**
     * @param Request $request
     * Send mail to admin with question from user
     */
    public function sendMail(Request $request){

        Mail::send('emails.question', ['text' => $request->text], function($message)
        {
            $message->to(Config::get('mail.admin_question'))->subject('Вопрос на сайте allfor2.com');
        });
    }

    /**
     * @return mixed
     */
    public function setAsSupport()
    {
        $user = User::firstOrCreate([
            'name' => 'support',
            'active' => true,
            'isActive' => true
        ]);

        Auth::logout();
        Auth::loginUsingId($user->id);

        return redirect()->back();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getQa()
    {
        $currentUserId = Auth::user()->id;
        $user = User::getUserWithData($currentUserId);

        $qas = Review::has('product')->allowProduct()
            ->typeQA()
            ->orderBy('created_at','desc');

        if(Auth::user()->name == 'support'){
            $support = true;
        }else {
            $qas = $qas->forUser($currentUserId);
            $support = false;
        }

        $qas = $qas->paginate(10);


        return view('user.qa.indexQa',compact(
            'qas',
            'user',
            'support'
        ));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getQaTrashed()
    {
        if(Auth::user()->name == 'support'){
            $currentUserId = Auth::user()->id;
            $user = User::getUserWithData($currentUserId);

            $qas = Review::has('product')->allowProduct()
                ->typeQA()
                ->onlyTrashed()
                ->orderBy('created_at','desc')
                ->paginate(10);

            $support = true;

            return view('user.qa.indexQa',compact(
                'qas',
                'user',
                'support'
            ));
        }else{

            return redirect()->back();
        }
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function showQa($id)
    {
        $currentUserId = Auth::user()->id;
        $user = User::getUserWithData($currentUserId);

        $qa = Review::has('product')->allowProduct()
            ->typeQA()
            ->withUser()
            ->where('id',$id)
            ->first();

        if(Auth::user()->name == 'support'){
            $support = true;
            $qa->markAsReadSupport();
        }else{
            $support = false;
            $qa->markAsReadUser();
        }

        return view('user.qa.showQa',compact(
            'qa',
            'user',
            'support'
        ));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateQa($id)
    {
        $qa = Review::find($id);
        if(!$qa || !Input::has('answer') || Input::get('answer') == ''){
            return response()->json([
                'result' => 'ERROR'
            ]);
        }else{
            $qa->answer = Input::get('answer');
            $qa->markAsAnsweredSupport();
            $qa->markAsUnReadForUser();
            $qa->save();

            if($qa->user_id){
                Mailing::sendMailToUserNewMessage($qa->user_id);
            }

            if ($qa->email) {
                Mail::send('emails.newMessageAnswer', ['qa' => $qa],
                    function ($message) use ($qa) {
                        $message->to($qa->email)->subject(trans('user.newMessageOnKorovo'));
                    });
            }

            $html = view('user.qa.answerOnQa', [
                'message' => $qa->answer
            ])->render();

            return response()->json([
                'result' => 'OK',
                'messages' => $html
            ]);
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteQa()
    {
        $input = Input::all();
        if(Input::has('delete_qas')){
            if(Input::has('archive')){
                Review::whereIn('id', $input['delete_qas'])
                    ->delete();
            }elseif(Input::has('delete')){
                Review::whereIn('id', $input['delete_qas'])
                    ->withTrashed()
                    ->forceDelete();
            }elseif(Input::has('restore')){
                Review::withTrashed()
                    ->whereIn('id', $input['delete_qas'])
                    ->restore();
            }
        }

        return redirect()->back();
    }

}
