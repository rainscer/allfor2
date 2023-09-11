<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CatalogProductImage
 * @property integer owner_id
 * @property string  image_url
 * @property int     sort
 * @property int     $id
 * @package App\Models
 */
class CatalogProductImage extends Model
{


    const PATH_ORIGIN = 'uploads/upi/images/';


    const PATH_200 = '200x200';

    /*
     *
     */
    const PATH_800 = '800x600';

    /**
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'image_url',
        'sort'
    ];

    /**
     * @param $name
     * @return mixed
     */
    public static function saveImage($name)
    {
        //save images
        return self::firstOrCreate(['image_url' => $name])->id;
    }
}
