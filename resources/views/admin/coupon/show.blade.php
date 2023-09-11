@extends('admin.layout.default')

@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Coupon ({{ $coupon->code }})</div>
        <div class="panel-body">
            <div class="col-xs-12 form-horizontal">

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">ID заказа</th>
                        <th class="text-center">Статус</th>
                        <th class="text-center">Трекер</th>
                        <th class="text-center">Пользователь</th>
                        <th class="text-center">Дата заказа</th>
                    </tr>
                    </thead>
                    <tbody class="list text-center">
                        @foreach($coupon->orders as $order)
                        <tr>
                            <td><a href="{{ url('administrator/orders/edit/' . $order->id) }}">{{ $order->id }}</a></td>
                            <td>{{ $order->order_status }}</td>
                            <td>{{ $order->tracking_number ? $order->tracking_number : '-' }}</td>
                            <td>{!! $order->user ? '<a href="' . url('administrator/users/' . $order->user_id) . '">' . $order->user->getFullName() . '</a>' : '-' !!}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div>
                    <a href="{{ route('coupon.index') }}" class="btn btn-danger">Назад</a>
                </div>

            </div>
        </div>
    </div>
@endsection

