<?php namespace App\Models;

use Config;
use Cookie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Session;
use SoapClient;
use SoapFault;
use stdClass;
use Validator;

/**
 * Class Order
 * @property integer                                    id
 * @property integer                                    user_id
 * @property integer                                    order_number
 * @property float                                      order_total
 * @property float                                      delivery_cost
 * @property string                                     order_status
 * @property integer                                    last_office_index
 * @property string                                     delivery_description
 * @property string                                     contacts
 * @property string                                     tracking_number
 * @property boolean                                    api
 * @property boolean                                    deletion_mark
 * @property \App\Models\OrderItem                      order_item
 * @property \App\Models\User                           user
 * @package App\Models
 */
class Order extends Model {

    protected $fillable = [
        'user_id',
        'order_number',
        'order_total',
        'last_office_index',
        'delivery_description',
        'tracking_number',
        'order_status',
        'delivery_cost',
        'api',
        'coupon_id',
        'payment_id'
    ];

    /**
     * @var array
     */
    public $contactFields = [
        'd_user_name',
        'd_user_last_name',
        'd_user_region',
        'd_user_city',
        'd_user_address',
        'd_user_index',
        'd_user_phone',
        'd_user_email',
    ];

    /**
     *
     */
    public $all_statuses = [
        'paid'      => 'paid',
        'waiting'   => 'waiting',
        'delivered' => 'delivered'
    ];

    /*
     *
     */
    const PACKING_COST_SETTING_NAME = 'packing_cost';

    /*
     *
     */
    const UA_DELIVERY_COST_SETTING_NAME = 'ua_deliv_cost';
    /**
     *
     */
    const STATUS_PAID = 'paid';
    /**
     *
     */
    const STATUS_WAITING = 'waiting';
    /**
     *
     */
    const STATUS_DELIVERED = 'delivered';
    /**
     * документ доставлен и оприходован получателем
     */
    const STATUS_ARRIVED = 'arrived';
    /*
     * Payment status success
     */
    const STATUS_SUCCESS = 'success';

    /*
     *
     */
    public $track_alias = [
        'LM',
        'LS',
        'TT'
    ];

    /*
     *
     */
    const TOTO_TRACK = 'TT';

    /*
     *
     */
    public static $param_wayforpay = [
        '0' => ['merchantAccount'=>'test_merch_n1', 'merchantSecretKey'=>'flk3409refn54t54t*FNJRET', 'merchantDomainName'=>'klangdorf.korovo.com'],
        '1' => ['merchantAccount'=>'korovo_com','merchantSecretKey'=>'8cd5aca9f464eafdc522fbd3169b5513b861f051', 'merchantDomainName'=>'korovo.com'],
    ];

