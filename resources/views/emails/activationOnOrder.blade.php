@extends('emails.default')
@section('content')
    <img src="{{ asset('/images/user_email.png') }}" width="170" height="170">
    <div>
        <font color="#565656" size="4">Congratulations, <strong>{{ $username }} {{ $last_name }}</strong></font>
    </div>
    <div>
        <font color="#565656" size="4">You've placed your first order on <strong>allfor2</strong> and we are thrilled !</font>
    </div>
    <div style="margin-top: 20px;">
        <font color="#565656" size="3">In order to follow it's progress, or contact us, please sign in to your account.</font>
    </div>
    <div style="margin-top: 20px;">
        <font color="#565656" size="3">We've issued you a temporary password : <strong>12345678</strong> - use it to sign in for the first time,<br /> and change it once you can.</font>
    </div>
    <div style="margin-bottom: 20px;">
        <a href="{{ $activationUrl }}">{{ $activationUrl }}</a>
    </div>
    <div style="margin-bottom: 20px;">
        <div>Thank you for your trust, and Welcome !</div>
        <div>Don't hesitate to contact us on any issues whatsoever !</div>
    </div>
@endsection