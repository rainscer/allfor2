<?php

namespace App\Models;
use Carbon\Carbon;


/**
 * Class AdvertisingCampaign
 * @property integer                                    id
 * @property string                                     name
 * @property string                                     description
 * @property float                                      cost
 * @property boolean                                    active
 * @property string                                     slug
 * @property string                                     token
 * @property integer                                    product_id
 * @property Carbon                                     start_date
 * @property Carbon                                     end_date
 * @property \App\Models\ItemAdvertisingCampaign        itemAdvertisingCampaign
 * @package App\Models
 */

class AdvertisingCampaign extends BaseAdminModel
{
    /*
     *
     */
    protected $fillable = [
        'name',
        'description',
        'cost',
        'active',
        'slug',
        'token',
        'product_id',
        'start_date',
        'end_date'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date'
    ];

    /*
     *
     */
    public static $rules = [
        'name'          => 'required|max:255',
        'slug'          => 'required',
        'token'         => 'required',
        'product_id'    => 'required|not_in:0',
        'startDate'     => 'required|date_format:"d/m/Y"',
        'endDate'       => 'required|date_format:"d/m/Y"',
    ];

    /**
     * @var array
     */
    public $casts = [
        'active' => 'boolean'
    ];


    /**
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        static::fixDate($attributes);

        return parent::update($attributes, $options);
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function create(array $attributes = [])
    {
        static::fixDate($attributes);
        $model = parent::create($attributes);

        return $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemAdvertisingCampaign()
    {
        return $this->hasMany('App\Models\ItemAdvertisingCampaign', 'id_campaign', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(
            'App\Models\CatalogProduct',
            'product_id'
        );
    }


    /**
     * @return string
     */
    public static function getNameColumn()
    {
        return 'name';
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * @param $attributes
     */
    private static function fixDate(&$attributes)
    {
        try {
            $attributes['start_date'] = Carbon::createFromFormat('d/m/Y', $attributes['startDate']);
        }
        catch(\Exception $e) {
            $attributes['start_date'] = Carbon::now();
        }

        try {
            $attributes['end_date'] = Carbon::createFromFormat('d/m/Y', $attributes['endDate']);
        }
        catch(\Exception $e) {
            $attributes['end_date'] = Carbon::now()->addDay();
        }
    }

}
