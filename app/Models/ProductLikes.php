<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProductLikes
 * @property integer                             id
 * @property integer                             user_id
 * @property integer                             product_id
 * @property \App\Models\CatalogProduct          product
 * @package App\Models
 */
class ProductLikes extends Model {


    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_id'
    ];

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
        return $query->with(['product' => function ($query) {
            $query->with(['image' => function ($query) {
                $query->orderBy('id', 'asc');
            }]);
        }])
            ->whereHas('product', function ($query) {
                $query->active();
            });
    }

    /**
     * @param $data
     */
    public static function addLike($data)
    {
        $like = self::where('user_id','=',Auth::user()->id)
            ->where('product_id','=',$data)
            ->first();
        if (!$like){
            $like = new self;
            $like->user_id = Auth::user()->id;
            $like->product_id = $data;
            $like->save();

            $product = CatalogProduct::find($data);
            $product->likes = $product->likes + 1;
            $product->save();
        }
    }

}
