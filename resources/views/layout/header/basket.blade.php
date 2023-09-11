<div class="header_basket"
     onclick="$('.top-basket').slideToggle(400);">

    <img src="{{ asset('images/icons/cart-icon.png') }}" alt="">
    <span class="cart_total">
    @if((Session::has('cart_id')
            && App::make('Cart')->checkPostedAndDeleted(Session::get('cart_id')))
            || isset($small_cart_products))

            {{
                isset($small_cart_products)
                    ? collect($small_cart_products)->sum('quantity')
                    : collect(Session::get('cart_products'))->sum('quantity')
            }}
        @endif
    </span>
</div>

<div class="top-basket">
    @include('smallcart')
</div>