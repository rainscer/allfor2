@extends('emails.default')
@section('content')
    <img src="{{ asset('/images/user_email.png') }}" width="170" height="170">
    <div>
        <font color="#565656" size="3">
            {{ trans('user.linkForReset') }}
        </font>
    </div>
    <p>{{ url('password/reset/'.$token) }}</p>
@endsection
