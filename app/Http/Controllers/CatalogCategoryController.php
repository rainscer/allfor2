<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Settings;
use App\Models\ProductLog;
use Illuminate\Http\Request;
use App\Models\CatalogProduct;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class CatalogController
 * @package App\Http\Controllers
 */
class CatalogCategoryController extends Controller
{

    /*
     *
     */
    const SETTING_NAME = 'search_log';

    /**
     * @param Request $request
     * @return Application|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $referenceIds = [];
        // get left menu
        $menu = Menu::show();
        // if is for ajax upload products we get array of product ids
        // this is an array of shuffle ids
        if ($request->ajax()) {
            if (Session::has('product_ids')) {
                $referenceIds = Session::get('product_ids');
            } else {
                abort(404);
            }
        } else {
            // forget duplicate upi_id (that has attributes)
            Session::forget('product_upi_block');
            // get product ids
            $referenceIds = CatalogProduct::getShuffleProductIds();
        }

        // get count of pages
        $productsCount = ceil(count($referenceIds) / config('app.count_per_page'));
        $products = CatalogProduct::getProducts($referenceIds, $request->page);

        // if this is for ajax upload products return ajax view
        if ($request->ajax()) {
            return view('catalog.list', compact('products'));
        }

        // check for banner on home page
        $banner = (bool)app('Setting')->checkSetting(CatalogProduct::SETTING_NAME_BANNER, true);

        return view(
            'catalog.index',
            compact(
                'products',
                'productsCount',
                'banner',
                'menu'
            )
        );
    }

    /**
     * @param Request $request
     * @return Response|void
     */
    public function searchProductsAjax(Request $request)
    {
        if ($request->has('search')) {
            $local = 'name_' . App::getLocale();

            // find products
            $products = CatalogProduct::getSearchProduct($request->get('search'));

            if ($request->has('category_slug') && ($request->get('category_slug') !== '')) {
                // get children catalogs
                $catalogChildren = App::make('Catalog')
                    ->getAllChildren($request->get('category_slug'));

                $products = $products->whereHas('category', function ($query) use ($catalogChildren) {
                    $query->whereIn('catalog_categories.id', $catalogChildren);
                });
            }

            $products = $products->active()
                ->lists($local, 'id');

            return response()->json([
                'options' => $products
            ]);
        }
    }

    /** For search products return found items
     * @param Request $request
     * @return Response
     */
    public function searchProducts(Request $request)
    {
        // if request has category slug - this means that search is in category so we need data for left menu
        if ($request->has('category_slug') && ($request->get('category_slug') != '')) {
            $categorySlug = $request->get('category_slug');
            // get children catalogs
            $catalogChildren = App::make('Catalog')
                ->getAllChildren($categorySlug);

            // get left menu with catalogs
            $catalog_sub_menu = App::make('Catalog')
                ->getSubMenu($categorySlug);

            $catalog_active = App::make('Catalog')
                ->getCategoryBySlug($categorySlug);
        }

        // get product by id in search input (when user choose product in list of founds)
        if ($request->has('product_id') && $request->has('toggle')) {
            // find product by id
            $product = CatalogProduct::getProductWithAllRel();
            $product = $product->where('id', $request->get('product_id'))
                ->first();

            // log the search words
            if (Settings::checkSetting(self::SETTING_NAME, true)) {
                $local = 'name_' . App::getLocale();
                ProductLog::create([
                    'checked' => true,
                    'words'   => $product->$local
                ]);
            }

            // make collection and push our product there
            $products = new Collection();
            $products->push($product);

            $style = $request->get('toggle');

            return response()->json(
                [
                    'response' => view(
                        'catalog.style_' . $style,
                        compact(
                            'products',
                            'catalog_sub_menu',
                            'catalog_active'
                        )
                    )->render()
                ]
            );
        } elseif ($request->has('search') && $request->has('toggle')) {
            $search = trim($request->get('search'));
            // if search word is number and its lenght is 5 - search it like upi_id
            if (/*preg_match('/^[0-9]{5}/', $search) && strlen($search) == 5*/ is_numeric($search)) {
                // find product by id
                $product = CatalogProduct::getProductWithAllRel();
                $products = $product->where('upi_id', 'like', '%' . $search . '%')
                    ->get();
                /*if($product) {
                    // make collection and push our product there
                    $products = new Collection();
                    $products->push($product);
                }*/
            } else {
                // find products by name and description
                $products = CatalogProduct::getSearchProduct($search);

                // if this search is in category we must return right left menu and make search in this category
                if (isset($catalogChildren)) {
                    $products = $products->whereHas('category', function ($query) use ($catalogChildren) {
                        $query->whereIn('catalog_categories.id', $catalogChildren);
                    });
                }

                $products = collect(
                    $products->with([
                        'review' => function ($query) {
                            $query->active()
                                ->typeReview();
                        }
                    ])
                        ->active()
                        ->get()
                )->sortByDesc('rel');

                // filter where we have matches in description
                $products_with_desc_match = $products->filter(function ($item) {
                    if ($item->rel_desc == 1 && $item->rel < 1) {
                        return $item;
                    }
                });

                // filter where we have all matches in name or keywords by relevation
                $products_with_all_match = $products->filter(function ($item) {
                    return $item->rel >= 1;
                });

                // filter if there is 100% match of name
                $local = 'name_' . App::getLocale();
                $products_top_match = $products->filter(function ($item) use ($local, $search) {
                    return strcasecmp(trim($item->$local), $search) == 0;
                });

                // if found 100% match - put this to variable $products
                if (count($products_top_match) > 0) {
                    $products = $products_top_match;
                    // else if we found match of all words - put this. In another way we have our collection without changes
                } elseif (count($products_with_all_match) > 0) {
                    $products = $products_with_all_match;
                    $products = $products->merge($products_with_desc_match);
                }

                if ($products->count()) {
                    $referenceIds = CatalogProduct::getShuffleProductIds();
                    $products = $products->merge(CatalogProduct::getProducts($referenceIds, $request->page));
                }
                // log search word
                if (Settings::checkSetting(self::SETTING_NAME, true)) {
                    ProductLog::create([
                        'words' => $search
                    ]);
                }
            }

            $style = $request->get('toggle');

            return response()->json(
                [
                    'response' => view(
                        'catalog.style_' . $style,
                        compact(
                            'products',
                            'catalog_sub_menu',
                            'catalog_active'
                        )
                    )->render()
                ]
            );
        } else {
            // if there are no search word or it's empty - reload page
            return response()->json(
                [
                    'action' => 'reload_page'
                ]
            );
        }
    }

