<div id="header-main" class="head container-fluid default">
    <div class="col-xs-3 col-md-2 col-sm-2 col-lg-2 logo-block">
        <a class="logo white_color" href="{{ url('/') }}">
            <img class="img-logo" src="{{ asset('images/logo.png') }}" alt="{{ trans('home.title') }}">
        </a>
        {{--
        <span class="language">Україна</span>
        <div class="dropdown language_list">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <img class="flag" src="{{ asset('images/ua.png') }}">
                 LaravelLocalization::getCurrentLocaleName()
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                    <li>
                        <a rel="alternate" hreflang="{{$localeCode}}" href="{{LaravelLocalization::getLocalizedURL($localeCode) }}">
                            <img class="flag" src="{{asset('images/' . $localeCode . '.png') }}">
                            {{ $properties['native'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        --}}
    </div>

    <div class="hidden-xs hidden-sm col-md-3 col-lg-3 hash_tags">
        @include('layout.menu')
    </div>
    <div class="hidden-xs col-md-3 col-sm-3 col-lg-3 search-form">
        {!! Form::open(array('url' => url('search'), 'class' => 'search_form'))!!}
        <input type="text" class="search-input" value="{{ Session::get('search', '') }}" name="search"
               data-url="{{ url('sub-search') }}" data-back-url="{{ url('search') }}"
               autocomplete="off" placeholder="{{ trans('home.search') }}">
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><img src="{{ asset('/images/search_line.png') }}"></button>
        </span>
        <!-- if you want add dropdown list with result - find in app.js "$('input[name="search"]').typeahead" block -->
        {!! Form::close()!!}
    </div>
    <!--div class="col-xs-2 col-xs-offset-0 col-md-2 col-md-offset-0 col-sm-2 col-sm-offset-0 col-lg-2 col-lg-offset-0 call_header"-->
    <!--div class="  col-sm-2 col-sm-offset-0 hidden-xs col-md-2 col-md-offset-0 col-lg-2 col-lg-offset-0 call_header">
        <a class="call-back" href="{{ Auth::check() ? '#callBack' : '#callBack' }}" data-toggle="modal">
            <img src="{{ asset('/images/svjaz.png') }}" class="call_icon" alt="call back">
        </a>
    </div-->
    <!--div class="col-xs-2 col-xs-offset-1 col-md-2 col-md-offset-0 col-sm-2 col-sm-offset-0 col-lg-2 col-lg-offset-0 user_header"-->
    <div class="col-xs-2 col-sm-2 hidden-md hidden-lg hash_tags_menu">
        <img src="{{ asset('/images/menu.png') }}" class="hash_tags_icon" alt="search-mobile"
             onclick="$('.col-xs-12.hash_tags_mobile').slideToggle(250);">
    </div>
    <div class="col-xs-12 col-sm-12 hidden-md hidden-lg hash_tags_mobile">
        @include('layout.menu')
    </div>
    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 user_header">
        @if (Auth::check())
            @if(isset($authUser->image))
                <a href="{{ url('user') }}" class="image_user_link"><img src="{{ $authUser->image }}" class="user_icon" alt="user"></a>
            @else
                <a href="/user" class="image_user_link">
                    <img src="{{ asset('/images/user-profile.png') }}" class="user_icon" alt="user">
                </a>
            @endif
                {{--<a href="{{ url('auth/logout') }}" class="user_link_logout white_color">{{ trans('home.logout') }}</a>--}}
        @else
            <a href="#modal_user" class="user_foto_header" data-toggle="modal">
                <img src="{{ asset('/images/user-profile.png') }}" class="user_icon" alt="user">
            </a>
            {{--<a href="#modal_user" class="user_link_logout white_color" data-toggle="modal">{{ trans('home.login') }}</a>--}}
        @endif
    </div>
    <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2 basket_header white_color">
        <div class="cart-img"  onclick="$('.top-basket').slideToggle(250);"><img src="{{ asset('/images/cart.png') }}" alt="cart icon">
            <div class="cart_total_header">
                @if((Session::has('cart_id') && App::make('Cart')->checkPostedAndDeleted(Session::get('cart_id'))) || isset($small_cart_products))
                    {{ isset($small_cart_products) ? collect($small_cart_products)->sum('quantity') : collect(Session::get('cart_products'))->sum('quantity') }}
                @endif
            </div>
        </div>
        {{--<div class="cart-header">{{ trans('home.basket') }}</div>--}}
        <div class="top-basket">
            <div class="basket">
                @include('smallcart')
            </div>
        </div>
    </div>
    <div class="hidden-md hidden-lg phone">
        <img src="{{ asset('/images/phone.png') }}" class="phone_icon" alt="phone back"
             onclick="$('.col-xs-12.phone_mobile').slideToggle(250);">
    </div>
    <div class="col-xs-12 col-md-2 phone_mobile">
        <div class="phones_data">
            <a href="{{ url('contacts') }}" class="modalFormToggle">#Контакты</a>
            <a href="{{ url('aboutUs') }}"  class="modalFormToggle">#О нас</a>
            <a href="{{ url('deliveryInfo') }}" class="modalFormToggle">#Доставка</a>
            <a href="{{ url('payment') }}" class="modalFormToggle">#Оплата</a>
        </div>
    </div>
    <div class="col-xs-2 col-xs-offset-2 hidden-md hidden-sm hidden-lg search_mobile">
        <img src="{{ asset('/images/search_mobile.png') }}" class="search_mobile_img" alt="search-mobile"
             onclick="$('.col-xs-12.search-form').slideToggle(250);">
    </div>
    <div class="col-xs-12 hidden-md hidden-sm hidden-lg search-form"  id="search_mobile">
        {!! Form::open(array('url' => url('search'), 'class' => 'search_form'))!!}
        <div class="input-group">
            <input type="text" class="search-input form-control" value="{{ Session::get('search', '') }}" name="search"
                   data-url="{{ url('sub-search') }}" data-back-url="{{ url('search') }}"
                   autocomplete="off">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit"><img src="{{ asset('/images/search.png') }}"></button>
      </span>
        </div>
        {!! Form::close()!!}
    </div>
</div>