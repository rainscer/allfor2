<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use Crawler;

/**
 * Class ItemAdvertisingCampaign
 * @property integer                                    id
 * @property integer                                    id_campaign
 * @property integer                                    number_of_visits
 * @property integer                                    number_of_phones
 * @property integer                                    number_of_deals
 * @property integer                                    number_of_refunded
 * @property float                                      campaign_cost
 * @property float                                      campaign_profit
 * @property boolean                                    flag_changed
 * @property integer                                    product_id
 * @property \App\Models\AdvertisingCampaign            advertisingCampaign
 * @package App\Models
 */

class ItemAdvertisingCampaign extends BaseAdminModel
{
    /**
     *
     */
    const NUMBER_VISITS = 'number_of_visits';

    /**
     *
     */
    const NUMBER_PHONES = 'number_of_phones';

    /**
     *
     */
    const NUMBER_DEALS = 'number_of_deals';

    /**
     *
     */
    const NUMBER_REFUNDED = 'number_of_refunded';

    /*
     *
     */
    protected $fillable = [
        'id_campaign',
        'number_of_visits',
        'number_of_phones',
        'number_of_deals',
        'number_of_refunded',
        'campaign_cost',
        'campaign_profit',
        'flag_changed',
        'product_id'
    ];

    /**
     * @var array
     */
    public static $types = [
        'number_of_visits',
        'number_of_phones',
        'number_of_deals',
        'number_of_refunded'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function advertisingCampaign()
    {
        return $this->belongsTo('App\Models\AdvertisingCampaign', 'id_campaign', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\CatalogProduct');
    }

    /**
     * @param Request $request
     * @param int $campaign
     * @param int $product_id
     * @param $type
     */
    public static function addNewCount(Request $request, $campaign = 0, $product_id = 0, $type)
    {
        if(Crawler::isCrawler())
            return true;

        if(!Session::has('token_company.' . $campaign) || !Session::has('product_visit.' . $product_id) ||
            $type != static::NUMBER_VISITS)
        {
            $campaignModel = AdvertisingCampaign::active()
                ->find($campaign);

            $campaign = $campaignModel ? $campaignModel->id : $campaign;
            // check incoming type
            if(in_array($type, static::$types)) {

                $ip = ip2long($request->getClientIp());
                $ipExists = VisitIp::where('ip', $ip)
                    ->where('campaign_id', $campaign)
                    ->first();

                if($ipExists){
                    if(!$ipExists->refunded) {
                        $ipExists->refunded = true;
                        $ipExists->save();
                    }

                    if($type == static::NUMBER_VISITS)
                        $type = static::NUMBER_REFUNDED;
                }else{
                    VisitIp::create(
                        [
                            'ip'            => $ip,
                            'campaign_id'   => $campaign
                        ]);
                }

                // get today date
                $now = Carbon::now()->startOfDay();

                // search in db for record for current day and given product -
                // we must have only one record per day for each product
                $todayItem = static::whereDay('created_at', '=',$now->format('d'))
                    ->where('id_campaign', $campaign)
                    ->where('product_id', $campaignModel ? $campaignModel->product_id : $product_id)
                    ->first();

                // if not found - create new
                if (!$todayItem) {
                    $todayItem = new self;
                    $todayItem->id_campaign = $campaign;
                    $todayItem->product_id = $campaignModel ? $campaignModel->product_id : $product_id;
                    $todayItem->{$type} = 1;
                }else{
                    $todayItem->{$type}++;
                }
                // update cost of campaign
                $todayItem->campaign_cost = $campaignModel ? $campaignModel->cost : 0;
                $todayItem->save();
            }

            Session::put('token_company.' . $campaign, $campaignModel ? $campaignModel->token : 0);
            Session::put('product_visit.' . $product_id, $product_id);

            if((int)$campaign != 0)
                Session::put('token_company.0', 0);
        }
    }

    /**
     * @param $type
     * @param int $campaign
     * @return int
     */
    public static function getCountByCampaign($type, $campaign = 0)
    {
        // check incoming type
        if(in_array($type, static::$types)) {

            return static::where('id_campaign', $campaign)
                ->sum($type);
        }

        return 0;
    }
}
