<?php namespace App\Http\Controllers\Payment;

use App\Models\JobLog;
use Session;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class WebMoneyController extends Controller
{
    /*
	 *
	 */
    protected $name = 'WebMoney';

    /**
     * @param Request $request
     * @return string
     */
    public function getPaymentStatus(Request $request)
    {
        // Checking valid data from payment
        if ($request->LMI_PREREQUEST == 1){
            if ($request->LMI_PAYEE_PURSE != Config::get('webmoney.WM_SHOP_PURSE_WMU')){

                return trans('cart.webMoneyWrongPayee');
            }

            $id = Order::where('id',$request->LMI_PAYMENT_NO)
                ->with('order_item')
                ->first();

            if(is_null($id)){

                return trans('cart.webMoneyNoProducts');
            }
            if(($id->deletion_mark == 1) || ($id->order_status != Order::STATUS_WAITING)){

                return trans('cart.webMoneyWrongMarkProducts');
            }
            $total_sum = 0;

            foreach ($id->order_item as $product){
                $total_sum += $product->product_quantity * $product->product_price;
            }
            if (round($request->LMI_PAYMENT_AMOUNT,0) != round($total_sum,0)){

                return trans('cart.webMoneyWrongSum');
            }

            return "YES";
        }else{
            // Next step of checking order - getting secret hash code
            $secret_key = Config::get('webmoney.LMI_SECRET_KEY');
            $common_string = $request->LMI_PAYEE_PURSE.$request->LMI_PAYMENT_AMOUNT;
            $common_string .= $request->LMI_PAYMENT_NO.$request->LMI_MODE;
            $common_string .= $request->LMI_SYS_INVS_NO.$request->LMI_SYS_TRANS_NO;
            $common_string .= $request->LMI_SYS_TRANS_DATE.$secret_key.$request->LMI_PAYER_PURSE;
            $common_string .= $request->LMI_PAYER_WM;
            $hash = strtoupper(hash("sha256",$common_string));

            if($hash != $request->LMI_HASH) {
                if($request->LMI_PAYMENT_NO) {

                    JobLog::writeJob(
                        $this->name,
                        $request->LMI_PAYMENT_NO,
                        'false'
                    );

                }
            }else{
                Order::changeStatusOrder($request->LMI_PAYMENT_NO, Order::STATUS_PAID);
                Order::success($request->LMI_PAYMENT_NO);

                JobLog::writeJob(
                    $this->name,
                    $request->LMI_PAYMENT_NO,
                    'success',
                    $request->LMI_PAYMENT_AMOUNT,
                    true
                );
            }
        }
    }

    /**
     * @param $amount
     * @param $order_id
     * @return string
     */
    public static function getPayment($amount, $order_id)
    {
        $fields = array();
        // Добавление полей формы в ассоциативный массив
        $fields["LMI_PAYMENT_AMOUNT"] = $amount;
        $fields["LMI_PAYMENT_DESC_BASE64"] = base64_encode("Оплата заказа #".$order_id." на allfor2.com");
        $fields["LMI_PAYMENT_NO"] = $order_id;
        $fields["LMI_PAYEE_PURSE"] = Config::get('webmoney.WM_SHOP_PURSE_WMU');
        $fields["LMI_SIM_MODE"] = Config::get('webmoney.LMI_SIM_MODE');

        $form = "<form action='https://merchant.webmoney.ru/lmi/payment.asp' id='webmoney' method='POST'>";

        foreach($fields as $key => $val)
        {
            $form .= "<input type='hidden' name='$key' value='$val'/>";
        }

        $form .= "<input type='submit' class='wmbtn' style='font-famaly:Verdana, Helvetica, sans-serif!important;padding:0 10px;height:30px;font-size:12px!important;border:1px solid #538ec1!important;background:#a4cef4!important;color:#fff!important;' value=' &#1086;&#1087;&#1083;&#1072;&#1090;&#1080;&#1090;&#1100; " . $amount . " WMU '></form>";

        return $form;
    }
}