    /*
     *
     */
    public static $hash_order_key = 'FNJRET';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function order_item()
    {
        return $this->hasMany(
            'App\Models\OrderItem',
            'order_id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(
            'App\Models\User',
            'user_id'
        );
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('deletion_mark', '=', 0);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeApi($query)
    {
        return $query->where('api', '=', 1);
    }

    /**
     * @return bool
     */
    public function isTotoTrack()
    {
        $value = substr(trim($this->tracking_number),0,2);
        if ($value == self::TOTO_TRACK){

            return true;
        }
        return false;
    }

    /**
     * @param null $track
     * @return bool
     */
    public function isValidTrack($track = null)
    {
        !is_null($track) ? : $track = $this->tracking_number;
        $value = substr(trim($track),0,2);
        if (in_array($value, $this->track_alias)){

            return true;
        }
        return false;
    }

    /**
     *
     */
    public function setDelivered()
    {
        $this->order_status = self::STATUS_DELIVERED;
        $this->save();
    }


    /**
     * @param $id
     * @param int|string $status
     */
    public static function changeStatusOrder($id,$status)
    {
        $order = self::where('id', $id)
            ->with('order_item')
            ->first();

        if($status == self::STATUS_PAID){
            // set order as new
            $order->new = true;

            foreach ($order->order_item as $product){
                // find all order-items where is current product that not paid and set deleted them for user
                OrderItem::with('order')
                    ->whereHas('order', function ($query) {
                        $query->where('orders.order_status','=',self::STATUS_WAITING);
                    })
                    ->where('product_id','=',$product->product_id)
                    ->update([
                        'deletion_mark_user' => true
                    ]);
            }
        }

        $order->order_status = $status;
        $order->save();

    }

    /** Finish success paid order
     * @param $order_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function success($order_id)
    {
        $order = Order::where('id',$order_id)
            ->with('order_item')
            ->where('order_status',Order::STATUS_PAID)
            ->notDeleted()
            ->first();

        if($order) {
            $new_user = User::checkUserOrCreateNew($order);
            if($new_user){
                Order::where('id',$order_id)
                    ->with('order_item')
                    ->where('order_status',Order::STATUS_PAID)
                    ->notDeleted()
                    ->update([
                       'user_id' =>  $new_user
                    ]);
            }
            self::sendMail($order_id);
            Cart::clearAndFinish($order->order_number, $order->order_item);
        }
    }

    /**
     * @param $order_id
     * @return bool
     */
    public static function sendMail($order_id)
    {
        $order = self::with('order_item')
            ->where('orders.id',$order_id)
            ->notDeleted()
            ->first();

        $order->contacts = unserialize($order->contacts);
        $order->contacts = (array)$order->contacts;

        foreach ($order->contactFields as $field) {
            isset($order->contacts[$field]) ? $order->$field = $order->contacts[$field] : $order->$field = '';
        }

        //Send mail for admin about order
        Mail::send('emails.successOrder',
            [
                'order' => $order,
                'admin' => true
            ], function($message) use ($order)
            {
                $message->to(Config::get('mail.admin_order'))
                    ->subject('Заказ на сайте allfor2.com');
            });

        if(isset($order->contacts['d_user_email']) && $order->contacts['d_user_email'])
        {
            //Send mail to customer if email isset
            Mail::send('emails.successOrder', ['order' => $order], function($message) use ($order)
            {
                $message->to($order->contacts['d_user_email'])->subject('Заказ на сайте allfor2.com');
            });
        }

    }

    /**
     * @return array
     */
    public static function processCheckStatusesOfOrders()
    {
        // get orders that sent by api to devoffice and has status PAID - maybe leater we don't need check of status
        $orders = collect(self::where('order_status', self::STATUS_PAID)
            ->notDeleted()
            ->api()
            ->lists('id'));

        $orders = $orders->map(function ($item) {
            return config('app.shop_code') . '-' . $item;
        })->toArray();

        $remote_data_temp['order_numbers'] = serialize($orders);

        $result = [];

        $response = remote(
            config('app.api_checkStatuses'),
            $remote_data_temp
        );

        if (!isValidResponse($response)) {
            return false;
        }

        foreach($response->value as $order){
            $order_id = explode('-', $order->order_id);

            $order_found = self::where('id', last($order_id))
                ->where('order_status', '<>', self::STATUS_DELIVERED)
                ->first();

            if(!$order_found){
                continue;
            }

            $edited = false;
            if($order_found->isValidTrack($order->tracking_number)){

                if($order_found->tracking_number != $order->tracking_number){
                    $order_found->tracking_number = $order->tracking_number;
                    $edited = true;
                }
            }
            /*if($order->last_action == self::STATUS_DELIVERED){

                $order_found->order_status = self::STATUS_DELIVERED;
                $edited = true;
            }*/

            if($edited){
                $order_found->save();
                $result[] = '#' . last($order_id);//. ' status ' . $order->last_action . ' tr: ' . $order->tracking_number;
            }
        }
        // вернуть массив строк с описанием результата выполнения
        return $result;
    }

    /**
     * Recalculate order total and delivery cost
     * @param Order $order
     */
    public static function recalculateOrder(Order $order)
    {
        $order_total = 0;

        foreach($order->order_item as $item)
        {
            $order_total += $item->product_quantity * $item->product_price;
        }
        $quantity = $order->order_item->lists('product_quantity', 'product_id');

        $order->order_total = $order_total;
        $order->delivery_cost = Cart::getDeliveryCostInfo($quantity,true);
        $order->save();
    }

    /**
     * @param $dataArray
     * @return array
     */
    public static function getOrderContacts($dataArray)
    {
        if (!is_array($dataArray)) {
            $dataArray = (array)$dataArray;
        }

        foreach ($dataArray as $key => $row) {
            $dataArray[$key] = $row;
        }

        return array_intersect_key(
            (array)$dataArray,
            array_flip((new self)->contactFields)
        );
    }

    /**
     * @param $id
     * @return string
     */
    public static function getRegionNameByCityId($id)
    {
        $region = DB::table('d_region')
            ->join('d_city', function ($join) {
                $join->on('d_region.id', '=', 'd_city.region_id');
            })
            ->where('d_city.id', '=', $id)
            ->select('d_region.id','d_region.name')
            ->first();

        return !is_null($region) ? $region->name : 'Unknown';
    }

    /**
     * @param $id
     * @return string
     */
    public static function getRegionIdByCityId($id)
    {
        $region = DB::table('d_region')
            ->join('d_city', function ($join) {
                $join->on('d_region.id', '=', 'd_city.region_id');
            })
            ->where('d_city.id', '=', $id)
            ->select('d_region.id','d_region.name')
            ->first();

        return !is_null($region) ? $region->id : 0;
    }

    /**
     * @param $id
     * @return string
     */
    public static function getRegionName($id)
    {
        $region = DB::table('d_region')
            ->where('id', $id)
            ->select('id','name')
            ->first();

        return !is_null($region) ? $region->name : 'Unknown';
    }

    /**
     * @param $id
     * @return string
     */
    public static function getCityName($id)
    {
        $city = DB::table('d_city')
            ->where('id', $id)
            ->select('id','name')
            ->first();

        return !is_null($city) ? $city->name : 'Unknown';
    }

    /**
     * @param $id
     * @return string
     */
    public static function getRegionName2($id)
    {
        $region = DB::table('d_region')
            ->where('id', $id)
            ->select('id','name')
            ->first();

        return !is_null($region) ? $region->name : null;
    }

    /**
     * @param $id
     * @return string
     */
    public static function getCityName2($id)
    {
        $city = DB::table('d_city')
            ->where('id', $id)
            ->select('id','name')
            ->first();

        return !is_null($city) ? $city->name : null;
    }

    /**
     * @param bool $forAdmin
     * @return array
     */
    public static function processCheckStatusOfOrdersUkrPoshta($forAdmin = false)
    {
        try {
            $client = new SoapClient('http://services.ukrposhta.com/barcodestatistic/barcodestatistic.asmx?WSDL',
                [
                    'cache_wsdl' => WSDL_CACHE_NONE,
                    'exceptions' => true
                ]);
        }catch (SoapFault $fault){
            return $fault->getMessage();
        }
        $params = new stdClass();
        $params->guid = 'fcc8d9e1-b6f9-438f-9ac8-b67ab44391dd'; // guid - своего рода API-key
        $params->culture = 'uk'; // Указание, на каком языке сервис будет отдавать нам информацию

        $orders = self::where('order_status', Order::STATUS_PAID)
            ->whereNotNull('tracking_number')
            ->get();

        $results = [];

        $delivered_statuses = [
            '41002',
            '41003',
            '41004'
        ];

        $isDelivery = '21501';

        foreach($orders as $order){
            $params->barcode = $order->tracking_number;
            $result = $client->GetBarcodeInfo($params)->GetBarcodeInfoResult;
            if($forAdmin){
                $results[] = $order->tracking_number . ' ' . $result->eventdescription;
            }else{
                if ($result->code != ' ') {

                    if (in_array($result->code, $delivered_statuses)) {
                        $order->setDelivered();
                        $order->last_office_index = $result->lastofficeindex;
                        $order->delivery_description = trim(str_replace('\n', '', $result->eventdescription));
                        $order->save();
                        $results[] = $order->tracking_number;
                    }
                    if ($result->lastofficeindex != $order->last_office_index) {
                        $order->last_office_index = $result->lastofficeindex;
                        $order->delivery_description = trim(str_replace('\n', '', $result->eventdescription));
                        $order->save();

                        if ($result->code != $isDelivery) {
                            self::sendMailAboutDelivery($order);
                            $results[] = $order->tracking_number;
                        }
                    }
                    if ($result->code == $isDelivery) {
                        self::sendMailAboutDelivery($order, true);
                        $results[] = $order->tracking_number . '(delivered)';
                    }
                }
            }
        }
        return $results;
    }


    /**
     * @param Order $order
     * @param bool|false $delivered
     */
    public static function sendMailAboutDelivery(Order $order, $delivered = false)
    {
        $order->contacts = unserialize($order->contacts);
        $order->contacts = (array)$order->contacts;

        if(isset($order->contacts['d_user_email']) && $order->contacts['d_user_email'])
        {
            //Send mail to customer if email isset
            Mail::send('emails.orderDeliveryStatus', ['order' => $order, 'delivered' => $delivered], function($message) use ($order)
            {
                $message->to($order->contacts['d_user_email'])->subject('Статус заказа на сайте allfor2.com');
            });
        }

    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
}
