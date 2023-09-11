<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cart
 * @property integer                                    product_id
 * @property integer                                    cart_id
 * @property integer                                    quantity
 * @property float                                      price
 * @property integer                                    upi_id
 * @property string                                     product_name_ru
 * @property string                                     product_image
 * @property Carbon                                     updated_at
 * @property Carbon                                     created_at
 * @property \App\Models\CatalogProduct                 product
 * @package App\Models
 */
class CartProduct extends Model {


    /**
     * @var array
     */
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
        'upi_id'
    ];

    public function cart()
    {
        return $this->belongsTo(
            'App\Models\Cart',
            'cart_id'
        );
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
     * @param $query
     * @return mixed
     */
    public function scopeActiveProduct($query)
    {
        return $query->with('product')
            ->whereHas(
                'product',
                function ($query) {
                    $query->active();
                }
            );
    }

    /**
     * @param $cart_id
     * @param CatalogProduct $product
     * @param int $quantity
     * @return int
     */
    public static function saveOrUpdateProductInCart($cart_id, CatalogProduct $product, $quantity = 1)
    {
        $cart_product = self::firstOrNew(['cart_id' => $cart_id, 'product_id' => $product->id]);
        $cart_product->cart_id = $cart_id;
        $cart_product->product_id = $product->id;
        if ($cart_product->exists) {
            $cart_product->quantity = $cart_product->quantity + $quantity;
        } else {
            $cart_product->quantity = $quantity;
        }
        $cart_product->price = $product->price;
        $cart_product->upi_id = $product->upi_id;
        $cart_product->save();

        return $cart_product->quantity;
    }

}
