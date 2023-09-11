<?php namespace App\Http\Controllers;

use App\Commands\NewOrderMail;
use Illuminate\Support\Facades\Cookie;
use App\Commands\SendInvalidPaymentWFP;
use App\Http\Controllers\Payment\PayPalController;
use App\Http\Requests\ChargeRequest;
use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\JobLog;
use App\Models\LiqPay;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Settings;
use App\Models\User;
use App\Services\Registrar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{

    /*
     *
     */
    const SETTING_NAME_LIQPAY = 'liqpay_live';

    public function charge(ChargeRequest $request)
    {
        $order = Order::find($request->order_id);

        if (empty($order)) {
            return $this->failOrder();
        }
        // TODO check if order not found

//        \Stripe\Stripe::setApiKey('sk_test_GhZ8vMo0p3RmXvv6DnbnnAYk002exCT4g6');
        \Stripe\Stripe::setApiKey('sk_live_Y1tbJ50KSuxw9fF2tD52CGAq001VqizmIw');

        $charge = \Stripe\Charge::create(
            [
                'amount'        => ($order->order_total + $order->delivery_cost) * 100, // cents
                'currency'      => 'usd',
                'description'   => 'Example charge',
                'source'        => $request->stripeToken,
                'metadata'      => ['order_id' => $order->id],
                'receipt_email' => 'kerzpoltava@gmail.com',
            ]
        );

        $order->payment_id = $charge->id;
        $order->order_status = Order::STATUS_PAID;
        $order->save();

        if ($charge->status == 'succeeded') {

            JobLog::writeJob(
                'Stripe',
                $order->id,
                $charge->status,
                $charge->amount / 100,
                true
            );

            return $this->successOrder();
        }

        return $this->failOrder();
    }

    public function create_stripe(OrderRequest $request)
    {
        $order = Order::create(['order_number' => Session::get('cart_id')]);

        // Check if user is auth (not needed more because not auth users can't buy products, but it's can change)
        $order->user_id = Auth::check() ? Auth::user()->id : null;

        // register new user if not exist
        if (empty($order->user_id)) {
            $user_data = [
                'email'               => $request->email,
                'name'                => $request->name,
                'last_name'           => $request->last_name,
                'password'            => '12345678',
                'registered_on_order' => true
            ];

            $user = User::firstOrNew(['email' => $user_data['email']]);

            if ($user->exists) {
                $order->user_id = $user->id;
            } else {
                $registrar = new Registrar();
                $user = $registrar->create($user_data);

                $order->user_id = $user->id;
                Session::put('is_new_user', $user->id);
            }
            Session::put('order_user_name', $user->name . ' ' . $user->last_name);
        } else {
            Session::put('order_user_name', Auth::user()->name . ' ' . Auth::user()->last_name);
        }

        $id = Cart::where('id', '=', Session::get('cart_id'))
            ->with(
                [
                    'cart_products' => function ($query) {
                        $query->with('product');
                    }
                ]
            )
            ->first();

        $total_sum = 0;
        $total_weight = 0;
        $products_for_wfp = [];
        $quantities_for_wfp = [];
        $prices_for_wfp = [];

        foreach ($id->cart_products as $product) {

            if ($product->product) {

                $total_sum += $product->quantity * $product->product->price;
                $total_weight += $product->product->weight;

                $order_item = new OrderItem;
                $order_item->order_id = $order->id;
                $order_item->product_id = $product->product_id;
                $order_item->product_quantity = $product->quantity;
                $order_item->product_price = $product->product->price;
                $order_item->product_upi = $product->upi_id;
                $order_item->product_sku = $product->product->sku;
                $order_item->product_name = $product->product->name_en;
                $order_item->deletion_mark_user = false;
                $order_item->save();

                $products_for_wfp[] = $product->product->name_en;
                $quantities_for_wfp[] = $product->quantity;
                $prices_for_wfp[] = $product->product->price;

            }

        }

        $contacts = [];
        $contacts['d_user_name'] = $request->name;
        $contacts['d_user_region'] = '';
        $contacts['d_user_city'] = $request->d_user_city /*Order::getCityName($request->d_user_city)*/
        ;
        $contacts['d_user_address'] = $request->d_user_address;
        $contacts['d_user_index'] = $request->d_user_index ?: '';
        $contacts['d_user_phone'] = $request->d_user_phone;
        $contacts['d_user_last_name'] = $request->last_name;
        $contacts['d_user_email'] = $request->email;

        $order->contacts = serialize(
            Order::getOrderContacts($contacts)
        );

        $coupon_sum = 0;

        if ($request->coupon) {

            $coupon = Coupon::where('code', $request->coupon)
                ->where('expired_at', '>', date('Y-m-d'))
                ->first();

            if ($coupon) {

                if ($coupon->count > $coupon->orders->count()) {

                    $order->coupon_id = $coupon->id;
                    $coupon_sum = $coupon->amount;
                }

            }
        }

        $order->delivery_cost = round(0.015 * $total_weight, 2);

        $order->order_total = round($total_sum - $coupon_sum, 2);
        $order->order_status = Order::STATUS_WAITING;
        $order->comment = $request->comment;
        $order->save();

        // if user auth - update his data
        if ($order->user_id) {
            $user = User::find($order->user_id);
            $user->contacts = serialize(
                User::getUserContacts($contacts)
            );
            $user->save();
        }

        // put order id to session
//        Session::put('order_id', $order->id);

        $this->clearCartSession();

        return response()->json(['result' => 'OK', 'order_id' => $order->id]);
    }

    /** Creating new order or updating old one
     * @param OrderRequest $request
     * @return string
     */
    public function create(OrderRequest $request)
    {

        return $this->create_stripe($request);

        $order = Order::create(['order_number' => Session::get('cart_id')]);

        // Check if user is auth (not needed more because not auth users can't buy products, but it's can change)
        $order->user_id = Auth::check() ? Auth::user()->id : null;

        $id = Cart::where('id','=',Session::get('cart_id'))
            ->with(['cart_products' => function($query){
                $query->with('product');
            }])
            ->first();

        $total_sum = 0;
        $total_weight = 0;
        $products_for_wfp = [];
        $quantities_for_wfp = [];
        $prices_for_wfp = [];

        foreach ($id->cart_products as $product){

            if ($product->product) {

                $total_sum += $product->quantity * $product->product->price;
                $total_weight += $product->product->weight;

                $order_item = new OrderItem;
                $order_item->order_id = $order->id;
                $order_item->product_id = $product->product_id;
                $order_item->product_quantity = $product->quantity;
                $order_item->product_price = $product->product->price;
                $order_item->product_upi = $product->upi_id;
                $order_item->product_sku = $product->product->sku;
                $order_item->product_name = $product->product->name_en;
                $order_item->deletion_mark_user = false;
                $order_item->save();

                $products_for_wfp[] = $product->product->name_en;
                $quantities_for_wfp[] = $product->quantity;
                $prices_for_wfp[] = $product->product->price;

            }

        }

        $contacts = [];
        $contacts['d_user_name'] = $request->name;
        $contacts['d_user_region'] = '';
        $contacts['d_user_city'] = $request->d_user_city /*Order::getCityName($request->d_user_city)*/;
        $contacts['d_user_address'] = $request->d_user_address;
        $contacts['d_user_index'] = $request->d_user_index ? :'';
        $contacts['d_user_phone'] = $request->d_user_phone;
        $contacts['d_user_last_name'] = $request->last_name;
        $contacts['d_user_email'] = $request->email;

        $order->contacts = serialize(
            Order::getOrderContacts($contacts)
        );

        $coupon_sum = 0;

        if ($request->coupon) {

            $coupon = Coupon::where('code', $request->coupon)->where('expired_at', '>', date('Y-m-d'))->first();

            if ($coupon) {

                if ($coupon->count > $coupon->orders->count()) {

                    $order->coupon_id = $coupon->id;
                    $coupon_sum = $coupon->amount;
                }

            }
        }

    // get delivery cost as total with all delivery parameters
        //$order->delivery_cost = Cart::getDeliveryCostInfo(collect($id->cart_products)->lists('quantity', 'product_id'), true); //$deliveryCost +

    // get delivery cost as collection from delivery parameters
        $getDeliveryCostInfo = Cart::getDeliveryCostInfo(collect($id->cart_products)->lists('quantity', 'product_id'));
        //$order->delivery_cost = (int)$getDeliveryCostInfo->ua_deliv_price;
        //$order->delivery_cost = ceil($total_weight / 100) * 2;
        $order->delivery_cost = round(0.015 * $total_weight, 2);

        $order->order_total = round($total_sum - $coupon_sum, 2);
        $order->order_status = Order::STATUS_WAITING;
        $order->comment = $request->comment;
        $order->save();

        // put order id to session
        Session::put('order_id', $order->id);

        // if user auth - update his data
        if($order->user_id) {
            $user = User::find($order->user_id);
            $user->contacts = serialize(
                User::getUserContacts($contacts)
            );
            $user->save();
        }

        // Pay forms
        $public_key = config('app.public_key_liqpay');
        $private_key = config('app.private_key_liqpay');
        $liqpay = new LiqPay($public_key, $private_key);
        $username = explode(' ', $request->name);
        $params = [
            'version'               => '3',
            'public_key'            => $public_key,
            'private_key'           => $private_key,
            'action'                => 'pay',
            'amount'                => $order->order_total + $order->delivery_cost,
            'currency'              => 'USD',
            'description'           => 'Заказ (#' . $order->id . ') на сайте allfor2.com',
            'order_id'              => $order->id,
            'server_url'            => url('payment/liqpay/status'),
            'result_url'            => url('order/success'),
            'sender_first_name'     => head($username),
            'sender_last_name'      => last($username),
            'sender_country_code'   => '804',
            'sender_city'           => $request->d_user_city/*Order::getCityName($request->d_user_city)*/,
            'sender_address'        => $request->d_user_address,
            'sender_postal_code'    => $contacts['d_user_index']
        ];

        if(Settings::checkSetting(self::SETTING_NAME_LIQPAY,false)) {
            $params['sandbox'] = '1';
        }

        // for testing
        //$params['amount'] = 4;
        //$params['currency'] = 'UAH';
        //$params['sandbox'] = '1';

        $liqpay_form = $liqpay->cnb_form($params);
        $liqpay_sign = $liqpay->cnb_signature($params);
        $liqpay_data = base64_encode(json_encode($params));

        //$liqpay_sign = $liqpay->str_to_sign($private_key . $liqpay_data . $private_key);

        //$wallet = WalletOneController::getPayment($order->order_total + $order->delivery_cost);
        //$webMoney = WebMoneyController::getPayment($order->order_total + $order->delivery_cost, $order->id);
        $payPal = PayPalController::getPayForm();

        /* wayforpay */

        $wfp = Order::$param_wayforpay;
        $time = time();

        $i = 1;

        $string = [];
        $string[] = $wfp[$i]['merchantAccount'];
        $string[] = $wfp[$i]['merchantDomainName'];
        $string[] = $order->id;
        $string[] = $time;
        $string[] = ($order->order_total + $order->delivery_cost - $coupon_sum);
        $string[] = 'UAH';

        $string_s = implode(';', $string);

        $string_s .= ';' . implode(';', $products_for_wfp);
        $string_s .= ';' . implode(';', $quantities_for_wfp);
        $string_s .= ';' . implode(';', $prices_for_wfp);


        //*-----FOR TESTING with test-data-----*//


        /*$i = 0;
        $order_n = $order->id + 9999;

        $string = [];
        $string[] = $wfp[$i]['merchantAccount'];
        $string[] = $wfp[$i]['merchantDomainName'];
        $string[] = $order_n;
        $string[] = $time;
        $string[] = (1 + $order->delivery_cost);
        $string[] = 'UAH';

        $string_s = implode(';', $string);

        $string_s .= ';' . implode(';', $products_for_wfp);
        $string_s .= ';1';
        $string_s .= ';1';*/

        //*-----FOR TESTING with test-data-----*//


        $merchantSecretKey = $wfp[$i]['merchantSecretKey'];
        $merchantSignature = hash_hmac("md5",$string_s,$merchantSecretKey);

        $hash_order_url =  url('/delivery/'.hash_hmac("md5",$order->id,Order::$hash_order_key).'/'.$order->id);


        /* wayforpay */

        return response()->json([
            'result'        => 'OK',
            //'deliveryCost'  => $deliveryCost,
            //'wallet'        => $wallet,
            'liqpay'        => $liqpay_form,
            'liqpay_data'   => $liqpay_data,
            'liqpay_sign'   => $liqpay_sign,
            //'webMoney'      => $webMoney,
            'payPal'        => $payPal,
            //for wayforpay
            'hash_order_url'     => $hash_order_url,
            'order'=> $order,
            'merchantSignature' =>$merchantSignature,
            'merchantAccount' => $wfp[$i]['merchantAccount'],
            'merchantDomainName' => $wfp[$i]['merchantDomainName'],
            'time'=>$time,
            'products_for_wfp' => $products_for_wfp,
            'quantities_for_wfp' => $quantities_for_wfp,
            'prices_for_wfp' => $prices_for_wfp,
            'send_error_url_wfp' => url('/invalid-payment-wfp/'.$order->id)
        ]);
    }

    /**
     * @param $hash_order_id
     * @param $order_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function orderInStatusPaidWFP($hash_order_id, $order_id)
    {
        $order = Order::find($order_id);

        if($order && $hash_order_id == hash_hmac("md5", $order->id, Order::$hash_order_key))
        {
            $order->order_status = Order::STATUS_PAID;
            $order->save();

            $order = $order->user_id ? $order->load('user') : $order;
            $order = $order->user_id ? $order->load('user') : $order;

            $this->dispatch(new NewOrderMail($order));

            $success_url = url('/order/success');

            return response()->json([
                'status' => 'OK',
                'success_url' => $success_url
            ]);
        }
    }

    /**
     * @param OrderRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function saveUserAddress(OrderRequest $request)
    {
        // if user auth - update his data
        if(Auth::check()) {

            $contacts = [];
            $contacts['d_user_region'] = Order::getRegionNameByCityId($request->d_user_city);
            $contacts['d_user_city'] = Order::getCityName($request->d_user_city);
            $contacts['d_user_address'] = $request->d_user_address;
            $contacts['d_user_index'] = $request->d_user_index;
            $contacts['d_user_phone'] = $request->d_user_phone;
            $contacts['last_name'] = $request->last_name;

            $user = User::find(Auth::user()->id);
            $user->contacts = serialize(
                User::getUserContacts($contacts)
            );
            $user->save();

            $validated_address = $request->name . ', '. $request->last_name . ', ' .
                $contacts['d_user_address'] . ', ' .$contacts['d_user_city'] . ', ' .
                $contacts['d_user_region'] . ', ' . $contacts['d_user_index'] . ', ' .
                $contacts['d_user_phone'] . ', ' . $request->email;

            return response()->json([
                'result'        => 'OK',
                'address'       => $validated_address
            ]);
        }

        return response()->json([
            'result'        => 'ERROR'
        ]);
    }


    /**
     * @return mixed
     */
    public function failOrder()
    {
        return Redirect::route('cart')
            ->with('error', trans('cart.errorPay'));
    }

    /**
     * @return mixed
     */
    public function successOrder()
    {
        if (Session::has('order_id')) {
            $order = Order::where('id',Session::get('order_id'))
                ->where('order_status',Order::STATUS_PAID)
                ->first();

            $data = [];
            if($order){
                if (Session::has('is_new_user')) {
                    $data['is_new_user'] = true;
                }
                $data['order_user_name'] = Session::get('order_user_name');
                $cookie = $this->clearCartSession();

                return response()->view('order.success', $data)
                    ->withCookie($cookie);
            }

            return redirect()->route('cart');
        }

        return redirect()->route('cart');

    }

    public  function invalidPaymentWFP($order_id)
    {
        $order = Order::find($order_id);

        if($order) {

            $order = $order->user_id ? $order->load('user') : $order;

            $this->dispatch(new SendInvalidPaymentWFP($order));
        }
    }

    /**
     * @return mixed
     */
    private function clearCartSession()
    {
        // forget session data
        Session::forget('order_user_name');
        Session::forget('order_id');
        Session::forget('cart_id');
        Session::forget('delivery_cost');
        Session::forget('cart_products');
        Session::forget('is_new_user');

        return Cookie::forget('cart_uid');
    }
}