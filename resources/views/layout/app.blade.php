<!doctype html>
<html>
<head>
    <script>
        // for ie
        function msieversion() {
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");
            if (msie > 0) {     // If Internet Explorer, return version number
                return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)));
            } else {               // If another browser, return 0
                return 0;
            }

        }

        ieversion = msieversion();
        if (ieversion < 9 && ieversion >= 3) {
            window.location.href = '/ie8';
        }
    </script>
    <meta name="viewport" content="height=device-height,
                      width=device-width, initial-scale=1.0,
                      minimum-scale=1.0, maximum-scale=1.0,
                      user-scalable=no, target-densitydpi=device-dpi">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="w1-verification" content="108542481549"/>
    <meta name='yandex-verification' content='6425ea77559ba173'/>
    <meta name="keywords"
          content="{{ isset($meta_keywords) ? $meta_keywords : trans('home.title') }}">
    <meta name="description"
          content="{{ isset($title) ? $title : trans('home.title') }}">
    <meta property="og:title"
          content="{{ isset($title) ? $title : trans('home.title') }}"/>
    <meta property="og:description"
          content="{{ isset($description) ? mb_substr($description,0,180).'...' : trans('home.title') }}"/>
    <meta property="og:site_name" content="allfor2.com"/>
    <meta property="og:url" content="{{ URL::full() }}"/>
    <meta property="og:image"
          content="{{ isset($image) ? image_asset($image,'lg') : asset('/images/social-logo.png') }}"/>
    <meta name="twitter:image"
          content="{{ isset($image) ? image_asset($image,'lg') : asset('/images/social-logo.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@allfor2">
    <meta name="twitter:title"
          content="{{ isset($title) ? $title : trans('home.title') }}">
    <meta name="twitter:description"
          content="{{ isset($description) ? mb_substr($description,0,180).'...' : trans('home.title') }}">
    <meta name="twitter:creator" content="@allfor2">

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon"/>
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}"
          type="image/x-icon"/>

    <link href="https://fonts.googleapis.com/css?family=Montserrat&amp;subset=cyrillic"
          rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('build/fonts/fonts.css') }}"/>

    <link rel="stylesheet"
          href="{{ asset('build/plugins/formstyler/formstyler.css') }}"/>
    <link rel="stylesheet"
          href="{{ asset('build/plugins/arcticmodal/jquery.arcticmodal.css') }}"/>
    <link rel="stylesheet"
          href="{{ asset('build/plugins/mCustomScrollbar/css/jquery.mCustomScrollbar.min.css') }}"/>
    <link rel="stylesheet"
          href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="{{ elixir('css/style.css') }}">

    <script src="{{ elixir('js/all.js') }}?v=0.0.1"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.jsdelivr.net/jquery.slick/1.5.9/slick.css"/>
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/jquery.slick/1.5.9/slick.min.js"></script>
    <script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
    <script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
    <script src="{{ asset('build/js/file-upload-e114f6c3f7.js') }}"></script>
    <script id="widget-wfp-script" language="javascript" type="text/javascript"
            src="https://secure.wayforpay.com/server/pay-widget.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic'
          rel='stylesheet' type='text/css'>
    <title>{{ isset($title) ? $title : trans('home.title') }}</title>

