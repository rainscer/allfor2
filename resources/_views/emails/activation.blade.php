@extends('emails.default')
@section('content')
    <img src="{{ asset('/images/user_email.png') }}" width="170" height="170">
    <div>
        <font color="#565656" size="4">Поздравляем, <strong>{{ $username }}</strong></font>
    </div>
    <div>
        <font color="#565656" size="4">Вы успешно зарегистрировались на <strong>allfor2.com</strong></font>
    </div>
    <div style="margin-top: 20px;">
        <font color="#565656" size="3">Пожайлуста активируйте Вашу учётную запись, перейдя по ссылке</font>
    </div>
    <div style="margin-bottom: 20px;">
        <a href="{{ $activationUrl }}">{{ $activationUrl }}</a>
    </div>
@endsection