<?php namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProductRequest;
use App\Models\CatalogCategory;
use App\Models\CatalogProduct;
use App\Models\CatalogProductImage;
use App\Models\RegisterProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{


    const ACTIVE_PRODUCTS = 'active';

    const PENDING_PRODUCTS = 'pending';

    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->module_name = 'CatalogProduct';
        $this->module_alias = 'catalogProducts';
    }


    /**
     * @return string
     */
    public function getModelClass()
    {
        return CatalogProduct::class;
    }

    /**
     * Display a listing of the resource.
     * @param null $type
     * @return Response
     */
    public function index($type = null)
    {
        $title = 'Products';
        $input = Input::all();

        $activeOnly = true;

        if (isset($input['show_inactive_filter']) && $input['show_inactive_filter'] === '1') {
            $activeOnly = false;
        }

        $products = CatalogProduct::getProductWithAllRel($activeOnly);

        if(isset($input['category2']) && isset($input['category1']) && isset($input['category0'])
            && $input['category2'] != '0'){

            $products = $this->filterByCategory($products, $input['category2']);

            $categoriesLevel1 = $this->getCategoriesList($input['category0']);

            $categoriesLevel2 = $this->getCategoriesList($input['category1']);

        }elseif(isset($input['category1']) && isset($input['category0']) && $input['category1'] != '0'){

            $products = $this->filterByCategory($products, $input['category1']);

            $categoriesLevel1 = $this->getCategoriesList($input['category0']);

            $categoriesLevel2 = $this->getCategoriesList($input['category1']);

        }elseif(isset($input['category0']) && $input['category0'] != '0'){

            $products = $this->filterByCategory($products, $input['category0']);

            $categoriesLevel1 = $this->getCategoriesList($input['category0']);
        }

        if(isset($input['filter-name']) && $input['filter-name'] != ''){

            $products = $this->filterByName($products, $input['filter-name']);
        }

        if(isset($input['sort']) && isset($input['direction'])){

            $products = $this->sortProductQuery($products, $input['sort'], $input['direction']);
        }

        if($type == self::ACTIVE_PRODUCTS){

            $products = $products->active();

            $title = 'Active products';
        }

        if($type == self::PENDING_PRODUCTS){

            $products = $products->notActive();

            $title = 'Pending products';
        }

        $products = $products->paginate(20)
            ->appends(Input::except('_token'));

        $categoriesLevel0 = CatalogCategory::withDepth()
            ->having('depth', '=', 0)
            ->lists(CatalogCategory::getNameColumn(), 'id');

        $categoriesLevel0 = [ 0 => trans('user.selectSubcategory')] + $categoriesLevel0;

        return view('admin.catalogProducts.index',
            compact(
                'products',
                'categoriesLevel0',
                'categoriesLevel1',
                'categoriesLevel2',
                'title',
                'activeOnly'
            )
            );
    }

    /**
     * @param $products
     * @param $category_id
     * @return mixed
     */
    private function filterByCategory($products, $category_id)
    {
        $categories = CatalogCategory::descendantsOf($category_id)
            ->lists('id');

        $categories[] = $category_id;

        return $products->whereHas('category', function ($query) use ($categories) {
            $query->whereIn('catalog_categories.id', $categories);
        });
    }

    /**
     * Direction type - ASC or DESC
     * @param $products
     * @param $sort
     * @param $direction
     * @return mixed
     */
    private function sortProductQuery($products, $sort, $direction)
    {
        $product = new CatalogProduct();

        if(in_array($sort, $product->getFillable())){

            $products = $products->orderBy($sort, $direction);
        }

        return $products;
    }
    /**
     * @param $category_id
     * @param bool $withZero
     * @return array
     */
    private function getCategoriesList($category_id, $withZero = true)
    {
         $categories = CatalogCategory::where('id' , $category_id)
            ->first();

        if($categories) {

            $categories = $categories->children
                ->lists(CatalogCategory::getNameColumn(), 'id');

            return $withZero ? ([ 0 => trans('user.selectSubcategory') ] + $categories) : $categories;
        }

        return [];
    }

    /**
     * @param $products
     * @param $name
     * @param bool $anyWord
     * @return mixed
     */
    private function filterByName($products, $name, $anyWord = false)
    {
        $search = $name;

        $search_first = trim($search);
        $search_array = explode(' ',$search_first);

        if($anyWord) {

            $products = $products->where(CatalogProduct::getNameColumn(), 'like', '%' . $search_array[0] . '%');

            array_forget($search_array, 0);
            foreach ($search_array as $item) {
                $products = $products->orWhere(CatalogProduct::getNameColumn(), 'like', '%' . $item . '%');
            }
        }

        return $products->where(function($query) use ($search_first) {
            $query->where(CatalogProduct::getUpiColumn(), $search_first)
                ->orWhere(CatalogProduct::getNameColumn(), 'like', '%' . $search_first . '%');
        });
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categoriesLevel0 = [ 0 => trans('user.selectSubcategory') ] + CatalogCategory::withDepth()
                ->having('depth', '=', 0)
                ->lists(CatalogCategory::getNameColumn(), 'id');

        return view('admin.catalogProducts.create', compact('categoriesLevel0'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function actionOnEntry()
    {
        if(Input::has('deactivate'))
        {
            $records = CatalogProduct::whereIn('id',Input::get('entries'))
                ->get();

            foreach ($records as $record) {
                $record->setDeactive();
            }
        }elseif(Input::has('activate'))
        {
            $records = CatalogProduct::whereIn('id',Input::get('entries'))
                ->get();

            foreach ($records as $record) {
                $record->setActive();
            }
        }elseif(Input::has('delete'))
        {
            $records = CatalogProduct::whereIn('id',Input::get('entries'))
                ->get();

            foreach ($records as $record) {
                $record->delete();
            }
        }

        return redirect()
            ->back();
    }

    /**
     * @param CatalogProduct $product_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(CatalogProduct $product_id)
    {
        $product = $product_id;

        $categoriesLevel0 = [ 0 => trans('user.selectSubcategory') ] + CatalogCategory::withDepth()
            ->having('depth', '=', 0)
            ->lists(CatalogCategory::getNameColumn(), 'id');

        $category = $product->category->first();

        if($category) {
            if (!$category->isRoot()) {

                $result = $category->getAncestors();

                if ($result->count() == 2) {
                    $categoriesLevel1 = $result->first()->children->lists(CatalogCategory::getNameColumn(), 'id');

                    $categoriesLevel2 = $result->last()->children->lists(CatalogCategory::getNameColumn(), 'id');

                    $product->catlvl0 = $result->first()->id;
                    $product->catlvl1 = $result->last()->id;
                    $product->catlvl2 = $category->id;
                } elseif($result->count() != 0) {
                    $categoriesLevel1 = [ 0 => trans('user.selectSubcategory') ] + $result->first()->children->lists(CatalogCategory::getNameColumn(), 'id');

                    $categoriesLevel2 = [ 0 => trans('user.selectSubcategory') ] + $category->children->lists(CatalogCategory::getNameColumn(), 'id');

                    $product->catlvl0 = $result->first()->id;
                    $product->catlvl1 = $category->id;
                }
            } else {
                $categoriesLevel1 = [ 0 => trans('user.selectSubcategory') ] + $category->children->lists(CatalogCategory::getNameColumn(), 'id');

                $product->catlvl0 = $category->id;
            }
        }


        return view('admin.catalogProducts.edit',
            compact(
                'categoriesLevel0',
                'categoriesLevel1',
                'categoriesLevel2',
                'product'
            )
        );
    }

    /**
     * @param CatalogProduct $product
     * @param ProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CatalogProduct $product, ProductRequest $request)
    {
        if (Input::has('activate')) {

            $product->setActive();

            CatalogProduct::clearSessionAndCache();
        } elseif(Input::has('deactivate')){

            $product->setDeactive();

            CatalogProduct::clearSessionAndCache();

        }elseif(Input::has('save')){

            $input = Input::all();

            $product->fill($input);
            $product->save();
            $active = $product->active;

            $activeProductCategory = $active ? ($active->active  ? true : false) : false ;

            $product->saveToCategory($input, $activeProductCategory);

            $product->saveImages($input);

        }elseif (Input::has('cancel')) {

            return redirect()->to('administrator/products');

        }

        return redirect()->to('administrator/products');
    }


    /**
     * @param ProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductRequest $request)
    {
        $input = Input::all();
        $product = new CatalogProduct();
        $product->fill($input);
        $product->save();

        $product->saveToCategory($input);

        $product->saveImages($input);

        return redirect()->to('administrator/products');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAll()
    {
        
//        $products = CatalogProduct::get();

//        foreach ($products as $product) {
//            $product->delete();
//        }

//        CatalogProduct::clearSessionAndCache();

        return redirect()->to('administrator/products');
    }

    /**
     * TEMP function
     * @return string
     */
    public function rebuildOldImages()
    {
        $count = 0;
        $deleted = 0;

        $images = CatalogProductImage::all();

        $this->rebuidImg($images, BaseImage::PATH_ORIGIN, $count, $deleted);

        $images = CatalogProductUsedImage::all();

        $this->rebuidImg($images, BaseImage::PATH_USED, $count, $deleted);

        $images = CatalogProductImageVersion::all();

        $this->rebuidImg($images, BaseImage::PATH_MODIFIED, $count, $deleted);

        return 'Changed = ' . $count . ' Deleted = ' . $deleted;
    }

    /**
     * @param $images
     * @param $path
     * @param $count
     * @param $deleted
     */
    private function rebuidImg($images, $path, &$count, &$deleted)
    {
        /*foreach ($images as $image) {

            $name = pathinfo($image->image_url, PATHINFO_BASENAME);
            $imagePath = $path . $image->owner_id;

            $image_dir = public_path($imagePath);

            if (!File::isDirectory($image_dir)) {
                File::makeDirectory($image_dir, $mode = 0777, true);
            }

            $res = resizeAndSaveImage(
                public_path() . $image->image_url,
                $image_dir . '/' . $name
            );

            if($res){
                $image->image_url = '/' . $imagePath . '/' . $name;
                $image->save();

                $count++;
            }else{
                $image->delete();
                $deleted++;
            }

        }*/

        // clear preview folders
        $images = $images->groupBy('owner_id');
        foreach ($images as $owner => $image) {
            $imagePath = $path . $owner;
            $image_dir = public_path($imagePath);

            File::cleanDirectory($image_dir . '/' . BaseImage::PATH_200);
            File::cleanDirectory($image_dir . '/' . BaseImage::PATH_800);
            $count++;
        }
    }



    /** Function for save uploaded files and returns array of its names
     * @param $type
     * @return array
     */
    public function uploadImage($type)
    {
        // Grab our files input
        $files = Input::file('files');
        // We will store our uploads in public/uploads/*
        if($type == 'upi'){
            $assetPath = 'uploads/upi/temp';
        }else{
            return array(
                'files' => []
            );
        }

        $uploadPath = public_path($assetPath);
        if (!File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true);
        }
        // We need an empty array for us to put the files back into
        $results = [];
        foreach ($files as $file) {
            //only availableMimeTypes
            if (in_array($file->getClientMimeType(), CatalogProduct::$availableMimeTypes, true)) {
                $name = $file->getClientOriginalName();
                $file->move($uploadPath, $name);
                $image_url = asset($assetPath . '/' . $name);
                if ($type === 'upi') {
                    $id = CatalogProductImage::saveImage($assetPath . '/' . $name);
                }
                $results[] = compact('image_url', 'id');
            }
        }

        // return our results in a files object
        return array(
            'files' => $results
        );
    }

    /**
     * @param $type
     * @param Request $request
     */
    public function deleteImage($type, Request $request)
    {
        if($type == 'upi') {
            $image = CatalogProductImage::where('id', $request->id)->first();
            if ($image) {

                if(File::exists(public_path($image->url)))
                    File::delete(public_path($image->url));

                $image->delete();
            }
        }
    }

    /**
     * @param CatalogCategory $category_id
     * @return int
     */
    public function assignAllToCategory(CatalogCategory $category_id)
    {
        $products = CatalogProduct::lists('id');

        DB::table('register_product_categories')->truncate();

        $i = 0;
        foreach ($products as $product) {
            RegisterProductCategory::create([
                'product_id'    => $product,
                'category_id'   => $category_id->id,
                'active'        => 1
            ]);

            $i++;
        }

        return $i;
    }

}