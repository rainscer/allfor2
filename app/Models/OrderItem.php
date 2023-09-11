<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Class OrderItem
 * @property integer                                    id
 * @property integer                                    order_id
 * @property integer                                    product_id
 * @property string                                     product_name
 * @property integer                                    product_quantity
 * @property float                                      product_price
 * @property integer                                    product_upi
 * @property string                                     product_sku
 * @property boolean                                    deletion_mark_user
 * @property \App\Models\Order                          product
 * @property \App\Models\CatalogProduct                 order
 * @package App\Models
 */
class OrderItem extends Model {

    protected $fillable = array(
        'order_id',
        'product_id',
        'product_name',
        'product_quantity',
        'product_price',
        'product_upi',
        'product_sku'
    );

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(
            'App\Models\Order',
            'order_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(
            'App\Models\CatalogProduct',
            'product_upi',
            'upi_id'
        );
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActiveProduct($query)
    {
        return $query->with('product')
            ->whereHas('product', function ($query) {
                $query->active();
            });
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActiveProductWithImage($query)
    {
        return $query->with(
            ['product' => function($query){
                $query->with(['image' => function ($query) {
                    $query->orderBy('id', 'asc');
                }]);
            }])->whereHas('product', function ($query) {
            $query->active();
        });
    }
}
