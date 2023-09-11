<!doctype html>
<html>
<head>
    <script>
        // for ie
        function msieversion() {
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf ( "MSIE " );
            if ( msie > 0 ) {     // If Internet Explorer, return version number
                return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)));
            }
            else {               // If another browser, return 0
                return 0;
            }

        }

        ieversion = msieversion();
        if ( ieversion < 9 && ieversion >= 3){
            window.location.href = '/ie8';
        }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="w1-verification" content="108542481549" />
    <meta name='yandex-verification' content='6425ea77559ba173' />
    <meta name="keywords" content="{{ isset($meta_keywords) ? $meta_keywords : trans('home.title') }}">
    <meta name="description" content="{{ isset($title) ? $title : trans('home.title') }}">
    <meta property="og:title" content="{{ isset($title) ? $title : trans('home.title') }}"/>
    <meta property="og:description" content="{{ isset($description) ? mb_substr($description,0,180).'...' : trans('home.title') }}"/>
    <meta property="og:site_name" content="allfor2.com"/>
    <meta property="og:url" content="{{ URL::full() }}"/>
    <meta property="og:image" content="{{ isset($image) ? image_asset($image,'lg') : asset('/images/social-logo.png') }}"/>
    <meta name="twitter:image" content="{{ isset($image) ? image_asset($image,'lg') : asset('/images/social-logo.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@allfor2">
    <meta name="twitter:title" content="{{ isset($title) ? $title : trans('home.title') }}">
    <meta name="twitter:description" content="{{ isset($description) ? mb_substr($description,0,180).'...' : trans('home.title') }}">
    <meta name="twitter:creator" content="@allfor2">

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('ios-fav.ico') }}" type="image/x-icon" />

    <link rel="stylesheet" href="{{ elixir("css/style.css") }}">
    <script src="{{ elixir("js/all.js") }}"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    {{--<script src="//vk.com/js/api/openapi.js" type="text/javascript"></script>--}}
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.css"/>
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.min.js"></script>
    <script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
    <script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
    <script src="{{ elixir("js/file-upload.js") }}"></script>

    <script id="widget-wfp-script" language="javascript" type="text/javascript" src="https://secure.wayforpay.com/server/pay-widget.js"></script>

    {{--<script type="text/javascript" src="{{ elixir("js/jquery.touchSwipe.min.js") }}"></script>--}}
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

    <title>{{ isset($title) ? $title : trans('home.title') }}</title>
</head>
<body class="body-site">
<header> @include('layout.header') </header>
<div class="main_content container-fluid">
    <div class="contents">
        @include('alertMessages')
        @include('productModalLink')

        <div class="container-fluid main-content">
            <div class="catalog-control clearfix hidden">
                <a href="{{ url('search') }}" class="view-style-products view-style-list" data-click-state="blocks"></a>
            </div>
            <div class="product-list" id="{{ Request::is('/') ? 'main_list' : '' }}">
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
    {{-- @include('layout.footer') --}}
</footer>

@include('messengers')
</body>
</html>