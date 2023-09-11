<?php namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class WalletOneController extends Controller
{
    /**
     * @param $sum
     * @return string
     */
    public static function getPayment($sum)
    {
        $key = Config::get('walletOne.key');
        $fields = array();
        // Добавление полей формы в ассоциативный массив
        $fields["WMI_MERCHANT_ID"] = Config::get('walletOne.merchant_id');
        $fields["WMI_PAYMENT_AMOUNT"] = $sum;
        $fields["WMI_CURRENCY_ID"] = Config::get('walletOne.currency_id');
        $fields["WMI_PAYMENT_NO"] = Session::get('cart_id');
        $fields["WMI_DESCRIPTION"] = "BASE64:" . base64_encode("Оплата заказа #".Session::get('cart_id')." на allfor2.com");
        $fields["WMI_SUCCESS_URL"] = Config::get('walletOne.success_url');
        $fields["WMI_FAIL_URL"] = Config::get('walletOne.fail_url');

        //Сортировка значений внутри полей
        foreach ($fields as $name => $val) {
            if (is_array($val)) {
                usort($val, "strcasecmp");
                $fields[$name] = $val;
            }
        }

        // Формирование сообщения, путем объединения значений формы,
        // отсортированных по именам ключей в порядке возрастания.
        uksort($fields, "strcasecmp");
        $fieldValues = "";

        foreach ($fields as $value) {
            if (is_array($value))
                foreach ($value as $v) {
                    //Конвертация из текущей кодировки (UTF-8)
                    //необходима только если кодировка магазина отлична от Windows-1251
                    $v = iconv("utf-8", "windows-1251", $v);
                    $fieldValues .= $v;
                }
            else {
                //Конвертация из текущей кодировки (UTF-8)
                //необходима только если кодировка магазина отлична от Windows-1251
                $value = iconv("utf-8", "windows-1251", $value);
                $fieldValues .= $value;
            }
        }

        // Формирование значения параметра WMI_SIGNATURE, путем
        // вычисления отпечатка, сформированного выше сообщения,
        // по алгоритму MD5 и представление его в Base64

        $signature = base64_encode(pack("H*", md5($fieldValues . $key)));

        //Добавление параметра WMI_SIGNATURE в словарь параметров формы

        $fields["WMI_SIGNATURE"] = $signature;

        //return $signature;// Формирование HTML-кода платежной формы


        $form = "<form action='https://wl.walletone.com/checkout/checkout/Index' method='POST'>";

        foreach($fields as $key => $val)
        {
            if(is_array($val))
                foreach($val as $value)
                {
                    $form .= "<input type='hidden' name='$key' value='$value'/>";
                }
            else
                $form .= "<input type='hidden' name='$key' value='$val'/>";
        }

        $form .= "<input type='submit' value='Wallet one'/></form>";

        return $form;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getPaymentStatus(Request $request)
    {
        $skey =Config::get('walletOne.key');
        // Проверка наличия необходимых параметров в POST-запросе

        if (!isset($request->WMI_SIGNATURE))
            return $this->print_answer("Retry", "Отсутствует параметр WMI_SIGNATURE");

        if (!isset($request->WMI_PAYMENT_NO))
            return $this->print_answer("Retry", "Отсутствует параметр WMI_PAYMENT_NO");

        if (!isset($request->WMI_ORDER_STATE))
            return $this->print_answer("Retry", "Отсутствует параметр WMI_ORDER_STATE");

        // Извлечение всех параметров POST-запроса, кроме WMI_SIGNATURE

        foreach($request as $name => $value)
        {
            if ($name !== "WMI_SIGNATURE") $params[$name] = $value;
        }

        // Сортировка массива по именам ключей в порядке возрастания
        // и формирование сообщения, путем объединения значений формы

        uksort($params, "strcasecmp"); $values = "";

        foreach($params as $name => $value)
        {
            //Конвертация из текущей кодировки (UTF-8)
            //необходима только если кодировка магазина отлична от Windows-1251
            $value = iconv("utf-8", "windows-1251", $value);
            $values .= $value;
        }

        // Формирование подписи для сравнения ее с параметром WMI_SIGNATURE

        $signature = base64_encode(pack("H*", md5($values . $skey)));

        //Сравнение полученной подписи с подписью W1

        if ($signature == $request->WMI_SIGNATURE)
        {
            if (strtoupper($request->WMI_ORDER_STATE) == "ACCEPTED")
            {
                Order::changeStatusOrder($request->WMI_PAYMENT_NO,Order::STATUS_PAID);

                return $this->print_answer("Ok", "Заказ #" . $request->WMI_PAYMENT_NO . " оплачен!");
            }
            else
            {
                // Случилось что-то странное, пришло неизвестное состояние заказа

                return $this->print_answer("Retry", "Неверное состояние ". $request->WMI_ORDER_STATE);
            }
        }
        else
        {
            // Подпись не совпадает, возможно вы поменяли настройки интернет-магазина

            return $this->print_answer("Retry", "Неверная подпись " . $request->WMI_SIGNATURE);
        }
    }


    /**
     * @param $result
     * @param $description
     * @return string
     */
    public function print_answer($result, $description)
    {
        return "WMI_RESULT=" . strtoupper($result) . "&WMI_DESCRIPTION=" .urlencode($description);
    }
}