@if(Request::is('user/likes'))
    <div class="grid prod-pad block-products-with-event">
        @else
            <div class="product-likes-block-carousel block-products-with-event">
                @endif
                @foreach($user->like as $like)
                    <div class="product-item grid-item" id="block-{{ $like->product->id }}">
                        <div class="product-img-block">
                            <a href="{{ route('product.url',[$like->product->upi_id, $like->product->slug]) }}" class="link_modal">
                                <img src="{{ $like->product->getMainImage('md') }}" alt="{!! $like->product->$local !!}">
                            </a>
                        </div>
                        <div class="product-name">
                            <a href="{{ route('product.url',[$like->product->upi_id, $like->product->slug]) }}" class="link_modal">
                                {!! mb_strlen($like->product->$local) > 60 ? mb_substr($like->product->$local,0,57)."..." : $like->product->$local !!}
                            </a>
                        </div>
                        <div class="product-price">{{ $like->product->price }}{{$curency_code}}</div>
                        <button type="button" class="delete-btn" data-owner-id="{{ $like->product->id }}" data-url="{{ url('user/likes/delete') }}"></button>
                        <input type="checkbox" id="{{ $like->product->id }}" name="order_item[]" value="{{ $like->product->id }}">
                        <label for="{{ $like->product->id }}"><span></span></label>
                        <span class="shadow"></span>
                    </div>
                @endforeach
            </div>