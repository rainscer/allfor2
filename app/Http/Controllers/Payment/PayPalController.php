<?php namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CatalogProduct;
use App\Models\JobLog;
use App\Models\Order;
use App\Models\Settings;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use PayPal\Api\InputFields;
use PayPal\Api\ShippingAddress;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class PayPalController extends Controller
{

    private $_api_context;
    /*
         *
         */
    const SETTING_NAME_PAYPAL = 'paypal_live';
    /*
	 *
	 */
    protected $name = 'PayPal';

    public function __construct()
    {
        $paypal_conf = Config::get('paypal');
        if(app('Setting')->getSettingValue(self::SETTING_NAME_PAYPAL,false)) {
            $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
            $this->_api_context->setConfig($paypal_conf['settings']);
        }else{
            $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id_sandbox'], $paypal_conf['secret_sandbox']));
            $this->_api_context->setConfig($paypal_conf['settings_sandbox']);
        }
    }

    /**
     * @return mixed
     */
    public function postPayment()
    {

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $items = [];
        $totalSum = 0;

        $product_ids = collect(Session::get('cart_products'))
            ->lists('quantity', 'product_id');

        $products = CatalogProduct::whereIn('id', array_keys($product_ids)) // DB::table('catalog_products')
            ->get();

        $curency = 'curency_'.App::getLocale();
        $curency = app('Setting')->getSettingValue($curency, 1);

        foreach ($products as $product) {
            $item = new Item();
            $item->setName($product->name_en)
                ->setCurrency('USD')
                ->setQuantity($product_ids[$product->id])
                ->setPrice(round($product->price / $curency,2));
            $items[] = $item;
            $totalSum += $product_ids[$product->id] * round($product->price / $curency,2);
        }

        // get delivery cost
        $deliveryCost = Cart::getDeliveryCostInfo($product_ids, true); //$deliveryCost +

        $deliveryCost = round($deliveryCost / $curency, 2);
        $totalSumWithDelivery = $totalSum + $deliveryCost;

        $details = new Details();
        $details->setSubtotal($totalSum)
            ->setShipping($deliveryCost);

        // get shipping address
        $order = Order::where('orders.id', Session::get('order_id'))
            ->first();

        $order->contacts = unserialize($order->contacts);
        $order->contacts = (array)$order->contacts;

        foreach ($order->contactFields as $field) {
            isset($order->contacts[$field]) ? $order->$field = $order->contacts[$field] : $order->$field = '';
        }

        $shipping_address = new ShippingAddress();
        $shipping_address->setCity($order->d_user_city);
        $shipping_address->setCountryCode('UA');
        $shipping_address->setPostalCode($order->d_user_index);
        $shipping_address->setLine1($order->d_user_address);
        $shipping_address->setState($order->d_user_region);
        $shipping_address->setRecipientName($order->d_user_name);

        // add item to list
        $item_list = new ItemList();
        $item_list->setItems($items)
            ->setShippingAddress($shipping_address);

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($totalSumWithDelivery)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Pay for products');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('payment.status'))
            ->setCancelUrl(URL::route('payment.status'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            if (Config::get('app.debug')) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                $err_data = json_decode($ex->getData(), true);
                exit;
            } else {
                die('Some error occur, sorry for inconvenient');
            }
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // add payment ID to session
        Session::put('paypal_total', $totalSumWithDelivery);
        Session::put('paypal_payment_id', $payment->getId());
        Session::put('paypal_payment_order_id', Session::get('order_id'));

        if(isset($redirect_url)) {
            // redirect to paypal
            return Redirect::away($redirect_url);
        }

        return Redirect::route('cart')
            ->with('error', 'Unknown error occurred');
    }

    /**
     * @return mixed
     */
    public function getPaymentStatus()
    {
        // Get the payment ID before session clear
        $payment_id = Session::get('paypal_payment_id');
        $order_id = Session::get('paypal_payment_order_id');
        $curency = 'curency_'.App::getLocale();
        $curency = app('Setting')->getSettingValue($curency, 1);
        $order_total = Session::get('paypal_total');
        $order_total = $order_total * $curency;

        if(!$payment_id || !$order_id){

            return redirect()->route('cart')
                ->with('errorPay' , 'Error');
        }

        // clear the session payment ID
        Session::forget('paypal_payment_id');
        Session::forget('paypal_payment_order_id');
        Session::forget('paypal_total');

        if ((Input::get('PayerID')=='') || (Input::get('token'))=='') {

            JobLog::writeJob(
                $this->name,
                $order_id,
                'false',
                $order_total
            );

            return Redirect::route('cart')
                ->with('errorPay' , 'Error');
                //->with('error', 'Оплата не произошла. Счёт №'.$order_id.' не оплачен');
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        // PaymentExecution object includes information necessary
        // to execute a PayPal account payment.
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        //Execute the payment
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') { // payment made

            JobLog::writeJob(
                $this->name,
                $order_id,
                'success',
                $order_total,
                true
            );

            Order::changeStatusOrder($order_id,Order::STATUS_PAID);
            Order::success($order_id);

            return redirect('order/success');
        }

        return Redirect::route('cart')
            ->with('errorPay' , 'Error');
    }

    /**
     * @return string
     */
    public static function getPayForm()
    {
        $form = "<form action='" . url('payment/paypal') . "' method='POST' id='paypal'>";
        $form .= "<input type='hidden' name='_token' value='" . csrf_token() . "'>";
        $form .= "<input type='image' src='" . asset('/images/pp.png') . "' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
        $form .= "<img alt='' border='0' src='https://www.paypalobjects.com/en_US/i/scr/pixel.gif' width='1' height='1'></form>";

        return $form;
    }
}