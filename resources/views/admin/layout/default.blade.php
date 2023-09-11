<!doctype html>
<html>
<head>
    <meta name="viewport" content="height=device-height,
                      width=device-width, initial-scale=1.0,
                      minimum-scale=1.0, maximum-scale=1.0,
                      user-scalable=no, target-densitydpi=device-dpi">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ elixir("css/style.css") }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <script src="{{ elixir("js/all.js") }}"></script>
    <script src="{{ asset('vendor/js/ckeditor/ckeditor.js') }}" type="text/javascript" charset="utf-8" ></script>
    <script src="{{ asset('public/select2/dist/js/select2.js') }}" type="text/javascript" charset="utf-8" ></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/select2/dist/css/select2.css') }}"/>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.css"/>
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.min.js"></script>
    {{--<script src="//vk.com/js/api/openapi.js" type="text/javascript"></script>--}}
    <title>{{ isset($title) ? $title : trans('home.title') }}</title>
</head>
<body class="body-site">
<div class="container-fluid">
    <header> @include('admin.layout.header') </header>
    <div class="contents">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Ошибка!</strong><br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            @if (session()->has('success'))
                <div class="alert alert-success" role="alert">{{ session()->get('success') }}</div>
            @endif
        @yield('content')
            <input type="text" id="site-title" class="hidden" value="{{ isset($title) ? $title : trans('home.title') }}">
            <input type="text" id="site-url" class="hidden" value="{{ Request::url() }}">
    </div>
</div>
@include('modal')
<footer> @include('admin.layout.footer')
    <style>
        @media (min-width: 768px) {
            .dropdown:hover .dropdown-menu {
                display: block;
            }
        }
    </style>
</footer>
</body>
</html>