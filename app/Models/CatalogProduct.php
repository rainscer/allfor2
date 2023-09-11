<?php namespace App\Models;

use Cache;
use Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Exception;
use File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * Class CatalogProduct
 * @property integer                                    id
 * @property string                                     name_ru
 * @property string                                     description_ru
 * @property string                                     name_en
 * @property string                                     description_en
 * @property string                                     name_ua
 * @property string                                     description_ua
 * @property string                                     slug
 * @property string                                     sku
 * @property Carbon                                     updated_at
 * @property Carbon                                     created_at
 * @property integer                                    upi_id
 * @property integer                                    new
 * @property integer                                    likes
 * @property float                                      weight
 * @property float                                      price
 * @property integer                                    views
 * @property integer                                    real_views
 * @property boolean                                    delivery_type
 * @property \App\Models\CatalogProductImage            image
 * @property \App\Models\ProductAttribute               attribute
 * @property \App\Models\CatalogCategory                category
 * @property \App\Models\Review                         review
 * @property \App\Models\ProductLikes                   like
 * @property integer                                    sold
 * @package App\Models
 */
class CatalogProduct extends Model implements SluggableInterface
{
    use SluggableTrait;

    /*
   *
   */
    public static $availableMimeTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png'
    ];

    /*
     *
     */
    const CATEGORY_18_SLUG = '18';

    /*
     *
    */
    const SETTING_NAME_BANNER = 'banner';

    /*
     *
     */
    const REVIEW_ASSET_PATH = 'uploads/review/';
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
        'likes',
        'sold',
        'views',
        'real_views',
        'new',
        'price',
        'weight',
        'upi_id',
        'sku',
        'delivery_type'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'delivery_type' => 'boolean'
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->with('active')
            ->whereHas('active', function ($query) {
            $query->where('active', '=', '1');
        });
    }


    /**
     * @param $query
     * @return mixed
     */
    public function scopeNotActive($query)
    {
        return $query->with('active')
            ->whereHas('active', function ($query) {
                $query->where('active', '=', '0');
            });
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function image()
    {
        return $this->hasMany(
            'App\Models\CatalogProductImage',
            'owner_id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function waitings()
    {
        return $this->hasMany('App\Models\WaitingProductUser','product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attribute()
    {
        return $this->hasMany(
            'App\Models\ProductAttribute',
            'upi_id',
            'upi_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function category()
    {
        return $this->belongsToMany(
            'App\Models\CatalogCategory',
            'register_product_categories',
            'product_id',
            'category_id'
        );

    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function active()
    {
        return $this->hasOne(
            'App\Models\RegisterProductCategory',
            'product_id'
        );

    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function review()
    {
        return $this->hasMany(
            'App\Models\Review',
            'product_id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function like()
    {
        return $this->hasMany(
            'App\Models\ProductLikes',
            'product_id',
            'id'
        );
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getPriceAttribute($value)
    {
        return round($value, 0);

        $curency = 'curency_'.App::getLocale();
        $curency = app('Setting')->getSettingValue($curency, 1);
        $weight = $this->attributes['weight'];
        $value > 3 ? $gp = 1 : $gp = 0.50;

        return round(($value + ($weight * 0.0125) + $gp) * $curency, 0);
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
    public static function getUpiColumn()
    {
        return 'upi_id';
    }

    /**
     * @param $slug
     * @param $productSlug
     * @return mixed
     */
    public static function getCategoryDopProducts($slug,$productSlug)
    {
        $referenceIds = CatalogProduct::with('category')
                ->whereHas('category', function ($query) use ($slug) {
                    $query->where('slug','=', $slug);
                })
                ->lists('id');

        shuffle($referenceIds);
        $referenceIds = array_slice( $referenceIds, 0, 10, true );

        return self::with('category')
                ->whereHas('category', function ($query) use ($slug) {
                    $query->where('slug','=', $slug);
                })
                ->where('slug','!=',$productSlug)
                ->whereIn('id',$referenceIds)
                ->with(['image' => function ($query) {
                    $query->orderBy('id', 'asc');
                }])
                ->active()
                ->select('slug','name_en','price','id','upi_id','weight')
                ->take(3)
                ->get();
    }

    /**
     * @return mixed
     */
    public static function getRandomDopProducts()
    {
        return self::with('category')
            ->with(['image' => function ($query) {
                $query->orderBy('id', 'asc');
            }])
            ->with(['attribute' => function ($query) {
                $query->with(['attribute_name' => function ($query) {
                    $query->with('products_attributes');
                }]);
            }])
            ->active()
            ->orderByRaw('RAND()')
            ->take(10)
            ->get();
    }

    /**
     * @param $search
     * @return mixed
     */
    public static function getSearchProduct($search)
    {
        //filter search words
        $pattern = array (
            "'<{cke_protected}%3Cscript%5B%5E%3E%5D*%3F%3E.*%3F%3C%2Fscript%3E>'si",
            "'<[/!]*?[^<>]*?>'si",
            "'([rn])[s]+'",
            "'&(quot|#34);'i",
            "'&(apos|#039);'i",
            "'&(amp|#38);'i",
            "'&(lt|#60);'i",
            "'&(gt|#62);'i",
            "'&(nbsp|#160);'i",
            "'&(iexcl|#161);'i",
            "'&(cent|#162);'i",
            "'&(pound|#163);'i",
            "'&(copy|#169);'i",
            "|[\s]+|i"
        );

        $replace = array (
            "",
            "",
            "1",
            "",
            "",
            "&",
            "<",
            ">",
            " ",
            chr(161),
            chr(162),
            chr(163),
            chr(169),
            " "
        );

        $search = htmlspecialchars($search,ENT_QUOTES);
        $search = preg_replace($pattern, $replace, $search);
        $search_first = trim($search);
        $search_array = explode(' ',$search_first);

        $search_third_temp = [];
        $search_second_temp = [];
        foreach ($search_array as $item){
            $search_second_temp[] = '+' . $item . '*' ;
            $search_third_temp[] = $item .'*' ;

        }

        $search_second = implode(' ', $search_second_temp);
        $search_third = implode(' ', $search_third_temp);
        $search_final = '>>"' . $search_first . '" >('. $search_second .') <<(' . $search_third .')';

        $name_columns = 'name_' . App::getLocale() . ', meta_keywords_' . App::getLocale();
        $desc_columns = 'description_' . App::getLocale();

        $search_raw = "MATCH (" . $name_columns . ") AGAINST ('".$search_final."' IN BOOLEAN MODE)";
        $search_raw_desc = "MATCH (" . $desc_columns . ") AGAINST ('(".$search_second.")' IN BOOLEAN MODE)";

        return self::select(
            'catalog_products.*',
            DB::raw("MATCH (" . $name_columns . ") AGAINST ('".$search_final."' IN BOOLEAN MODE) AS rel"),
            DB::raw("MATCH (" . $desc_columns . ") AGAINST ('".$search_second."' IN BOOLEAN MODE) AS rel_desc")
        )
            ->where(function($query) use ($search_raw, $search_raw_desc) {
                $query->whereRaw($search_raw)
                    ->orWhereRaw($search_raw_desc);
            })
            ->with(['attribute' => function ($query) {
                $query->with(['attribute_name' => function ($query) {
                    $query->with('products_attributes');
                }]);
            }])
            ->with(['image' => function ($query) {
                $query->orderBy('id', 'asc');
            }])
            ->with('category')
            ->with('active');
    }

    /**
     * @return array
     */
    public static function getShuffleProductIds()
    {
        $referenceIds = Cache::remember('catalog_all', config('app.cache_time'), function() {

            return self::whereHas('active', function ($query) {
                    $query->where('active', '=', '1');
                })->lists('hidden','id');
        });

        return self::shuffleProducts($referenceIds);
    }

    /**
     * @param $referenceIds
     * @return array
     */
    public static function shuffleProducts($referenceIds)
    {
        // get hidden elements for putting them in end of array
        $arrayHidden = array_keys(array_where($referenceIds, function ($key, $value) {
            return $value == true;
        }));

        $arrayNormal = array_keys(array_where($referenceIds, function ($key, $value) {
            return $value == false;
        }));

        //shuffle array of not-hidden elements
        shuffle($arrayNormal);
        // merge arrays for adding hidden elements to end
        $referenceIds = array_merge($arrayNormal, $arrayHidden);

        // put it to session for ajax load
        Session::put('product_ids', $referenceIds);

        return $referenceIds;
    }

    /**
     * @param $referenceIds
     * @param $page
     * @param null $catalog_children
     * @return mixed
     */
    public static function getProducts($referenceIds, $page, $catalog_children = null)
    {
        // get current page
        $page = !$page ? 1 : $page;
        // get current ids by current page
        $count = ($page - 1) * config('app.count_per_page');
        $referenceIds = array_slice( $referenceIds, $count, config('app.count_per_page'), true );
        $referenceIdsStr = implode(',', $referenceIds);
        if($referenceIdsStr != '') {

            // get from session upi that don't must be (that has attributes and once been displayed)
            $block_upi = Session::get('product_upi_block',[]);
            $products_sort = new Collection();

            // get products by found ids
            $products = self::with(['image' => function ($query) {
                $query->orderBy('id', 'asc');
            }])
                ->whereIn('id', $referenceIds)
                ->with(['attribute' => function ($query) {
                    $query->with(['attribute_name' => function ($query) {
                        $query->with('products_attributes');
                    }]);
                }])
                ->orderByRaw(DB::raw('FIELD(id, ' . $referenceIdsStr . ')'))
                ->active();

            if($catalog_children){
                $products = $products->with('category')
                    ->whereHas('category', function ($query) use ($catalog_children) {
                        $query->whereIn('catalog_categories.id', $catalog_children);
                    });
            }

            // Temporary test
            try{
                $products = $products->get();
            }catch (Exception $e){
                SystemError::write('GET Products cat','ID = ' . $referenceIdsStr);
                return null;
            }

            foreach($products as $key => $product) {
                if(!in_array($product->upi_id, $block_upi)) {
                    $products_sort[$key] = $product;
                    if (count($product->attribute)) {
                        foreach ($product->attribute as $attribute) {
                            if (count($attribute->attribute_name->products_attributes)) {
                                $block_upi = array_merge(
                                    $block_upi,
                                    collect($attribute->attribute_name->products_attributes)->lists('upi_id')
                                );
                            }
                        }
                    }
                }
            }
            // put to session found upi_id - this needs only for ajax load
            Session::put('product_upi_block',$block_upi);
            return $products_sort;
        }
    }

    /**
     * @param bool $active
     * @return mixed
     */
    public static function getProductWithAllRel($active = true)
    {
        $products = self::with(['image' => function ($query) {
                $query->orderBy('id', 'asc');
            }])
            ->with('category')
            ->with('active')
            ->with(['attribute' => function ($query) {
                $query->with(['attribute_name' => function ($query) {
                    $query->with('products_attributes');
                }]);
            }])
            ->with(['review' => function ($query) {
                $query->active()
                    ->typeReview();
            }]);

        return $active ? $products->active() : $products;
    }

    /**
     *
     */
    public static function clearSessionAndCache()
    {
        Session::forget('product_ids');
    }


    /**
     * @param $input
     * @param bool $active
     */
    public function saveToCategory($input, $active = false )
    {
        RegisterProductCategory::where('product_id', $this->id)
            ->delete();

        if(isset($input['category2']) && isset($input['category1']) && $input['category2'] != '0'){
            $category = CatalogCategory::find($input['category2']);
            $category_parent = CatalogCategory::find($input['category1']);

            if($category && $category_parent &&  $category->isDescendantOf($category_parent)){

                RegisterProductCategory::create(
                    [
                        'category_id'   => $category->id,
                        'product_id'    => $this->id,
                        'active'        => $active
                    ]
                );
            }
        }elseif(isset($input['category1']) && isset($input['category0']) && $input['category1'] != '0'){
            $category = CatalogCategory::find($input['category1']);
            $category_parent = CatalogCategory::find($input['category0']);
            if($category && $category_parent &&  $category->isDescendantOf($category_parent)){

                RegisterProductCategory::create(
                    [
                        'category_id'   => $category->id,
                        'product_id'    => $this->id,
                        'active'        => $active
                    ]
                );
            }
        }elseif(isset($input['category0']) && $input['category0'] != '0'){
            $category = CatalogCategory::find($input['category0']);

            if($category){
                RegisterProductCategory::create(
                    [
                        'category_id'   => $category->id,
                        'product_id'    => $this->id,
                        'active'        => $active
                    ]
                );
            }
        }
    }

    /**
     * @return int
     */
    public function setActive()
    {
        return $this->active()
            ->update([
                'active' => true
            ]);
    }

    /**
     * @return int
     */
    public function setDeactive()
    {
        return $this->active()
            ->update([
                'active' => false
            ]);
    }


    /**
     * Getting delivery cost by given array of ids and quantity
     * by multiplication weight and quantity
     * @param $product_array
     * @return number
     */
    public static function getDeliveryCost($product_array)
    {
        $products = self::whereIn('id',array_keys($product_array))
            ->active()
            ->lists('weight', 'id');

        $total = 0;
        foreach($products as $key => $product){
            $total += isset($product_array[$key]) ? $product_array[$key] * $product : 0;
        }

        $curency = 'curency_'.App::getLocale();
        $curency = app('Setting')->getSettingValue($curency, 1);

        return round(($total * 0.015 * $curency),0);
    }

    /**
     * Getting delivery cost by given array of ids and quantity
     * by multiplication weight and quantity
     * @param $product_array
     * @param int $countKg
     * @return number
     */
    public static function getWeightProductsPerkg($product_array, $countKg = 1)
    {
        $products = self::whereIn('id',array_keys($product_array))
            ->active()
            ->lists('weight', 'id');

        $total = 0;
        foreach($products as $key => $product){
            $total += isset($product_array[$key]) ? $product_array[$key] * $product : 0;
        }

        return ceil(($total/1000) / $countKg);
    }

    /**
     * @param $product_array
     * @return number
     */
    public static function checkPrices($product_array)
    {
        if($product_array) {
            $products = self::whereIn('id', array_keys($product_array))
                ->active()
                ->select('weight','price','id')
                ->get();

            $products = $products->lists('price', 'id');

            $res = [];
            foreach ($product_array as $key => $item) {
                !isset($products[$key]) ? : $item['price'] = $products[$key];
                $res[$key] = $item;
            }

            return $res;
        }
        return null;
    }

    /**
     * @param $image_review
     * @return array
     */
    public static function saveReviewImages($image_review)
    {
        $assetPath = CatalogProduct::REVIEW_ASSET_PATH;
        $moved_images = [];
        foreach($image_review as $image){
            $path = public_path($image);
            $file_name = pathinfo($path, PATHINFO_FILENAME);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $target = public_path($assetPath . $file_name . '.' . $extension);
            File::move($path, $target);
            $moved_images[] = $file_name . '.' . $extension;
        }


        return $moved_images;
    }

    /**
     * @param string $type
     * @return string
     */
    public function getMainImage($type = null)
    {
        return $this->image->count() ? image_asset($this->image->sortBy('sort')->first()->image_url, $type) : asset('images/no-image.jpg');
    }

    /**
     * @return int
     */
    public function getUpi()
    {
        return $this->upi_id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name_en;
    }


    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if (!$this->id) {
            $this->upi_id = static::getNextUpiId();
        }

        if (!$this->slug) {
            $this->slug = $this->generateSlug();
        }

        if (!$this->checkSlugUnique()) {
            $this->slug = $this->generateSlug();
        }

        if (empty($this->name_en) && !empty($this->name_ru)) {
            $this->name_en = $this->name_ru;
        }

        if (empty($this->description_en) && !empty($this->description_ru)) {
            $this->description_en = $this->description_ru;
        }

        return parent::save($options);
    }

    /**
     * @return bool|null
     */
    public function delete()
    {
        $this->image()->delete();
        $this->active()->delete();

        return parent::delete();
    }

    /**
     * @return bool
     */
    public function checkSlugUnique()
    {
        if($this->id){
            $exists = self::where('id', '<>', $this->id)
                ->where('slug', $this->slug)
                ->first();
        }else {
            $exists = self::where('slug', $this->slug)
                ->first();
        }

        return $exists ? false : true;
    }

    /**
     * @return string
     */
    public function generateSlug()
    {
        if(!$this->slug){
            return str_slug($this->{static::getNameColumn()});
        }

        $productCount = 1;

        while($productCount > 0){
            $slug_generated = $this->slug . '-' . rand(5, 100);

            $productCount = self::where('slug', $slug_generated)
                ->count();
        }

        return $slug_generated;
    }

    /**
     * @return mixed
     */
    public static function getNextUpiId()
    {
        return (self::max('upi_id')) + 1;
    }

    /**
     * @param $input
     */
    public function saveImages($input)
    {
        if(isset($input['images'])){

            $images = CatalogProductImage::whereIn('id', $input['images'])
                ->get();

            $i = 0;

            foreach($input['images'] as $item){

                $image = $images->first(function ($key, $value) use($item){
                    return  $value->id == $item;
                });

                $image->sort = $i;

                // check if this image in temp folder
                $findme   = 'temp';
                $pos = strpos($image->image_url, $findme);
                if ($pos !== false) {
                    // if not - move it to folder of
                    $product_dir_name = CatalogProductImage::PATH_ORIGIN . $this->id;
                    $advert_directory_200 = public_path($product_dir_name . '/' . CatalogProductImage::PATH_200);
                    $advert_directory_800 = public_path($product_dir_name . '/' . CatalogProductImage::PATH_800);
                    $advert_directory_origin = public_path($product_dir_name);

                    if (!File::isDirectory($advert_directory_200)) {
                        File::makeDirectory($advert_directory_200, $mode = 0777, true);
                    }
                    if (!File::isDirectory($advert_directory_800)) {
                        File::makeDirectory($advert_directory_800, $mode = 0777, true);
                    }

                    $new_name = Str::random(10) . '.' . File::extension($image->image_url);

                    resizeAndSaveImage($image->image_url, $advert_directory_200 . '/' . $new_name, true);
                    resizeAndSaveImage($image->image_url, $advert_directory_800 . '/' . $new_name, true, 600, 600);
                    resizeAndSaveImage($image->image_url, $advert_directory_origin . '/' . $new_name);

                    $image->image_url = '/' . $product_dir_name . '/' . $new_name;
                }
                $image->owner_id = $this->id;

                $image->save();

                $i++;
            }

            $this->image()
                ->whereNotIn('id', $input['images'])
                ->delete();
        }
    }

    /**
     * @return bool
     */
    public function isDeliveryType48Hours()
    {
        return (bool)$this->delivery_type;
    }

    /**
     * @return bool
     */
    public function isDeliveryType10Days()
    {
        return !(bool)$this->delivery_type;
    }

    /**
     * @param $delivery_type
     * @return string
     */
    public static function staticCheckDeliveryType($delivery_type)
    {
        if ($delivery_type) {
            return trans('cart.48hours');
        }

        return trans('cart.10days');
    }

    /**
     * @return string
     */
    public function checkDeliveryType()
    {
        return self::staticCheckDeliveryType($this->delivery_type);
    }

    public function getDescription()
    {
        $description = $this->{"description_" . App::getLocale()};

        foreach ($this->image as $key => $image) {
            // image_asset($image->image_url,'lg')

            $imageTag = '<img class="full-product-images" src="' .
                image_asset($image->image_url, 'sm') .
                '" alt="' . $this->{"name_" . App::getLocale()} .
                '">';
            $description = str_replace('[IMAGE_' . ($key + 1) . ']', $imageTag, $description);
        }

        return $description;
    }
}
