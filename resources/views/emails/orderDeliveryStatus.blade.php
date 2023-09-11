@extends('emails.default')
@section('content')
@if($delivered)
    <p>
        <font color="#565656" size="4">Спешим оповестить вас что Заказа №{{ $order->id }} на allfor2.com доставлен на почту!</font>
    </p>
@else
    <p>
        <font color="#565656" size="4">Спешим оповестить вас что статус Вашего заказа №{{ $order->id }} на allfor2.com изменился!</font>
    </p>
@endif
<div style="padding: 10px;">
    <p><font color="#565656" size="2"><strong>Сообщение почты:</strong><br>
        {{ $order->delivery_description }}</font></p>
</div>

@endsection