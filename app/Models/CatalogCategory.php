<?php namespace App\Models;

use Carbon;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Kalnoy\Nestedset\Node;

/**
 * Class CatalogCategory
 * @property string                                     name_ru
 * @property string                                     description_ru
 * @property string                                     name_en
 * @property string                                     description_en
 * @property string                                     name_ua
 * @property string                                     description_ua
 * @property string                                     slug
 * @property Carbon                                     updated_at
 * @property Carbon                                     created_at
 * @property integer                                    level
 * @property integer                                    left_key
 * @property integer                                    right_key
 * @property string                                     image
 * @property \App\Models\CatalogProduct                 products
 * @package App\Models
 */
class CatalogCategory extends Node implements SluggableInterface
{
    use SluggableTrait;

    /**
     * The name of "lft" column.
     *
     * @var string
     */
    const LFT = 'left_key';

    /**
     * The name of "rgt" column.
     *
     * @var string
     */
    const RGT = 'right_key';

    /**
     * The name of "parent id" column.
     *
     * @var string
     */
    const PARENT_ID = 'parent_id';

    /**
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'name_en',
        'save_to'    => 'slug',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name_ru',
        'name_ua',
        'name_en',
        'description_ru',
        'description_ua',
        'description_en',
        'slug',
        'level',
        'left_key',
        'right_key',
        'image',
        'parent_id'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('App\Models\CatalogProduct',
            'register_product_categories',
            'category_id',
            'product_id');
    }

    /**
     * @param array $attributes
     * @param Node|null $parent
     * @return static
     */
    public static function create(array $attributes = array(), Node $parent = null)
    {
        $slug = isset($attributes['slug']) ? $attributes['slug'] : null;
        $attributes['slug'] = static::generateSlug($slug);

        return parent::create($attributes, $parent);
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $slug = isset($options['slug']) ? $options['slug'] : null;
        $options['slug'] = static::generateSlug($slug, $this->id);

        return parent::save($options);
    }

    /**
     * @param array $attributes
     * @return bool|int
     */
    public function update(array $attributes = array())
    {
        $slug = isset($attributes['slug']) ? $attributes['slug'] : null;
        $attributes['slug'] = static::generateSlug($slug, $this->id);

        return parent::update($attributes);
    }


    /**
     * @return string
     */
    public static function generateSlug($slug, $id = null)
    {
        $slug = str_slug($slug);

        if($id) {
            $exists = $productCount = self::where('slug', $slug)
                ->whereNotIn('id', [$id])
                ->count();
        }else{
            $exists = $productCount = self::where('slug', $slug)
                ->count();
        }

        if($exists) {
            $productCount = 1;

            while ($productCount > 0) {
                $slug = $slug . '-' . rand(5, 100);

                if($id) {
                    $productCount = self::where('slug', $slug)
                        ->whereNotIn('id', [$id])
                        ->count();
                }else{
                    $productCount = self::where('slug', $slug)
                        ->count();
                }
            }
        }

        return $slug;
    }

    /**
     * @return string
     */
    public function getLftName()
    {
        return 'left_key';
    }

    /**
     * @return string
     */
    public function getRgtName()
    {
        return 'right_key';
    }

    /**
     * @return string
     */
    public function getParentIdName()
    {
        return 'parent_id';
    }

    // Specify parent id attribute mutator
    /**
     * @param $value
     */
    public function setParentAttribute($value)
    {
        $this->setParentIdAttribute($value);
    }


    /**
     * @param $level
     * @param $left_key
     * @param $right_key
     * @return mixed
     */
    public static function getParentSlug($level,$left_key,$right_key)
    {

        return self::where('level', '=', ($level - 1))
                    ->where('left_key', '<=', $left_key)
                    ->where('right_key', '>=', $right_key)
                    ->first();
    }


    /**
     * @return array
     */
    public static function getTree()
    {
        $name = 'name_en';

        $catalog = self::withDepth()
            ->defaultOrder()
            ->get()
            ->linkNodes()
            ->toTree();

        $array = [];
        foreach ($catalog as $category) {
            if ($category->children) {
                $children = [];
                foreach ($category->children as $category2) {
                    if ($category2->children) {
                        $child = [];
                        foreach ($category2->children as $category3) {
                            if ($category3->children) {
                                $chil = [];
                                foreach ($category3->children as $category4) {
                                    $chil[] = [
                                        'label' => $category4->{$name},
                                        'id'    => $category4->id
                                    ];
                                }
                                $child[] = [
                                    'label'    => $category3->{$name},
                                    'id'       => $category3->id,
                                    'children' => $chil
                                ];

                            } else {
                                $child[] = [
                                    'label' => $category3->{$name},
                                    'id'    => $category3->id
                                ];
                            }
                        }
                        $children[] = [
                            'label'    => $category2->{$name},
                            'id'       => $category2->id,
                            'children' => $child
                        ];
                    } else {
                        $children[] = [
                            'label' => $category2->{$name},
                            'id'    => $category2->id
                        ];
                    }
                }
                $array[] = [
                    'label'    => $category->{$name},
                    'id'       => $category->id,
                    'children' => $children
                ];
            } else {
                $array[] = [
                    'label' => $category->{$name},
                    'id'    => $category->id
                ];
            }
        }

        return $array;
    }

    /**
     * @return string
     */
    public static function getNameColumn()
    {
        return 'name_en';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->{static::getNameColumn()};
    }
}
