
    @if(isset($dop_products) && $dop_products)
        @foreach($dop_products as $product_item)
            @if ($product_item->image->count())
                <div class="dop-item">
                    <div class="product-item">
                        <div class="dop-product-img-block">
                            <a href="{{ route('product.url',[$product_item->upi_id, $product_item->slug]) }}" class="link_modal">
                                <img src="{{ $product_item->getMainImage('md') }}" alt="{!! $product_item->$local !!}">
                            </a>
                        </div>
                        <div class="product-name">
                            <a href="{{ route('product.url',[$product_item->upi_id, $product_item->slug]) }}" class="link_modal">
                                {!! mb_strlen($product_item->$local) > 80 ? mb_substr($product_item->$local,0,77)."..." : $product_item->$local !!}
                            </a>
                        </div>
                        <div class="product-price">
                            ${!! $product_item->price !!}{{--{{$curency_code}}--}}
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif