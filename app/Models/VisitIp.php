<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitIp extends Model {

    /**
     * @var array
     */
    protected $fillable = [
        'ip',
        'refunded',
        'campaign_id'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'refunded' => 'boolean'
    ];

}
