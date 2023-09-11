@extends('layout.default')
@section('content')
<div class="order-success-block">
    <h2>{{ trans('cart.thankYouForOrder') }}, <span class="colored-pink-order-success"> {{ Auth::check() ? Auth::user()->getFullName() : 'Гость' }}!</span></h2>
    <div class="order-success-text">
        <p class="clearfix">
            <span class="order-success-icon"><img src="{{ asset('/images/finish_order_partners.png') }}"></span>
            <span class="text">{{ trans('cart.textOrderSuccess1') }}</span>
        </p>
        <p class="clearfix">
            <span class="order-success-icon"><img src="{{ asset('/images/finish_order_settings.png') }}"></span>
            <span class="text">{{ trans('cart.textOrderSuccess2') }}</span>
        </p>
        <!--p class="clearfix">
            <span class="order-success-icon"><img src="{{ asset('/images/finish_order_questions.png') }}"></span>
            <span class="text">{{ trans('cart.textOrderSuccess3') }}
                <a href="{{ url('/user') }}">{{ trans('cart.textOrderSuccess3Link') }}</a> {{ trans('cart.textOrderSuccess31') }}</span>
        </p-->
        <p class="clearfix">
            <span class="order-success-icon"><img src="{{ asset('/images/finish_order_questions.png') }}"></span>
            <span class="text">{{ trans('cart.textOrderSuccess5') }}<a href="{{ url('/contacts') }}" class="animate modalFormToggle"><i class="fa fa-phone-square" aria-hidden="true"></i></a></span>
        </p>
        <p class="clearfix">
            <span class="order-success-icon"><img src="{{ asset('/images/finish_order_likes.png') }}"></span>
            <span class="text">{{ trans('cart.textOrderSuccess4') }} <a href="#want-free" class="show-want-free-block want-green"><i class="fa fa-gift" aria-hidden="true"></i> {{ trans('cart.textOrderSuccess4Link') }}! <i class="fa fa-gift" aria-hidden="true"></i></a></span>
        </p>
    </div>
    <div class="order-success-btn">
        <a href="{{ url('/') }}">{{ trans('cart.continueBuy') }}</a>
    </div>
    <div id="want-free">
        <img src="{{ asset('/images/want-free.png') }}" style="width: 100%;">
    </div>
</div>

@endsection