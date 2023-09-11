@extends('emails.default')
@section('content')

    <h2>
        Поздравляем, {{ $name }}!
    </h2>
    <div style="text-align: left; padding: 0 25px;">
        <p>
            Мы создали для Вас аккаунт чтобы Вы могли отслеживать посылку и использовать все преимущества личного кабинета.
        </p>
        <p>
            Для входа в личный кабинет введите на <a href="{{ url('/') }}">сайте</a> следующие данные:</p>
        <p>
            email: <strong>{{ $email }}</strong>
        </p>
        <p>
            пароль: <strong>{{ $password }}</strong>
        </p>
        <p>
            &nbsp;
        </p>
        <p>
            * Пароль Вы сможете изменить в <a href="{{ url('/user') }}">личном кабинете</a> в <a href="{{ url('/user/setting') }}">настройках</a> аккаунта
        </p>
    </div>
@endsection