@extends('emails.default')
@section('content')
    <div>
        <font color="#565656" size="4"><strong>{{ $user->getFullName() }}!</strong></font>
    </div>
    <div>
        <font color="#565656" size="4">У Вас одно непрочитанное сообщение в <a href="{{ url('messages') }}">личном кабинете</a> <strong>allfor2.com</strong></font>
    </div>
@endsection