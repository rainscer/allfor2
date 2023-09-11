<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CallMe extends Model {

    /*
     *
     */
	protected $fillable = [
        'phone',
        'campaign_name',
        'call_time',
        'completed'
    ];

    /**
     * @param $value
     * @return string
     */

    public function getCallTimeAttribute($value)
    {
        if($value !== '0000-00-00 00:00:00') {

            return Carbon::parse($value)->format('d.m H:i');
        }else{

            return null;
        }
    }

}
