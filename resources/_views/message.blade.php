@extends('layout.default')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default register">
                    <div class="panel-body">
                        <h3>{{ trans('home.message') }}</h3>
                        <p>{{ $message }}</p>
                        @if ($redirect)
                            <script type="application/javascript">
                                setTimeout(
                                        function() {
                                            location.href = '{{ $redirect }}';
                                        },
                                        5000
                                );
                            </script>
                            <p class="like-h">Нажмите <a href="{{ $redirect }}">эту ссылку</a>, если ваш браузер не поддерживает автоматический редирект.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection