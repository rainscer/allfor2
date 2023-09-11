@extends('admin.layout.default')
@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Укр почта

        </div>
        <div class="panel-body">
            @foreach($result as $item)
                <p>{{ $item }}</p>
            @endforeach
        </div>
    </div>
@endsection