</head>
<body class="body-site">
<header id="header" style="opacity: 0.9">
    <div class="container-fluid">
        <div class="justify-content-between">
            <div class="header_left">
                <div class="logo">
                    <a href="{{ url('/') }}"><img
                                src="{{ asset('images/logo.jpg') }}"
                                alt="{{ trans('home.title') }}"></a>
                </div>
                <a class="header_category hide_1200" href="javascript:void(0);"><img
                            src="{{ asset('images/icons/menu-icon.png') }}"
                            alt=""></a>
                {{--<a class="header_menu hide_767 hide_992" href="javascript:void(0);"><img src="{{ asset('images/icons/setting-icon.png') }}" alt="settings"></a>--}}
            </div>

            <div class="header_center">
                <a class="search_btn hide_480" href="javascript:void(0);"><img
                            src="{{ asset('images/icons/search-icon-mob.png') }}"
                            alt="search"></a>
                <div class="header_center_top">
                    <menu class="category_link" style="display: none;">
                        {{--@foreach (\App\Models\CatalogCategory::whereIsRoot()->take(10)->get() as $catalog_item)
                            <li><a href="{{ url('catalog/' . $catalog_item->slug) }}">{{  $catalog_item->name_ru }}</a></li>
                        @endforeach--}}
                    </menu>
                    {!! Form::open(array('url' => url('search'), 'class' => 'header_form search_form'))!!}
                    <input class="form_input2" type="text"
                           value="{{ Session::get('search', '') }}"
                           name="search"
                           placeholder="{{ trans('home.search') }}"/>
                    <button type="submit"><img
                                src="{{ asset('images/icons/search-icon.png') }}"
                                alt="search"></button>
                    {!! Form::close()!!}
                </div>
                <div class="header_center_bottom">
                    <menu class="category_link">
                        {{--@foreach (\App\Models\CatalogCategory::whereIsRoot()->skip(10)->take(20)->get() as $catalog_item)
                            <li><a href="{{ url('catalog/' . $catalog_item->slug) }}">{{  $catalog_item->name_ru }}</a></li>
                        @endforeach--}}
                    </menu>
                    <menu class="category_link mob_category">
                        @foreach (\App\Models\CatalogCategory::whereIsRoot()->get() as $catalog_item)
                            <li>
                                <a href="{{ url('catalog/' . $catalog_item->slug) }}">{{  $catalog_item->name_en }}</a>
                            </li>
                        @endforeach
                    </menu>
                </div>
            </div>

            <div class="header_right">
                <menu class="main_menu">
                    <li><a href="javascript:void(0);"
                           data-popup="#contact_popup">#{{ trans('home.menuContactUs') }}</a>
                    </li>
                    <li><a href="javascript:void(0);" data-popup="#about_popup">#{{ trans('home.menuAboutUs') }}</a>
                    </li>
                    <li><a href="javascript:void(0);"
                           data-popup="#delivery_popup">#{{ trans('home.menuDelivery') }}</a>
                    </li>
                    <li><a href="javascript:void(0);"
                           data-popup="#payment_popup">#{{ trans('home.menuPayments') }}</a>
                    </li>
                    {{--<li><a href="javascript:void(0);" data-popup="#ofert_popup">#{{ trans('home.menuContract') }}</a></li>--}}
                    <li><a href="javascript:void(0);"
                           data-popup="#return_popup">#{{ trans('home.menuReturns') }}</a>
                    </li>
                </menu>
                <a class="header_menu " href="javascript:void(0);"><img
                            src="{{ asset('images/icons/setting-icon.png') }}"
                            alt="menu"></a>

                @include('layout.header.basket')
                
                @if (Auth::check())
                    @if(isset($authUser->image))
                        <a href="{{ url('user') }}" class="header_login"><img
                                    src="{{ $authUser->image }}"
                                    class="user_icon" alt="user"></a>
                    @else
                        <a href="{{ url('user') }}" class="header_login"><img
                                    src="{{ asset('/images/user-profile.png') }}"
                                    class="user_icon" alt="user"></a>
                    @endif
                @else
                    <a class="header_login" href="#modal_user"
                       data-toggle="modal"><img
                                src="{{ asset('images/icons/user-profile-icon.png') }}"
                                alt=""></a>
                @endif

            </div>
        </div>
    </div>
</header>

<div class="main_content container-fluid">
    <div class="contents">
        @include('alertMessages')
        @include('productModalLink')

        <div class="main-content">
            <div class="catalog-control clearfix hidden">
                {{--                <a href="{{ url('search') }}" class="view-style-products view-style-list" data-click-state="blocks"></a>--}}
            </div>
            <div class="product-list"
                 id="{{ Request::is('/') ? 'main_list' : '' }}">
                @yield('bannder')
                @yield('content')
            </div>
        </div>
    </div>
</div>
@include('modal')
@include('auth.loginm')
@include('messenger.create')
@include('callBackModal')
@include('catalog._modalWaitProduct')

<footer>

</footer>

@include('messengers')

<!-- Popup Start     ============================================ -->
<div style="display:none;">

    <div class="arcticmodal_container_box popup_container2" id="coupon_popup">
        <div class="arcticmodal-close close_popup">&times;</div>
        <h2 class="popup-title">{{ trans('home.couponTitle') }}</h2>
        <p>{{ trans('home.couponDescription') }}</p>
    </div>

    <div class="arcticmodal_container_box popup_container3" id="like_popup">
        <div class="arcticmodal-close close_popup">&times;</div>
        {!! trans('home.likePopup') !!}
    </div>

    <div class="arcticmodal_container_box popup_container3" id="free_popup">
        <div class="arcticmodal-close close_popup">&times;</div>
        {!! trans('home.freePopup') !!}
    </div>

    <div class="arcticmodal_container_box popup_container3" id="contact_popup">
        <div class="arcticmodal-close close_popup">&times;</div>
        <ul class="popup_social_list">
            <li><a class="js-social-link"
                   href="https://www.facebook.com/allfor2com/" target="_blank">
                    <i>
                        <img src="{{ asset('images/icons/facebook-color-icon.png') }}"
                             alt="">
                        <img src="{{ asset('images/icons/facebook-grey-icon.png') }}"
                             alt="">
                    </i>
                    <span>Facebook</span>
                </a></li>
            <li><a class="js-social-link"
                   href="https://www.instagram.com/allfor2com/" target="_blank">
                    <i>
                        <img src="{{ asset('images/icons/insta-color-icon.png') }}"
                             alt="">
                        <img src="{{ asset('images/icons/insta-grey-icon.png') }}"
                             alt="">
                    </i>
                    <span>Instagram</span>
                </a></li>
            {{--            <li><a class="js-social-link viber" href="javascript:;">
                                <i>
                                    <img src="{{ asset('images/icons/viber-color-icon.png') }}" alt="">
                                    <img src="{{ asset('images/icons/viber-grey-icon.png') }}" alt="">
                                </i>
                                <span>Viber</span>
                            </a></li>--}}
            <li><a class="js-social-link telegram" href="https://t.me/allforr2"
                   target="_blank">
                    <i>
                        <img src="{{ asset('images/icons/telegram-color-icon.png') }}"
                             alt="">
                        <img src="{{ asset('images/icons/telegram-grey-icon.png') }}"
                             alt="">
                    </i>
                    <span>Telegram</span>
                </a></li>
            {{--            <li><a class="js-social-link whatsapp" href="javascript:;">
                                <i>
                                    <img src="{{ asset('images/icons/whatsup-color-icon.png') }}" alt="">
                                    <img src="{{ asset('images/icons/whatsup-grey-icon.png') }}" alt="">
                                </i>
                                <span>WhatsApp</span>
                            </a></li>--}}
            <li><a class="js-social-link email" href="javascript:;">
                    <i>
                        <img src="{{ asset('images/icons/email-color-icon.png') }}"
                             alt="">
                        <img src="{{ asset('images/icons/email-grey-icon.png') }}"
                             alt="">
                    </i>
                    <span>E-mail</span>
                </a></li>
            <li><a class="js-social-link msg"
                   href="https://www.facebook.com/allfor2com/" target="_blank">
                    <i>
                        <img src="{{ asset('images/icons/msg.png') }}" alt="">
                        <img src="{{ asset('images/icons/msg-grey.png') }}"
                             alt="">
                    </i>
                    <span>Messenger</span>
                </a></li>
            {{--            <li><a class="js-social-link tel" href="javascript:;">
                                <i>
                                    <img src="{{ asset('images/icons/tel-color-icon.png') }}" alt="">
                                    <img src="{{ asset('images/icons/tel-grey-icon.png') }}" alt="">
                                </i>
                                <span>Phone</span>
                            </a></li>--}}
        </ul>
        <div class="popup_hide">
            <span class="popup_hide_link viber"
                  style="font-size: 14px;">{!! trans('home.linkViber') !!}</span>
            {{--<a class="popup_hide_link viber" href="https://chats.viber.com/allfor2/ru" target="_blank">chats.viber.com/allfor2</a>--}}
            {{--<span class="popup_hide_link telegram" style="font-size: 14px;">{!! trans('home.linkTelegram') !!}</span>--}}
            {{--<a class="popup_hide_link telegram" href="https://t.me/allforr2" target="_blank">t.me/allforr2</a>--}}
            <span class="popup_hide_link whatsapp"
                  style="font-size: 14px;">{!! trans('home.linkWhatsapp') !!}</span>
            {{--<a class="popup_hide_link whatsapp" href="https://wa.me/380979692473" target="_blank">wa.me/380979692473</a>--}}
            <span class="popup_hide_link tel">+38 097 9692473</span>
            <span class="popup_hide_link email">help@allfor2.com</span>
        </div>
    </div>

    <div class="arcticmodal_container_box popup_container4" id="ofert_popup">
        <div class="arcticmodal-close close_popup">&times;</div>
        <div class="mCustomScrollbar mCustomScrollbarPopup"
             data-mcs-theme="dark">

            {!! trans('home.contract') !!}

        </div>
    </div>

    <div class="arcticmodal_container_box popup_container" id="delivery_popup">
        <div class="arcticmodal-close close_popup">&times;</div>

        {!! trans('home.deliveryInfo') !!}

    </div>

    <div class="arcticmodal_container_box popup_container"
         id="cart_delivery_popup">
        <div class="arcticmodal-close close_popup">&times;</div>

        {!! trans('home.cartDeliveryinfo') !!}

    </div>

    <div class="arcticmodal_container_box popup_container5" id="return_popup">
        <div class="arcticmodal-close close_popup">&times;</div>

        {!! trans('home.returns') !!}

    </div>

    <div class="arcticmodal_container_box popup_container5" id="payment_popup">
        <div class="arcticmodal-close close_popup">&times;</div>

        {!! trans('home.payment') !!}

    </div>

    <div class="arcticmodal_container_box popup_container" id="about_popup">
        <div class="arcticmodal-close close_popup">&times;</div>

        {!! trans('home.aboutUs2') !!}

    </div>
</div>
<!-- Popup End       ============================================ -->

<!-- Include Libs     ============================================ -->
<script src="{{ asset('build/plugins/modernizr.js') }}"></script>
<script src="{{ asset('build/plugins/formstyler/formstyler.js') }}"></script>
<script src="{{ asset('build/plugins/arcticmodal/jquery.arcticmodal.js') }}"></script>
<script src="{{ asset('build/plugins/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>

<!-- Include Libs End       ============================================ -->
<!-- Scripts Init Plugins & Core START     ============================================ -->
<script src="{{ asset('build/js/script.init.js') }}"></script>
<script src="{{ asset('build/js/script.core.js') }}"></script>
@push('scripts')
    <script>
        /* hide right menu */
        $(document).mouseup(function (e) { // отслеживаем событие клика по веб-документу
            var menu = $('.header_right .main_menu'); // определяем элемент, к которому будем применять условия (можем указывать ID, класс либо любой другой идентификатор элемента)
            if (!menu.is(e.target) // проверка условия если клик был не по нашему блоку
                && menu.has(e.target).length === 0) { // проверка условия если клик не по его дочерним элементам
                menu.removeClass('active'); // если условия выполняются - скрываем наш элемент
            } else {
                if ($(e.target).is('a'))
                    menu.removeClass('active')
            }
        });
        /* hide right menu */
    </script>
@endpush
@stack('scripts')
<!-- Scripts Init Plugins & Core END       ============================================ -->

</body>
</html>