    /**
     * @param         $categorySlug
     * @param Request $request
     * @return Application|\Illuminate\View\View
     */
    public function show($categorySlug, Request $request)
    {
        // get children catalogs
        $catalogChildren = App::make('Catalog')
            ->getAllChildren($categorySlug);

        if (! $catalogChildren) {
            abort(404);
        }

        // if this is ajax load product take products
        if ($request->ajax()) {
            if (Session::has('product_ids') && count(Session::get('product_ids'))) {
                $products = CatalogProduct::getProducts(
                    Session::get('product_ids'),
                    $request->page
                //$catalog_children
                );

                return view(
                    'catalog.list',
                    compact(
                        'products'
                    )
                );
            } else {
                // if found ids is empty return empty page
                return view('catalog.index');
            }
        } else {
            // forget duplicate upi_id (that has attributes)
            Session::forget('product_upi_block');
        }

        // get left menu with catalogs
        $catalog_sub_menu = App::make('Catalog')
            ->getSubMenu($categorySlug);

        //Get title for page
        $catalog_active = App::make('Catalog')
            ->getCategoryBySlug($categorySlug);
        $local = "name_" . App::getLocale();
        $title = $catalog_active->$local;
        // if this is not ajax upload products put in the cache list of ids
        $referenceIds = Cache::remember(
            'catalog_' . $categorySlug,
            config('app.cache_time'),
            function () use ($catalogChildren) {
                return CatalogProduct::with('category')
                    ->whereHas('category', function ($query) use ($catalogChildren) {
                        $query->whereIn('catalog_categories.id', $catalogChildren);
                    })
                    ->active()
                    ->lists('hidden', 'id');
            }
        );

        $referenceIds2 = Cache::remember(
            'catalog2_' . $categorySlug,
            config('app.cache_time'),
            static function () use ($catalogChildren) {
                return CatalogProduct::with('category')
                    ->whereHas('category', function ($query) use ($catalogChildren) {
                        $query->whereNotIn('catalog_categories.id', $catalogChildren);
                    })
                    ->active()
                    ->lists('hidden', 'id');
            }
        );

        // if found ids is empty return empty page
        if (! count($referenceIds) && ! count($referenceIds2)) {
            return view(
                'catalog.index',
                compact(
                    'title',
                    'catalog_sub_menu',
                    'catalog_active'
                )
            );
        }

        $referenceIds = CatalogProduct::shuffleProducts($referenceIds);
        $referenceIds2 = CatalogProduct::shuffleProducts($referenceIds2);

        $referenceIds = array_merge($referenceIds, $referenceIds2);

        // put it to session for ajax load
        Session::put('product_ids', $referenceIds);

        // else get products
        $products_count = ceil(count($referenceIds) / config('app.count_per_page'));

        $products = CatalogProduct::getProducts(
            $referenceIds,
            $request->page
        //$catalog_children
        );

        return view(
            'catalog.index',
            compact(
                'products',
                'products_count',
                'title',
                'catalog_sub_menu',
                'catalog_active'
            )
        );
    }

    /**
     * Setting user as adult for category 18+
     * @return mixed
     */
    public function setAdult()
    {
        $cookies = Cookie::forever('adult', 'yes');

        return response()
            ->view('user.empty')
            ->withCookie($cookies);
    }
}
