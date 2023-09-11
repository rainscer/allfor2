@extends('emails.default')
@section('content')
    <div>
        <font color="#565656" size="4"><strong>{{ $name }}!</strong></font>
    </div>
    <div>
        <font color="#565656" size="4">Спешим уведомить вас о поступлении товара
            <a href="{{ route('product.url',[$product->upi_id, $product->slug]) }}"><strong>{{ $product->name_ru }}</strong></a>!
        </font>
    </div>
@endsection