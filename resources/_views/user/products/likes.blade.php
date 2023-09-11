@extends('user.index')
@section('user_content')
    <div class="likes_header prod-pad">
        {{ trans('user.productsLiked') }}
    </div>
        @include('user.products.likes_list')
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