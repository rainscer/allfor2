<div class="panel panel-success">
    <div class="panel-heading">Заказы <a href="#" class="toggle-table btn btn-success">Свернуть/развернуть</a></div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="order_table">
                <tr>
                    <th>Номер, Дата, Статус</th>
                    <th>Товар</th>
                </tr>
                @foreach($orders as $order)
                    <tr id="item-{{ $order->id }}">
                        <td>
                            <a href="{{ url('administrator/orders/edit/'.$order->id) }}">{{ $order->id }}</a>
                            <p>{{ $order->created_at->format('d-m-Y H:i') }}</p>
                            <p><strong>{{ $order->order_status }}</strong></p>
                        </td>
                        <td>
                            <table class="table table-striped table-bordered">
                                @foreach($order->order_item as $order_item)
                                    <tr>
                                        <td class="order_item"><a href="{{ url('upi/'.$order_item->product_upi) }}" target="_blank">
                                                {{ $order_item->product_name }}</a></td>
                                        <td class="upi">Upi: {{ $order_item->product_upi }}</td>
                                        <td class="quantity">{{ $order_item->product_quantity.' шт'.' (' .
                                            $order_item->product_price.' '.$curency_code.')' }}</td>
                                        <td class="price">{{ $order_item->product_quantity * $order_item->product_price . ' ' .
                                            $curency_code }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Доставка:</strong></td>
                                    <td><strong>{{ $order->delivery_cost . ' ' . $curency_code }}</strong></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Итого:</strong></td>
                                    <td><strong>{{ $order->order_total + $order->delivery_cost . ' ' . $curency_code }}</strong></td>
                                </tr>
                            </table>
                        </td>
                        @endforeach
                    </tr>
            </table>
        </div>
    </div>
</div>