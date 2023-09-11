<?php namespace App\Models;

use Carbon;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

/**
 * Class CatalogArticle
 * @property string                                     title_ru
 * @property string                                     text_ru
 * @property string                                     title_en
 * @property string                                     text_en
 * @property string                                     title_ua
 * @property string                                     text_ua
 * @property string                                     slug
 * @property Carbon                                     updated_at
 * @property Carbon                                     created_at
 * @package App\Models
 */
class CatalogArticle extends Model implements SluggableInterface
{
    use SluggableTrait;

    /**
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'title_ru',
        'save_to'    => 'slug',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'title_ua',
        'text_ua',
        'title_ru',
        'text_ru',
        'title_en',
        'text_en',
        'slug'
    ];

}
