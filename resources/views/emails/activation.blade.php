@extends('emails.default')
@section('content')
    <img src="{{ asset('/images/user_email.png') }}" width="170" height="170">
    <div>
        <font color="#565656" size="4">Congratulations, <strong>{{ $username }} {{ $last_name }}</strong></font>
    </div>
    <div>
        <font color="#565656" size="4">You've successfuly registered on <strong>allfor2.com</strong>!</font>
    </div>
    <div style="margin-top: 20px;">
        <font color="#565656" size="3">Please, follow this link to activate your account!</font>
    </div>
    <div style="margin-bottom: 20px;">
        <a href="{{ $activationUrl }}">{{ $activationUrl }}</a>
    </div>
    <div style="margin-bottom: 20px;">
        <div>Thank you for your trust, and Welcome !</div>
        <div>Don't hesitate to contact us on any issues whatsoever !</div>
    </div>
@endsection