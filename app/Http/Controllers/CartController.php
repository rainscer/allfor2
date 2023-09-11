<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\CatalogProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CartController
 * @package App\Http\Controllers
 */
class CartController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /*if(!Auth::check() || (Auth::check() && !Auth::user()->active)){
            return redirect('/')->withErrors(['error' => 'Сайт находится на реконструкции']);
        }*/
        if (Session::has('cart_id') && Session::has('cart_products')) {
            // check if this cart is posted or deleted
            if (app('Cart')->checkPostedAndDeleted(Session::get('cart_id'))) {
                // get cart products
                $products = collect(Session::get('cart_products'));
                $cart_products = CatalogProduct::getProductWithAllRel();
                $cart_products = $cart_products->whereIn('id', $products->lists('product_id'))
                    ->get();

                $quantity = $products->lists('quantity', 'product_id');
                $deliveryInfo = Cart::getDeliveryCostInfo($quantity);

                $cities = User::getCities();

                if (Auth::check()) {
                    $user = Auth::user();
                    $validated_address = Cart::validateUserAddress($user);
                } else {
                    $user = new \stdClass();
                }

                $title = trans('cart.title');


                return view(
                    'order.cart_stripe',
                    compact(
                        'cart_products',
                        'quantity',
                        'deliveryInfo',
                        'cities',
                        'title',
                        'user',
                        'validated_address'
                    )
                );
            }
        }

        return view('order.cart');
    }

    /**
     * @return mixed
     */
    public function clean()
    {
        Cart::clean();
        $cookie = Cookie::forget('cart_uid');

        return response()
            ->redirectTo('/')
            ->with('message', trans('cart.emptyCart'))
            ->withCookie($cookie);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Request $request)
    {
        $notEmpty = Cart::deleteProduct($request->product_id);

        $products = collect(Session::get('cart_products'))
            ->lists('quantity', 'product_id');

        $checked_prices = CatalogProduct::checkPrices(Session::get('cart_products', null));
        Session::put('cart_products', $checked_prices);

        $smallCart = view('smallcart', [
            'small_cart_products' => $checked_prices,
            'deliveryCost'        => 0
        ])->render();

        // check if is empty - if "yes" - forget cookie
        if (! $notEmpty) {
            $cookie = Cookie::forget('cart_uid');

            return response()
                ->json([
                    'smallCart' => $smallCart
                ])
                ->withCookie($cookie);
        }

        return response()
            ->json([
                'smallCart'        => $smallCart,
                'cart_total_count' => array_sum($products)
            ]);
    }

    /**
     * @param Request $request
     * @param null    $coupon_value
     * @return Response
     */
    public function update(Request $request, $coupon_value = null)
    {
        Cart::updateProduct($request->products);

        $products = collect(Session::get('cart_products'))
            ->lists('quantity', 'product_id');

        $deliveryInfo = Cart::getDeliveryCostInfo($products);

        $checked_prices = CatalogProduct::checkPrices(Session::get('cart_products', null));
        Session::put('cart_products', $checked_prices);

        $smallCart = view('smallcart', [
            'small_cart_products' => $checked_prices,
            'deliveryCost'        => 0,
            'coef_delivery'       => $deliveryInfo->coef_delivery
        ])->render();

        $coupon_sum = -1;

        if ($coupon_value) {
            $coupon = Coupon::where('code', trim($coupon_value))
                ->where('expired_at', '>', date('Y-m-d'))
                ->first();

            $coupon_sum = 0;

            if ($coupon) {
                if ($coupon->count > $coupon->orders->count()) {
                    $coupon_sum = $coupon->amount;
                }
            }
        }

        return response()
            ->json([
                'smallCart'        => $smallCart,
                'cart_total_count' => array_sum($products),
                'deliveryCost'     => 0,
                'ua_deliv_price'   => /*$deliveryInfo->ua_deliv_price*/ 0,
                'packing_price'    => $deliveryInfo->packing_price,
                'coef_delivery'    => $deliveryInfo->coef_delivery,
                'coupon'           => $coupon_sum
            ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request)
    {
        $cart = (new Cart())->addProduct($request->product_id, $request->quantity);
        $cartTotal = Cart::getTotalCostCart($cart['cart_products']);

        $returnHTML = view('smallcart', [
            'small_cart_products' => $cart['cart_products'],
            'deliveryCost'        => 0
        ])
            ->render();

        // if this is new cart created put cart_uid to cookie
        if ($cart['new']) {
            $cookies = Cookie::forever('cart_uid', $cart['cart_uid']);

            return response()
                ->json([
                    'response_content' => $returnHTML,
                    'cart_total_count' => collect($cart['cart_products'])->sum('quantity'),
                    'cart_total'       => $cartTotal,
                    'message'          => trans('cart.productAdded')
                ])
                ->withCookie($cookies);
        }

        return response()->json([
            'response_content' => $returnHTML,
            'cart_total_count' => collect($cart['cart_products'])->sum('quantity'),
            'cart_total'       => $cartTotal,
            'message'          => trans('cart.productAdded')
        ]);
    }

    public function getCity(Request $request)
    {
        $results = DB::table('d_city')
            ->where('name', 'like', $request->term . '%')
            ->take(5)
            ->get();

        $city = [];
        foreach ($results as $result) {
            $region = DB::table('d_region')
                ->where('id', $result->region_id)
                ->first();
            $city[] = $result->name . ' (' . $region->name . ')';
        }

        return response()->json($city);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getState(Request $request)
    {
        $response = ['count' => 0, 'sum' => 0];
        if (Session::has('cart_products')) {
            foreach (Session::get('cart_products') as $cartProduct) {
                $response['sum'] += $cartProduct['price'] * (int)$cartProduct['quantity'];
                $response['count'] += $cartProduct['quantity'];
            }
        }

        return response()->json($response);
    }
}
