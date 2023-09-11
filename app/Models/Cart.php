<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Cookie;
use Illuminate\Support\Facades\Validator;


/**
 * Class Cart
 * @property Carbon  updated_at
 * @property Carbon  created_at
 * @property integer user_id
 * @property boolean deletion_mark
 * @property boolean posted
 * @property string  uid
 * @package App\Models
 */
class Cart extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'uid',
        'deletion_mark',
        'posted'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cart_products()
    {
        return $this->hasMany(
            'App\Models\CartProduct',
            'cart_id',
            'id'
        );
    }

    /**
     *
     */
    public static function clean()
    {
        $cart = self::find(Session::get('cart_id'));
        if ($cart) {
            $cart->deletion_mark = 1;
            $cart->save();
        }
        // delete cart from session
        Session::forget('cart_id');
        Session::forget('cart_products');
    }

    /**
     * @param $product_id
     * @return bool|null|void
     */
    public static function deleteProduct($product_id)
    {
        Session::forget('cart_products.' . $product_id);

        CartProduct::where('product_id', '=', $product_id)
            ->where('cart_id', '=', Session::get('cart_id'))
            ->delete();

        $cart_count = CartProduct::where('cart_id', '=', Session::get('cart_id'))
            ->count();

        // if its 0 mark cart as deleted
        if (! $cart_count) {
            self::clean();

            return false;
        }

        return true;
    }


    /**
     * Update product in session and in DB cart
     * params is product_id and its new quantity
     * @param $products
     */
    public static function updateProduct($products)
    {
        foreach ($products as $product) {
            // get product in session of cart
            $cart_product = Session::get('cart_products.' . $product['itemId']);

            // set new quantity
            $cart_product['quantity'] = $product['itemQuantity'];
            Session::put('cart_products.' . $product['itemId'], $cart_product);

            // update in DB
            CartProduct::where('product_id', '=', $product['itemId'])
                ->where('cart_id', '=', Session::get('cart_id'))
                ->update(['quantity' => $cart_product['quantity']]);
        }
    }


    /** Add product to cart
     * @param $product_id
     * @param $quantity
     * @return mixed
     */
    public function addProduct($product_id, $quantity)
    {
        $cart_uid = null;
        // if we have current cart get this id
        if (Session::has('cart_id')) {
            $cart_id = Session::get('cart_id');
            $new = false;
            // else create new one
        } else {
            $cart = self::createNew();
            $cart_id = $cart->id;
            $cart_uid = $cart->uid;
            $new = true;
        }

        // save or update product in DB
        $product = CatalogProduct::where('id', $product_id)
            ->with([
                'image' => function ($query) {
                    $query->orderBy('id', 'asc');
                }
            ])
            ->first();
        $quantity = CartProduct::saveOrUpdateProductInCart($cart_id, $product, $quantity);

        // if we has this product we update it quantity
        if (Session::has('cart_products.' . $product->id)) {
            $cart_product = Session::get('cart_products.' . $product->id);
            $cart_product['quantity'] = $quantity;
            Session::put('cart_products.' . $product->id, $cart_product);
            // else put new in session
        } else {
            Session::put(
                'cart_products.' . $product->id,
                [
                    'cart_id'       => $cart_id,
                    'product_id'    => $product->id,
                    'name_ru'       => $product->name_ru,
                    'name_en'       => $product->name_en,
                    'quantity'      => $quantity,
                    'price'         => $product->price,
                    'upi_id'        => $product->upi_id,
                    'image'         => $product->getMainImage('sm'),
                    'delivery_type' => $product->delivery_type
                ]
            );
        }

        $cart_products = CatalogProduct::checkPrices(Session::get('cart_products', null));
        Session::put('cart_products', $cart_products);

        return compact(
            'cart_products',
            'cart_id',
            'cart_uid',
            'new'
        );
    }

    /**
     * Posted cart and finish order
     * @param $cart_id
     * @param $cart_products
     */
    public static function clearAndFinish($cart_id, $cart_products)
    {
        foreach ($cart_products as $cart_product) {
            // update count of sold in products
            CatalogProduct::where('id', '=', $cart_product->product_id)
                ->update([
                    'sold' => DB::raw('catalog_products.sold + 1')
                ]);
        }

        // set posted cart
        $cart = self::find($cart_id);
        $cart->posted = 1;
        $cart->save();
    }

    /** Create new cart
     * @return mixed
     */
    public static function createNew()
    {
        $cart = new self;
        $cart->uid = Session::getId();
        $cart->user_id = isset(Auth::user()->id) ? Auth::user()->id : '';
        $cart->save();

        Session::put('cart_id', $cart->id);
        Session::forget('cart_products');

        return $cart;
    }

    /**
     * @param      $productArray
     * @param bool $needTotal
     * @return Collection
     */
    public static function getDeliveryCostInfo($productArray, $needTotal = false)
    {
        /*$deliveryCost = CatalogProduct::getDeliveryCost($products);
        Session::put('delivery_cost', $deliveryCost);*/
        $deliveryInfo = new Collection();
        $deliveryInfo->packing_price = app('Setting')->getSettingValue(Order::PACKING_COST_SETTING_NAME, 0);
        $deliveryInfo->ua_deliv_price = app('Setting')->getSettingValue(Order::UA_DELIVERY_COST_SETTING_NAME, 0);

        $deliveryInfo->coef_delivery = CatalogProduct::getWeightProductsPerkg($productArray);
        if ($needTotal) {
            return ($deliveryInfo->packing_price + $deliveryInfo->ua_deliv_price) * $deliveryInfo->coef_delivery;
        }

        return $deliveryInfo;
    }

    /**
     * @param $productArray
     * @return number
     */
    public static function getTotalCostCart($productArray)
    {
        $products = collect($productArray);

        $total_q = $products->lists('quantity', 'product_id');
        $total_p = $products->lists('price', 'product_id');

        $total = array_map(function ($item, $key) {
            return $item * $key;
        }, $total_q, $total_p);

        $deliveryCost = self::getDeliveryCostInfo($total_q, true);
        $total = array_sum($total) + $deliveryCost; // + $deliveryCost

        return $total;
    }

    /**
     * @param null $user
     * @return null|string
     */
    public static function validateUserAddress($user = null)
    {
        if (! $user) {
            $user = Auth::user();
        }
        $user->contacts = unserialize($user->contacts);
        $user->contacts = (array)$user->contacts;

        foreach ($user->contactFields as $field) {
            isset($user->contacts[$field]) ? $user->$field = $user->contacts[$field] : $user->$field = '';
        }

        $validator = Validator::make(
            [
                'd_user_city'      => $user->d_user_city,
                'd_user_phone'     => $user->d_user_phone,
                'd_user_index'     => $user->d_user_index,
                'd_user_address'   => $user->d_user_address,
                'd_user_email'     => $user->email,
                'd_user_last_name' => $user->last_name
            ],
            [
                'd_user_address'   => 'required',
                'd_user_city'      => 'required',
                'd_user_index'     => 'required|numeric',
                'd_user_phone'     => 'required',
                'd_user_email'     => 'required|email',
                'd_user_last_name' => 'required'
            ]
        );

        if (! $validator->fails()) {
            $validated_address = $user->name . ', ' . $user->last_name . ', ' .
                $user->d_user_address . ', ' . $user->d_user_city . ', ' .
                $user->d_user_region . ', ' . $user->d_user_index . ', ' .
                $user->d_user_phone . ', ' . $user->email;
        } else {
            $validated_address = null;
        }

        return $validated_address;
    }
}