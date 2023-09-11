@extends('emails.default')
@section('content')
@if(isset($admin))
    <p>
        <font color="#565656" size="4">Новый заказ №{{ $order->id }} на allfor2.com</font>
    </p>
@else
    <p>
        <font color="#565656" size="4">Спасибо за Ваш заказ №{{ $order->id }} на <a href="{{ url('/') }}" target="_blank">allfor2.com</a></font>
    </p>
@endif
<div style="padding: 10px;">
    <p><font color="#565656" size="2"><strong>Ваши товары:</strong></font></p>
    <table cellpadding="7" align="center" cellspacing="0" border="0" style="text-align: left;" width="100%">
        <tr style="background-color: #EEEEEE;">
            <th style='border: 1px solid #757575;'><font color="#565656" size="2">Название товара</font></th>
            <th style='border: 1px solid #757575;'><font color="#565656" size="2">Количество (шт.)</font></th>
            <th style='border: 1px solid #757575;'><font color="#565656" size="2">Цена (${{--{{ $curency_code }}--}}.)</font></th>
        </tr>
        @foreach($order->order_item as $order_item)
            <tr>
                <td style='border: 1px solid #757575;'><font color="#565656" size="2">
                        {{ $order_item->product_name }}</font></td>
                <td style='border: 1px solid #757575; text-align: center;'><font color="#565656" size="2">{{ $order_item->product_quantity }}</font></td>
                <td style='border: 1px solid #757575; text-align: center;'><font color="#565656" size="2">
                        {{ $order_item->product_price*$order_item->product_quantity }}</font></td>
            </tr>
        @endforeach
        <tr>
            <td style="text-align: right; border-top: 1px solid #757575;border-right: 1px solid #757575;"><strong><font color="#565656" size="2">Доставка:</font></strong></td>
            <td style='border: 1px solid #757575; text-align: center;'><strong><font color="#565656" size="2"></font></strong></td>
            <td style='border: 1px solid #757575; text-align: center;'><strong><font color="#565656" size="2">{{ $order->delivery_cost }}</font></strong></td>
        </tr>
        <tr>
            <td style="text-align: right; border-top: 0; border-right: 1px solid #757575;"><strong><font color="#565656" size="2">Итого:</font></strong></td>
            <td style='border: 1px solid #757575; text-align: center;'><strong><font color="#565656" size="2">{{ collect($order->order_item)->sum('product_quantity') }}</font></strong></td>
            <td style='border: 1px solid #757575; text-align: center;'><strong><font color="#565656" size="2">{{ $order->order_total + $order->delivery_cost }}</font></strong></td>
        </tr>
    </table>

    <p>
    </p>
    <p style="margin: 25px 0;">
        <font color="#565656" size="2"><strong>Детали доставки:</strong></font>
    </p>
    <table cellpadding="10" cellspacing="0" border="0" style="text-align: left;" width="100%">
        <tr>
            <td width="130"><font color="#565656" size="2">Имя получателя:</font></td>
            <td><font color="#565656" size="2">
                    {{ $order->d_user_name }}</font>
            </td>
        </tr>
        <tr>
            <td width="130" style="vertical-align: top;"><div style="margin: 2px 0;"><font color="#565656" size="2">Адрес доставки</font></div></td>
            <td>
                <div style="margin: 2px 0;"><font color="#565656" size="2">{{ $order->d_user_region }}</font></div>
                <div style="margin: 2px 0;"><font color="#565656" size="2">{{ $order->d_user_city }}</font></div>
                <div style="margin: 2px 0;"><font color="#565656" size="2">{{ $order->d_user_address }}</font></div>
                <div style="margin: 2px 0;"><font color="#565656" size="2">{{ $order->d_user_index }}</font></div>
                <div style="margin: 2px 0;"><font color="#565656" size="2">{{ $order->d_user_phone }}</font></div>
            </td>
        </tr>
    </table>
</div>
@if(!isset($admin))
    <p style="text-align: center; margin: 40px;">
        <a href="{{ url('/user') }}" style="font-size: 18px; padding: 15px 30px; color: #fff; background: #ADD157; text-decoration: none;">
            Перейти в личный кабинет
        </a>
    </p>
@endif
@endsection