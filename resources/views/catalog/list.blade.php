@if(isset($products) && $products)
    @foreach($products as $product)
        @if ($product->image->count())
            <div class="product-item grid-item {{ Request::ajax() ? 'ajax-load' : '' }}
            {{ $product->hidden ? 'not-active' : '' }}">
                <div class="product-img-block">
                    <a href="{{ route('product.url',[$product->upi_id, $product->slug]) }}"
                       class="link_modal">
                        <img src="{{ $product->getMainImage('md') }}"
                             alt="{{ $product->$local }}">
                    </a>
                </div>
                <div class="product-name">
                    <a href="{{ route('product.url',[$product->upi_id, $product->slug]) }}"
                       class="link_modal">
                        {{ $product->$local }}
                    </a>
                </div>

                <div class="product-price" style="text-align: center">
                    ${{ $product->price }}</div>

                <span class="shadow"></span>
            </div>
        @endif
    @endforeach

    @include('catalog.cart_button')

@endif