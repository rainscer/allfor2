@extends('emails.default')
@section('content')
    <div>
        <font color="#565656" size="4"><strong>{{ $name }}!</strong></font>
    </div>
    <div>
        <font color="#565656" size="4">{{ trans('product.waitingGoods') }}
            <a href="{{ route('product.url',[$product->upi_id, $product->slug]) }}"><strong>{{ $product->name_en }}</strong></a>!
        </font>
    </div>
@endsection