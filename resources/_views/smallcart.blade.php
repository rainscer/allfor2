@if((isset($small_cart_products) && count($small_cart_products)) || (Session::has('cart_id') && App::make('Cart')->checkPostedAndDeleted(Session::get('cart_id'))))
    <div class="basket-title">{{ trans('home.basket') }}</div>
    <div class="cart-products">
        {{--*/ $total_sum = 0; /*--}}
        {{--*/ $total_count = 0; /*--}}
        {{-- */ if(!isset($small_cart_products)) { $small_cart_products = Session::get('cart_products'); } /* --}}
        @if(is_array($small_cart_products))
            @foreach($small_cart_products as $cart_product)
                <div id="small-pr{{ $cart_product['product_id'] }}" class="basket-product-item clearfix">
                    <div class="prod-img"><img src="{{ $cart_product['image'] }}"></div>
                    <div class="prod-desc">
                        {{ $cart_product[$local] }}
                        <div class="prod-count"><span class="quantity">{{ $cart_product['quantity'] }}</span> x
                            <span class="prod count-price">{{ $cart_product['price'] }}</span><span>{{ $curency_code }}</span>
                        </div>
                        <div class="prod-count">
                            {{-- */ $delivery_type = isset($cart_product['delivery_type']) ? $cart_product['delivery_type'] : false /* --}}
                            {{ trans('cart.delivery') }}: {{ \App\Models\CatalogProduct::staticCheckDeliveryType($delivery_type) }}
                        </div>
                    </div>

                    <div class="prod-delete">
                        <a href="{{ url('cart/delete') }}" data-owner-id="{{ $cart_product['product_id'] }}" class="ajaxActionDeleteProduct delete_prod_cart"></a>
                    </div>
                </div>
                {{-- */ $total_count += $cart_product['quantity'] /* --}}
                {{-- */ $total_sum += $cart_product['quantity'] * $cart_product['price'] /* --}}
            @endforeach
        @endif
    </div>
    {{-- */  if(!isset($deliveryCost)) { $deliveryCost = Session::get('delivery_cost',0); }
             $deliveryInfo = \App\Models\Cart::getDeliveryCostInfo(collect(Session::get('cart_products'))->lists('quantity', 'product_id'));
             $coef_delivery = $deliveryInfo->coef_delivery;
             $packing_price = $deliveryInfo->packing_price;
             $ua_deliv_price = $deliveryInfo->ua_deliv_price;

             // get delivery cost as total with all delivery parameters
              $total_sum += ($deliveryCost + $packing_price + $ua_deliv_price) * $coef_delivery;

             // get delivery cost as collection from delivery parameters
             $total_sum += $deliveryInfo->ua_deliv_price;

             $now = \Carbon\Carbon::now(); /* --}}
    <div class="clearfix"></div>
    <div class="basket-total-count">
        {{ trans('cart.productsInCart') }}: <span class="total-count">{{ $total_count }}</span>
    </div>
    <div class="basket-total-price">
        {{ trans('cart.total') }}: <span class="price-basket">{{ $total_sum }}</span> {{ $curency_code }}</div>
    <div class="delivery-info">
        <div class="bold">
            Бесплатная доставка
        </div>
        <div style="color: #949494;">
            Посылка у вас: {{ $now->addDays(20)->format('d.m') }} - {{ $now->addDays(10)->format('d.m') }}
        </div>
    </div>
    <div class="basket-buttons">
        <a href="{{ url('cart') }}" class="basket-order">{{ trans('cart.checkout') }}</a>
    </div>
    <div class="basket-buttons">
        <a href="{{  url('/cart/clean') }}" class="cart-clear">{{ trans('cart.clearCart') }}</a>
    </div>

@else
    <div class="empty_cart">{{ trans('cart.yourCartIsEmpty') }}</div>
@endif