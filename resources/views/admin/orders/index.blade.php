@extends('admin.layout.default')
@section('content')
    @if (session()->has('success_update'))
        <div class="alert alert-success" role="alert">Заказ успешно сохранен!</div>
    @endif
    {{-- */ $curency = app('Setting')->getSettingValue('curency_ru', 1) /* --}}
    <div class="panel panel-success">
        <div class="panel-heading clearfix">
            {!! Form::open(['route' => 'order.search', 'class' => 'search-order-form'])!!}
            Заказы
            @if(isset($status))
                <span class="badge">{{ $status }}</span>
            @endif
            {!! Form::text('search', isset($search) ? $search : '', ['placeholder'=>'Поиск','class'=>'search-input-product-admin'])!!}
            {!! Form::submit('Поиск', ['class'=>'btn btn-primary'])!!}
            <a href="{{ route('orders') }}" class="btn btn-primary">Сброс</a>
            {!! Form::close()!!}
            <button type="button" class="btn btn-success navbar-right delete-admin-btn"
                    data-owner-id="{{ url('administrator/orders/delete') }}">Удалить</button></div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="order_table">
                    <tr>
                        <th>Номер</th>
                        <th>Покупатель</th>
                        <th>Товар</th>
                    </tr>
                    @foreach($orders as $order)
                        {{--  */  $order->contacts = unserialize($order->contacts);
                                      $order->contacts = (array)$order->contacts;
                                      $sendDate = $order->created_at->copy(); /* --}}
                        <tr id="item-{{ $order->id }}">
                            <td
                                    @if(($order->api == 1) && ($order->order_status == \App\Models\Order::STATUS_PAID))
                                    class="api_send"
                                    @elseif(($order->api == 0) && ($order->order_status == \App\Models\Order::STATUS_PAID))
                                    class="api_not_send"
                                    @endif
                                    style="width: 90px;"
                            ><a href="{{ url('administrator/orders/edit/'.$order->id) }}">{{ $order->id }}</a>
                                <input type="checkbox" class="delete-box-admin" id="order-box-{{ $order->id }}"
                                       name="order_item[]" value="{{ $order->id }}">
                                <label for="order-box-{{ $order->id }}"><span></span></label>
                                @if($order->new)
                                    <span class="badge">new</span>
                                @endif
                            </td>
                            <td>
                                <p><strong>{{ $order->created_at->format('d.m.Y H:i') }}</strong></p>
                                @if($status == \App\Models\Order::STATUS_PAID)
                                    <p><strong>Ожидается:<br>{{ $sendDate->addDays(20)->format('d.m.Y') .
                                ' - ' . $sendDate->addDays(10)->format('d.m.Y') }}</strong></p>
                                @endif
                                @foreach ($order->contactFields as $field)
                                    <p>{!! isset($order->contacts[$field]) ? $order->contacts[$field] : ''!!}</p>
                                @endforeach
                                <p>{!! isset($order->payment_id) ? 'LP: ' . $order->payment_id : ''!!}</p>
                                <p>{!! $order->tracking_number ? 'TRACK: ' . $order->tracking_number : ''!!}</p>
                            </td>
                            <td>
                                <table class="table table-striped table-bordered">
                                    @foreach($order->order_item as $order_item)
                                        <tr>
                                            <td class="order_item"><a href="{{ url('upi/'.$order_item->product_upi) }}" target="_blank">
                                                    {{ $order_item->product_name }}</a></td>
                                            <td class="upi" width="150">Upi: {{ $order_item->product_upi }}</td>
                                            <td class="quantity" width="150">{{ $order_item->product_quantity.' шт'.' ($' .
                                            $order_item->product_price./*' '.$curency_code.*/')' }}</td>
                                            <td class="price" width="150">{{ '$' . $order_item->product_quantity * $order_item->product_price }} ({{ $order_item->product ? ($order_item->product_quantity *
                                            $order_item->product->weight * 0.015) : 'Product was deleted, can not be calculated' }})</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><strong>Доставка:</strong></td>
                                        <td><strong>${{ $order->delivery_cost /*. ' ' . $curency_code*/ }}</strong></td>
                                    </tr>
                                    @if($order->coupon)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><strong>Купон:</strong></td>
                                        <td><strong>-${{ $order->coupon->amount }}</strong> (<a href="{{ url('administrator/coupon/show', $order->coupon->id) }}">{{ $order->coupon->code }}</a>)</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><strong>Итого:</strong></td>
                                        <td><strong>${{ $order->order_total + $order->delivery_cost /*. ' ' . $curency_code*/ }}</strong></td>
                                    </tr>
                                </table>
                                @if ($order->comment)
                                    <div>
                                        <label>Комментарий:</label>
                                        <div>
                                            {{ nl2br($order->comment) }}
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @if ($orders instanceof \Illuminate\Pagination\AbstractPaginator)
                {!! $orders->render()  !!}
            @endif
        </div>
    </div>
@endsection