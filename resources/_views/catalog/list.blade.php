@if(isset($products) && $products)
    @foreach($products as $product)
        @if ($product->image->count())
            <div class="product-item grid-item {{ Request::ajax() ? 'ajax-load' : '' }}
            {{ $product->hidden ? 'not-active' : '' }}">
                <div class="product-img-block">
                    <a href="{{ route('product.url',[$product->upi_id, $product->slug]) }}" class="link_modal">
                        <img src="{{ $product->getMainImage('md') }}">
                    </a>
                </div>
                <div class="product-name">
                    <a href="{{ route('product.url',[$product->upi_id, $product->slug]) }}" class="link_modal">
                        {{ $product->$local }}
                    </a>
                </div>
                @if($product->hidden)
                    <div class="product-call-me">
                        @if(Auth::check() && Auth::user()->email != '')
                            <span class="call-me-if-exists-auth"
                                  data-url="{{ url('waiting-for-product-auth') }}"
                               data-owner-id="{{ $product->id }}">
                                {{ trans('product.notifyMe') }}
                            </span>
                        @else
                            <a href="#modal-wait-product" class="call-me-if-exists" data-toggle="modal"
                               data-product-image="{{ $product->getMainImage('md') }}"
                               data-product-title="{{ $product->$local }}"
                               data-owner-id="{{ $product->id }}">
                                {{ trans('product.notifyMe') }}
                            </a>
                        @endif
                            <div class="info-waiting"></div>
                    </div>
                @else
                    <div class="product-price">{{ $product->price }}{{$curency_code}}</div>
                @endif
                <span class="shadow"></span>
            </div>
        @endif
    @endforeach
@endif