<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ elixir("css/style.css") }}">
    <script src="{{ elixir("js/all.js") }}"></script>
    <title>allfor2.com</title>
</head>
<body class="body-site">
<div class="container-fluid">
    <div class="contents"> @yield('content') </div>
</div>
@include('modal')
</body>
</html>