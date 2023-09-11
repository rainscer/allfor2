@extends('layout.default')

@section('content')
    <script src="//static.liqpay.ua/libjs/checkout.js" async></script>
    @if(isset($cart_products))
        <div class="basket-page">

            <div class="basket-page-title">{{ trans('cart.yourCart') }}:</div>
            <div class="cart-body">
                <table id="order" class="basket-table">
                    <tr><td></td><td></td>
                        <td class="bas-prod-cost hidden-xs">{{ trans('cart.price') }}:</td>
                        <td class="bas-prod-count hidden-xs">{{ trans('cart.qu-ty') }}:</td>
                        <td class="bas-prod-cost hidden-xs">{{ trans('cart.sum') }}:</td>
                        <td></td>
                    </tr>
                    {{-- */ $total_sum = 0; /* --}}
                    {{-- */ $total_count = 0; /* --}}
                    {{-- */ $total_weight = 0; /* --}}
                    @foreach($cart_products as $cart_product)
                        <tr data-id="{{ $cart_product->id }}" id="pr{{ $cart_product->id }}" class="product-cart-item">
                            <td class="bas-prod-img">
                                <div class="product-basket-img"><img src="{{ $cart_product->getMainImage('md') }}"></div>
                            </td>
                            <td class="padding-20-mobile">
                                <div class="product-basket-detail">
                                    <div class="product-basket-title"><a href="{{ route('product.url',[$cart_product->upi_id, $cart_product->slug]) }}" class="link_modal">{!! $cart_product->$local !!}</a></div>
                                    <div class="product-basket-weight hidden-xs">
                                        <img src="{{ asset('/images/weight.png') }}"> {{ trans('cart.weight') }}:
                                        <span class="product-weight">{{ $cart_product->weight * $quantity[$cart_product->id] }}</span>г</div>
                                    <input type="hidden" class="product-weight-by1" value = "{{ $cart_product->weight }}">

                                    <div class="hidden-sm hidden-lg hidden-md">
                                        <table class="table mobile-product-info">
                                            <tr>
                                                <td>
                                                    {{ trans('cart.weight') }}:
                                                </td>
                                                <td class="align-right">
                                                    <div class="product-basket-weight">
                                                        <span class="product-weight">{{ $cart_product->weight * $quantity[$cart_product->id] }}</span>г
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{ trans('cart.price') }}:
                                                </td>
                                                <td class="align-right">
                                                    <div class="product-basket-cost">
                                                        $<span class="prod_price">{!! $cart_product->price !!}</span> {{--{{$curency_code}}--}}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{ trans('cart.qu-ty') }}:
                                                </td>
                                                <td class="align-right">
                                                    <input type="text" value="{{ $quantity[$cart_product->id] }}" class="product-basket-count hidden-sm hidden-lg hidden-md">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{ trans('cart.sum') }}:
                                                </td>
                                                <td class="align-right">
                                                    <div class="product-basket-cost">
                                                        $<span class="prod_sum">{!! $cart_product->price * $quantity[$cart_product->id] !!}</span> {{--{{$curency_code}}--}}
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>
                            </td>
                            <td class="bas-prod-cost hidden-xs">
                                <div class="product-basket-cost"><span class="prod_price">${!! $cart_product->price !!}</span> {{--{{$curency_code}}--}}</div>
                            </td>
                            <td class="bas-prod-count hidden-xs">
                                <input type="text" value="{{ $quantity[$cart_product->id] }}" class="product-basket-count hidden-xs">
                            </td>
                            <td class="bas-prod-cost hidden-xs">
                                <div class="product-basket-cost">$<span class="prod_sum">{!! $cart_product->price * $quantity[$cart_product->id] !!}</span> {{--{{$curency_code}}--}}</div>
                            </td>
                            <td style="text-align: right;" class="delete-icon-cart">
                                <a href="{{ url('cart/delete') }}" data-owner-id="{{ $cart_product->id }}" class="ajaxActionDeleteProduct"></a>
                            </td>
                        </tr>
                        {{-- */ $total_weight += $cart_product->weight * $quantity[$cart_product->id] /* --}}
                        {{-- */ $total_count += $quantity[$cart_product->id] /* --}}
                        {{-- */ $total_sum += $cart_product->price * $quantity[$cart_product->id] /* --}}
                    @endforeach
                </table>
                {{-- */

                        $deliveryCost = 0;
                        $packing_price = $deliveryInfo->packing_price;
                        $ua_deliv_price = $deliveryInfo->ua_deliv_price;
                        $coef_delivery = $deliveryInfo->coef_delivery;

                        // get delivery cost as total with all delivery parameters
                        // $total_sum += ($deliveryCost + $packing_price + $ua_deliv_price) * $coef_delivery;

                        // get delivery cost as collection from delivery parameters
                        //$total_sum += $deliveryInfo->ua_deliv_price;
                        $total_sum += ceil($total_weight / 100) * 2;

                /* --}}
                <table class="table tb-without-border deliv-cost">
                    <tr class="product-basket-deliv">
                        <td class="width-50 hidden-xs" rowspan="3">
                            <div class="weight-block">
                                {{-- */ $count_boxes = $coef_delivery /* --}}
                                <img src="{{ asset('/images/cart_icon.png') }}">
                                <span class="bold weight-info">
                                {{ trans('cart.orderWeight') }}
                                    <span class="total-weight">{{ round($total_weight/1000, 2) }}</span> кг
                            </span>
                                <span class="sum">=</span>
                                <div class="hidden img-for-clone">
                                    <img src="{{ asset('/images/box.png') }}">
                                </div>
                                <span class="delivery-boxes-block">
                                @while($count_boxes)
                                        <img src="{{ asset('/images/box.png') }}">
                                        {{-- */  $count_boxes-- /* --}}
                                    @endwhile
                                    </span>
                                {{--<span class="sum">=</span>
                            <span class="deliv-price-total-weight">
                                {{ ($ua_deliv_price + $packing_price) * $coef_delivery }}
                            </span> {{ $curency_code }}--}}
                            </div>
                            <div class="delivery-text">
                                {{ trans('cart.cartWeightDetailText') }}
                            </div>
                        </td>
                        <td></td>
                        <td class="align-right product-basket-cost" colspan="2"></td>
                    </tr>
                    <tr class="product-basket-deliv">
                        <td>{{ trans('cart.deliveryInUkraine') }}</td>
                        <td class="align-right product-basket-cost" colspan="2"><span class="ua-deliv-price">
                                {{--{{ $ua_deliv_price }}--}}${{ ceil($total_weight / 100) * 2 }}
                            </span> <span style="color: red;">*</span> {{--{{ $curency_code }}--}}
                        </td>
                    </tr>
                    {{--<tr class="product-basket-deliv">
                        <td>{{ trans('cart.weight') }} (<span class="total-weight">{{ round($total_weight/1000, 2) }}</span> кг)</td>
                        <td class="align-right product-basket-cost"><span class="deliv-price-total-weight">{{ ($ua_deliv_price + $packing_price) * $coef_delivery }}
                            </span> {{ $curency_code }}
                        </td>
                    </tr>--}}

                    <tr class="basket-page-total-price bold">
                        <td class="width-40 hidden-xs"></td>
                        <td>{{ trans('cart.total') }}:</td>
                        <td class="align-right product-basket-cost"><span class="total-price">${{  $total_sum }}
                            </span> {{--{{ $curency_code }}--}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">* <span style="text-decoration: underline">цена доставки $2 за 100г веса <span class="can-order">{{ $total_weight % 100 ? ', так же Вы можете заказать еще товар весом до ' . (100 - $total_weight % 100) . 'г и сумма доставки не изменится' : '' }}</span></span></td>
                    </tr>
                </table>

                <div class="hidden-sm hidden-lg hidden-md">
                    <div class="delivery-mobile-info">
                        <div class="weight-block">
                            {{-- */
                            $count_boxes = $coef_delivery /* --}}
                            <img src="{{ asset('/images/cart_icon.png') }}">
                            <span class="bold weight-info">
                                {{ trans('cart.orderWeight') }}
                                <span class="total-weight">{{ round($total_weight/1000, 2) }}</span> кг
                            </span>
                            <span class="sum">=</span>
                            <span class="delivery-boxes-block">
                                @while($count_boxes)
                                    <img src="{{ asset('/images/box.png') }}">
                                    {{-- */  $count_boxes-- /* --}}
                                @endwhile
                                    </span>
                            <span class="sum">=</span>
                            <span class="deliv-price-total-weight">
                                ${{ ($ua_deliv_price + $packing_price) * $coef_delivery }}
                            </span> {{--{{ $curency_code }}--}}
                        </div>
                        <div class="delivery-text">
                            {{ trans('cart.cartWeightDetailText') }}
                        </div>
                    </div>
                </div>

                @include('order.delivery')

                <div class="pay-block centered">
                <!--div class="pay-order">{{ trans('cart.pay') }}</div-->
                    <div>
                        {{--<input type="image" src="{{ asset('/images/wayforpay_pay.png') }}" class="btn pay-btn" data-payment="wayforpay">--}}
                        {{--<input type="image" src="{{ asset('/images/nal.png') }}" class="btn pay-btn" data-payment="liqpay">--}}
                        {{--<input type="image" src="{{ asset('/images/privat24.png') }}" class="btn pay-btn" data-payment="liqpay">--}}
                        {{--<input type="image" src="{{ asset('/images/card.png') }}" class="btn pay-btn" data-payment="liqpay">--}}
                        {{--<input type="image" src="{{ asset('/images/pp.png') }}" class="btn pay-btn" data-payment="paypal">--}}
                    </div>
                    {{--
                    <div class="how-to-pay-block">
                        <a href="#" class="link">{{ trans('cart.howToPay') }}?</a>
                    </div>
                    <div class="pay-items centered">
                        <input type="image" src="{{ asset('/images/pay_buttons.png') }}" class="btn">
                    </div>
                    --}}
                    <div class="payments hidden">
                    </div>
                </div>
                @else
                    <div class="basket-page-title">{{ trans('cart.yourCartIsEmpty') }}</div>
                @endif
            </div>

            @if(session()->has('errorPay'))
                <div class="modal fade" id="modalErroPay" tabindex="-1" role="dialog" aria-labelledby="modalError" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <button type="button" class="close modal-product-close-btn" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <div class="modal-body" style="padding: 15px 50px;">
                                <div class="centered" style="margin: 20px 0;">
                                    <img src="{{ asset('/images/x.png') }}" width = 30>
                                </div>
                                <h3 class="error-text centered bold">
                                    {{ trans('cart.errorPayTitle') }}
                                </h3>
                                <p class="error-desc bold">
                                    {{ trans('cart.errorPayText1') }}
                                </p>
                                <p class="error-desc">
                                    {{ trans('cart.errorPayText2') }}
                                </p>
                                <div class="error-block-btn centered">
                                    <button type="button" data-dismiss="modal" class="error-btn-close">{{ trans('cart.errorPayRetry') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    $(document).ready(function(){
                        $('#modalErroPay').modal('show');


                    });
                </script>

    @endif
            <div id="liqpay_checkout"></div>

            <script>
                $('#delivery').on('submit', function (e) {
                    e.preventDefault();

                    $.ajax({
                        method: "POST",
                        url: "delivery/save",
                        data: $('#delivery').serialize()
                    }).done(function(d){

                        if (d.result == 'OK') {



                            LiqPayCheckout.init({
                                data: d.liqpay_data,
                                signature: d.liqpay_sign,
                                embedTo: "#liqpay_checkout",
                                mode: "embed" // embed || popup,
                            }).on("liqpay.callback", function(data){
                                if (data.status == 'success') {
                                    $.ajax({
                                        type: "POST",
                                        url: d.hash_order_url,
                                        success: function (data_success) {
                                            window.location.href = data_success.success_url;
                                        }
                                    });
                                }

                            }).on("liqpay.ready", function(data){
                                //
                            }).on("liqpay.close", function(data){
                                $.ajax({
                                    type: "POST",
                                    url: d.send_error_url_wfp,
                                    success: function(data_success){

                                    }
                                });
                            });
                        }

                    }).fail(function(data) {
                        if (data.status == 401){
                            window.location.replace("{{ env('APP_URL') }}/login");
                        }
                    });
                });
            </script>
@endsection