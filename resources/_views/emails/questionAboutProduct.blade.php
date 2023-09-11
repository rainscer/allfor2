<div>
    <a href="{{ route('product.url',[$review->product->upi_id, $review->product->slug]) }}">
        <strong>Question about product {{ $review->product->name_ru }}: </strong>
    </a>
</div>
<br>
<br>
@if($review->user_id)
    <div><strong>Question From User: </strong>{{ $review->user->name }}</div>
    <div><strong>User's e-mail: </strong>{{ $review->user->email }}</div>
@elseif($review->quest)
    <div><strong>Question From Quest: </strong>{{ $review->quest }}</div>
@endif    
<br>
<div><strong>Question: </strong>{{ $review->text }}</div>
<br>
<div>
    <a href="{{ url('user/showQa/' . $review->id) }}">Chat here</a>
</div>

