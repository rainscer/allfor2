@if(!isset($product) || !$product)
    <div class="not-found">Not Found</div>
@else
    <script src="{{ elixir("js/product.js") }}"></script>
    <div class="product">
        <div class="row">
            @if(false)
                <div class="col-sm-7">
                    @if($product->image->count())
                        <div class="top-img" id='bigfoto'>
                            <div class="top-img-block">
                                <a href="{{ $product->getMainImage() }}">
                                    <img class="top-product-images" src='{{ $product->getMainImage() }}'
                                         title="{!! $product->$local !!}" alt="{!! $product->$local !!}">
                                </a>
                            </div>
                        </div>
                        <div class="carousel dop-images" id='smallfoto'>
                            @foreach($product->image as $image)
                                <div class="item dop-img"><a class="dop-img-href"
                                                             href="{{ image_asset($image->image_url,'lg') }}"
                                                             title="{!! $product->$local !!}"
                                                             data-standard="{{ image_asset($image->image_url,'lg') }}"><img
                                                class="dop-product-images"
                                                src="{{ image_asset($image->image_url,'lg') }} "
                                                alt="{!! $product->$local !!}"></a></div>
                            @endforeach
                        </div>
                    @else
                        <div class="top-img" id='bigfoto'>
                            <div class="top-img-block">
                                <img class="top-product-images" src='{{ asset('/images/no_image.png') }}'
                                     title="{!! $product->$local !!}" alt="{!! $product->$local !!}">
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            <div class="col-sm-12">
                <div class="product-name title-ajax">{!! $product->$local !!}</div>
                <div class="product-likes-views">
                    <span>{{ trans('product.views') }}</span><span>{{ $product->views + $product->real_views }}</span>
                    <span>{{ trans('product.sold') }}</span><span>{{ $product->sold }}</span>
                </div>
                {{--<div class="product-likes-views">
                    <span>{{ trans('cart.delivery') }}</span>
                    <span>{{ $product->checkDeliveryType() }}</span>
                </div>--}}
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
                        @if(false)
                            @if(!$product->hidden)
                                <a href="{{ url('cart') }}" target="_blank" data-url="{{ url('cart/add') }}"
                                   data-owner-id="{{ $product->id }}" class="ajaxAddProductToCart take">
                                    {{ trans('product.take') }}
                                </a>
                            @endif
                            <div class="alert alert-success message-box" role="alert">
                                <div class="message"></div>
                            </div>
                            <a href="{{ url('addlike/product') }}" data-owner-id="{{ $product->id }}"
                               class="ajaxLikeProduct want">
                                {{ trans('product.want') }}
                            </a>
                            <a href="#" class="show-want-free-block want-green" data-popup="#free_popup">
                                {{ trans('product.free') }}
                            </a>
                        @endif
                        <div class="to-cart-or-cont">
                            <div class="container-fluid">
                                <div class="col-xs-6 padding-sm-5">
                                    <img src="{{ asset('/images/check.png') }}">
                                    <div class="text">{{ trans('product.productAddedToCart') }}</div>
                                    <a href="{{ url('/cart') }}" class="btn">{{ trans('product.checkOut') }}</a>
                                </div>

                                <div class="col-xs-6 padding-sm-5">
                                    <img src="{{ asset('/images/korz.png') }}">
                                    <div class="text">{{ trans('product.totalInCart') }}: <span
                                                class="total-cart-in-pp bold">$200</span> {{--{{ $curency_code }}--}}
                                    </div>
                                    <button class="btn continue-ship">{{ trans('product.continueBuy') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--                <div class="social-product">--}}
                {{--                    <a class="fb-icon" href="https://www.facebook.com/sharer.php?u={{ Request::url() }}" target="_blank">--}}
                {{--                        Share on facebook--}}
                {{--                    </a>--}}
                {{--                </div>--}}
            </div>
        </div>
        {{--        <div class="product-del-pay-desc row">--}}
        {{--            <div class="col-md-4" style="padding: 0 40px;">--}}
        {{--                <div>--}}
        {{--                    <span class="guard"></span>--}}
        {{--                </div>--}}
        {{--                <span>{{ trans('product.quard') }}</span>--}}
        {{--            </div>--}}
        {{--            <div class="col-md-4" style="padding: 0 60px;">--}}
        {{--                <div>--}}
        {{--                    <span class="prepaid"></span>--}}
        {{--                </div>--}}
        {{--                <span>{{ trans('product.bestPrice') }}</span>--}}
        {{--            </div>--}}
        {{--            <div class="col-md-4" style="padding: 0 60px;">--}}
        {{--                <div>--}}
        {{--                    <span class="delivery-city"></span>--}}
        {{--                </div>--}}
        {{--                <span>{{ trans('product.productFromFactory') }}</span>--}}
        {{--            </div>--}}
        {{--        </div>--}}

        {{--        <div class="description-title">{{ trans('product.description') }}</div>--}}
        <div class="description img_product_all">{!! str_replace("\n", '<br />',$product->getDescription()) !!}</div>

        @if(false)
            @if($product->image->count())
                <div class="img_product_all">
                    <ul>
                        @foreach($product->image as $image)
                            <li>
                                <img class="full-product-images" src="{{ image_asset($image->image_url) }} "
                                     alt="{!! $product->$local !!}">
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif

        <h3 class="centered">{{ trans('user.reviews') }}</h3>
        @include('product.formReview')
        <div class="row review_block review">
            @include('product.reviewItem')
        </div>
        @if(false)
            <div class="dop-product" data-href="{{ url('product/dop-products') }}">
                @include('product.dopProducts')
            </div>
        @endif
    </div>

    <div id="custom-button" class="left color-grey">
        <a href="#" data-dismiss="modal" aria-hidden="true">
            <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
            BACK
        </a>
    </div>

    <div id="custom-button" class="right color-pink">
        <a href="{{ url('cart') }}" target="_blank" data-url="{{ url('cart/add') }}"
           data-owner-id="{{ $product->id }}" class="ajaxAddProductToCart take">
            ADD TO CART {!! $product->price !!}$<span id="cart-sum"></span>
            <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
        </a>
    </div>

    <br /><br /><br /><br /><br /><br /><br />

    <style>

        .img_product_all img {
            width: 100%;
        }
    </style>

    <script>
        visualViewport.addEventListener('resize', () => {
            document.documentElement.style.setProperty('--viewport-height', `${visualViewport.height}px`);
        });
        if (visualViewport.height < 1000) {
            document.documentElement.style.setProperty('--viewport-height', `${visualViewport.height}px`);
        }
    </script>

    <style>
        #custom-button a {
            color: #FFFFFF;
        }

        #custom-button a:hover {
            color: #FFFFFF;
        }

        #custom-button a:active {
            color: #FFFFFF;
        }

        #custom-button.right {
            right: 10px;
        }

        #custom-button.left {
            left: 10px;
        }

        #custom-button.color-pink {
            background-color: #e75f79;
        }

        #custom-button.color-grey {
            background-color: #9d9d9d;
        }

        #custom-button {
            position: fixed;
            -webkit-backface-visibility: hidden;
            top: calc(var(--viewport-height) - 50px);
            color: #FFF;
            padding: 10px 30px;
            height: 42px;
            font-weight: bold;
            z-index: 11202;
            display: block !important;
            opacity: 0.9;
            -webkit-border-radius: 10px;
            border-radius: 10px;
        }

        :root {
            --viewport-height: 100%;
        }

        .modal{
            height: calc(var(--viewport-height) - 1px) !important;
        }
        html {
            height: 100vh !important;
        }

        body {
            height: calc(var(--viewport-height) - 1px) !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>

@endif