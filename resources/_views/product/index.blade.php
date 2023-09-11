@include('modal')

@if(!isset($product) || !$product)
    <div class="not-found">Not Found</div>
@else
    <script src="{{ elixir("js/product.js") }}"></script>
    <div class="product">
        <div class="row">
            <div class="col-sm-7">
                @if($product->image->count())
                    <div class="top-img" id='bigfoto'>
                        <div class="top-img-block easyzoom easyzoom--adjacent">
                            <a href="{{ $product->getMainImage() }}" class="zoom">
                                <img class="top-product-images" src='{{ $product->getMainImage() }}' title="{!! $product->$local !!}" alt="{!! $product->$local !!}">
                            </a>
                        </div>
                    </div>
                    <div class="carousel dop-images" id='smallfoto'>
                        @foreach($product->image as $image)
                            <div class="item dop-img"><a class="dop-img-href" href="{{ image_asset($image->image_url,'lg') }}" title="{!! $product->$local !!}" data-standard="{{ image_asset($image->image_url,'lg') }}"><img class="dop-product-images" src="{{ image_asset($image->image_url,'lg') }} " alt="{!! $product->$local !!}"></a></div>
                        @endforeach
                    </div>
                @else
                    <div class="top-img" id='bigfoto'>
                        <div class="top-img-block">
                            <img class="top-product-images" src='{{ asset('/images/no_image.png') }}' title="{!! $product->$local !!}" alt="{!! $product->$local !!}">
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-sm-5">
                <div class="product-name title-ajax">{!! $product->$local !!}</div>
                <div>
                    <span class="product-price">{!! $product->price !!}</span>
                    <span class="product-currency">{{ $curency_code }}</span>
                </div>
                <div class="product-likes-views">
                    <span>{{ trans('product.views') }}</span><span>{{ $product->views + $product->real_views }}</span>
                    <span>{{ trans('product.sold') }}</span><span>{{ $product->sold }}</span>
                </div>
                <div class="product-likes-views">
                    <span>{{ trans('cart.delivery') }}</span>
                    <span>{{ $product->checkDeliveryType() }}</span>
                </div>
                <div class="product-attributtes">
                    @if($product->attribute)
                        @foreach($product->attribute as $attribute)
                            <div class="form-group">
                                {!! Form::label('attribute',$attribute->attribute_name->name) !!}
                                <select name="attribute" class="attribute form-control">
                                    @foreach($attribute->attribute_name->products_attributes as $attribut)
                                        @if($attribut->product)
                                            <option value="{{ route('product.url',[$attribut->product->upi_id, $attribut->product->slug]) }}"
                                                    {{ $product->upi_id == $attribut->upi_id ? 'selected' : '' }}>
                                                {{ $attribut->attribute_value_name  }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div>
                    <div class="product-quantity">
                        @if(!$product->hidden)
                            <span>{{ trans('product.quantity') }}:</span>
                            <input type="number" min="1" name="quantity" value="1">
                        @endif
                        <span class="span-upi_id">{{ trans('product.numberOfProduct') }}:  {{ $product->upi_id }}</span>
                    </div>
                    <div class="buy-buttons clearfix">
                        @if(!$product->hidden)
                            <a href="{{ url('cart') }}" target="_blank" data-url="{{ url('cart/add') }}" data-owner-id="{{ $product->id }}" class="ajaxAddProductToCart take">
                                {{ trans('product.take') }}!
                            </a>
                        @endif
                        <div class="alert alert-success message-box" role="alert">
                            <div class="message"></div>
                        </div>
                        <a href="{{ url('addlike/product') }}" data-owner-id="{{ $product->id }}" class="ajaxLikeProduct want">
                            {{ trans('product.want') }}!
                        </a>
                        <a href="#want-free" class="show-want-free-block want-green">
                            {{ trans('product.want') }}<br>{{ trans('product.free') }}!
                        </a>
                        <div class="to-cart-or-cont">
                            <div class="container-fluid">
                                <div class="col-xs-6 padding-sm-5">
                                    <img src="{{ asset('/images/check.png') }}">
                                    <div class="text">{{ trans('product.productAddedToCart') }}</div>
                                    <a href="{{ url('/cart') }}" class="btn">{{ trans('product.checkOut') }}</a>
                                </div>

                                <div class="col-xs-6 padding-sm-5">
                                    <img src="{{ asset('/images/korz.png') }}">
                                    <div class="text">{{ trans('product.totalInCart') }}: <span class="total-cart-in-pp bold">200</span> {{ $curency_code }}</div>
                                    <button class="btn continue-ship">{{ trans('product.continueBuy') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="social-product">
                    <a class="fb-icon" href="https://www.facebook.com/sharer.php?u={{ Request::url() }}" target="_blank">
                        Share on facebook
                    </a>
                </div>
            </div>
        </div>
        <div class="product-del-pay-desc row">
            <div class="col-md-4" style="padding: 0 40px;">
                <div>
                    <span class="guard"></span>
                </div>
                <span>{{ trans('product.quard') }}</span>
            </div>
            <div class="col-md-4" style="padding: 0 60px;">
                <div>
                    <span class="prepaid"></span>
                </div>
                <span>{{ trans('product.bestPrice') }}</span>
            </div>
            <div class="col-md-4" style="padding: 0 60px;">
                <div>
                    <span class="delivery-city"></span>
                </div>
                <span>{{ trans('product.productFromFactory') }}</span>
            </div>
        </div>

        <div class="description-title">{{ trans('product.description') }}</div>
        <div class="description">{!! str_replace("\n", '<br />',$product->$local_description) !!}</div>

        @if($product->image->count())
            <div class="img_product_all">
                <ul>
                    @foreach($product->image as $image)
                        <li>
                            <img class="full-product-images" src="{{ image_asset($image->image_url,'lg') }} " alt="{!! $product->$local !!}">
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h3 class="centered">Вопросы о товаре</h3>
        @include('product.formQA')
        <div class="row review_block qa">
            @include('product.qaItem')
        </div>
        <h3 class="centered">{{ trans('user.reviews') }}</h3>
            @include('product.formReview')
        <div class="row review_block review">
            @include('product.reviewItem')
        </div>
        <div class="dop-product" data-href="{{ url('product/dop-products') }}">
            @include('product.dopProducts')
        </div>
    </div>

    <div id="want-free">
        <img src="{{ asset('/images/want-free.png') }}" style="width: 100%;">
    </div>
    <style>
        @media (max-width: 767px) {
            #want-free {
                top: 850px
            }
        }
    </style>

@endif
{{--@if(isset($friends))
    <p>Вконтакте:</p>
    <div class="vk_post">
        <p>Ваши друзья:</p>
        <div class="form-group">
            <select id="friends" class="form-control" name="friends">
                <option value="{{ Auth::user()->social_id }}">Выберите друга или Ваш профиль по умолчанию</option>
                @foreach($friends as $friend)
                    <option value="{{ $friend->id }}">
                        {{ $friend->first_name }}&nbsp;{{ $friend->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <textarea rows="8" class="message-vk form-control"></textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-default postVk" data-owner-id="1">Сделать пост</button>
        </div>
        <div class="alert alert-success hide" role="alert"></div>
    </div>
@endif
@if(Session::has('user.facebook'))
    <div class="fb_post">
        <p>Facebook:</p>
        {{--<p>Ваши друзья:</p>
        <div class="form-group">
            <select id="friends" class="form-control" name="friends">
                <option value="{{ Auth::user()->social_id }}">Выберите друга или Ваш профиль по умолчанию</option>
                @foreach($friends as $friend)
                    <option value="{{ $friend->id }}">
                        {{ $friend->first_name }}&nbsp;{{ $friend->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <textarea rows="8" class="message-fb form-control"></textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-default postfb" data-owner-id="1">Сделать пост</button>
        </div>
        <div class="alert alert-success hide" role="alert"></div>
    </div>
@endif
--}}