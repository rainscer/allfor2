<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 * @property string                             name
 * @property string                             type
 * @property string                             href
 * @property string                             content
 * @property integer                            sort
 * @property \App\Models\CatalogArticle         article
 * @package App\Models
 */
class Menu extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menu';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'content',
        'sort'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function article()
    {
        return $this->hasOne(
            'App\Models\CatalogArticle',
            'id',
            'content'
        );

    }

    /**
     * @return mixed
     */
    public static function show()
    {
        return self::with('article')
            ->orderBy('sort')
            ->get();
    }

}
