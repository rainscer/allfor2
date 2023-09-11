<div>
    <strong>New Order {{ $order->order_number }}: </strong>
</div>
<br>
<br>
<div><strong>Order Total: </strong>{{ $order->order_total }}</div>
@if($order->user_id)
    <div><strong>User: </strong>{{ $order->user->name }}</div>
    <div><strong>User's e-mail: </strong>{{ $order->user->email }}</div>
@endif
<br>
<div>
    <a href="{{ url('administrator/orders/edit/' . $order->id) }}">Order here</a>
</div>

