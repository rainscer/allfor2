@extends('layout.app')
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
                            <p class="like-h">
                                {!! trans('home.autoRedirect', ['url' => $redirect ]) !!}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection