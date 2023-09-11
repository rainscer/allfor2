<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Class Settings
 * @property integer                            id
 * @property string                             key_name
 * @property string                             description
 * @property string                             value
 * @package App\Models
 */
class Settings extends Model {

    /**
     * @var array
     */
    protected $fillable = [
        'key_name',
        'description',
        'value'
    ];

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function checkSetting($key, $value)
    {
        return self::where('key_name',$key)
            ->where('value',$value)
            ->first();
    }

}
