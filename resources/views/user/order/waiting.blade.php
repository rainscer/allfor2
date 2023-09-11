@extends('user.index')
@section('user_content')
    <div class="likes_header prod-pad">
        {{ trans('user.productsNotPaid') }}
    </div>
    <div class="grid prod-pad block-products-with-event" id="not-paid-block">
        @foreach($orders as $order_item)
            <div class="product-item grid-item" id="block-{{ $order_item->product->id }}">
                <div class="product-img-block">
                    <a href="{{ route('product.url',[$order_item->product->upi_id, $order_item->product->slug]) }}" class="link_modal">
                        <img src="{{ $order_item->product->getMainImage('md') }}" alt="{!! $order_item->product->$local !!}">
                    </a>
                </div>
                <div class="product-name">
                    <a href="{{ route('product.url',[$order_item->product->upi_id, $order_item->product->slug]) }}" class="link_modal">
                        {{ $order_item->product->$local }}
                    </a>
                </div>
                <div class="product-price">${{ $order_item->product->price }}{{--{{$curency_code}}--}}</div>
                <span class="shadow"></span>
                <button type="button" class="delete-btn" data-owner-id="{{ $order_item->product->id }}" data-url="{{ url('user/not-paid/delete') }}"></button>
                <input type="checkbox" id="{{ $order_item->product->id }}" name="order_item[]" value="{{ $order_item->product->id }}">
                <label for="{{ $order_item->product->id }}"><span></span></label>
            </div>
        @endforeach

        @foreach($carts as $cart_items)
            @foreach ($cart_items as $cart_item)
                <div class="product-item grid-item" id="block-{{ $cart_item->product->id }}">
                    <div class="product-img-block">
                        <a href="{{ route('product.url',[$cart_item->product->upi_id, $cart_item->product->slug]) }}" class="link_modal">
                            <img src="{{ $cart_item->product->getMainImage('md') }}" alt="{!! $cart_item->product->$local !!}">
                        </a>
                    </div>
                    <div class="product-name">
                        <a href="{{ route('product.url',[$cart_item->product->upi_id, $cart_item->product->slug]) }}" class="link_modal">
                            {{ $cart_item->product->$local }}
                        </a>
                    </div>
                    <div class="product-price">${{ $cart_item->product->price }}{{--{{$curency_code}}--}}</div>
                    <span class="shadow"></span>
                    <button type="button" class="delete-btn" data-owner-id="{{ $cart_item->product->id }}" data-url="{{ url('user/cart/delete') }}"></button>
                    <input type="checkbox" id="{{ $cart_item->product->id }}" name="order_item[]" value="{{ $cart_item->product->id }}">
                    <label for="{{ $cart_item->product->id }}"><span></span></label>
                </div>
            @endforeach
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
@endsection