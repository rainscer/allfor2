@extends('user.index')
@section('user_content')
    <div class="likes_header prod-pad">
        {{ trans('user.productsVisited') }}
    </div>
    @if(isset($user->product_viewed) && count($user->product_viewed))
        <div class="grid prod-pad block-products-with-event">
            @foreach($user->product_viewed as $product)
                <div class="product-item grid-item" id="block-{{ $product->id }}">
                    <div class="product-img-block">
                        <a href="{{ route('product.url',[$product->upi_id, $product->slug]) }}" class="link_modal">
                            <img src="{{ $product->getMainImage('md') }}" alt="{!! $product->$local !!}">
                        </a>
                    </div>
                    <div class="product-name">
                        <a href="{{ route('product.url',[$product->upi_id, $product->slug]) }}" class="link_modal">
                            {!! mb_strlen($product->$local) > 60 ? mb_substr($product->$local,0,57)."..." : $product->$local !!}
                        </a>
                    </div>
                    <div class="product-price">${{ $product->price }}{{--{{$curency_code}}--}}</div>
                    <input type="checkbox" id="{{ $product->id }}" name="order_item[]" value="{{ $product->id }}">
                    <label for="{{ $product->id }}"><span></span></label>
                    <span class="shadow"></span>
                </div>
            @endforeach
        </div>

        <div class="add-to-cart-block">
            {{ trans('user.checked') }} <span class="total-checked bold">0</span> {{ trans('user.products') }}
            <div class="btn-likes-block">
            <a href="{{ url('cart') }}" class="add-to-cart-likes" data-owner-id="{{ url('/user/add-to-cart') }}">
                {{ trans('user.take') }}!
            </a>
            <button type="button" class="clear-likes">
                {{ trans('user.clear') }}
            </button>
            </div>
        </div>
    @endif
@endsection