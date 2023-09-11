<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RegisterProductCategory
 * @property integer                             id
 * @property integer                             category_id
 * @property integer                             product_id
 * @property boolean                             active
 * @package App\Models
 */
class RegisterProductCategory extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'category_id',
        'product_id',
        'active'
    ];

    /**
     * @param $id
     * @return mixed
     */
    public function getAllProductIdCategory($id)
    {
        return self::where('category_id', '=', $id)
            ->where('active','=',1)
            ->orderBy('id')
            ->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCategoryId($id)
    {
        $category_id = self::where('product_id', '=', $id)
            ->first();

        return $category_id->category_id;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getActiveProduct($id)
    {
        $category_id = self::where('product_id', '=', $id)
            ->first();

        return $category_id->active;
    }

    /**
     * @return mixed
     */
    public function getActiveProducts()
    {
        return self::select('product_id')
            ->where('active', '=', 1)
            ->get();
    }

}
