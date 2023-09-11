@extends('layout.app')
@section('content')
	<div class="error"><img src="{{ asset('/images/404.png') }}"></div>
	<div class="error-btn centered"><a href="{{ url('/') }}">{{ trans('home.goHome') }}</a> </div>
@endsection