@extends('layout.app')

@section('content')
    @if(isset($cart_products))
        <script src="https://js.stripe.com/v3/"></script>
        <div class="basket_container">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2" style="padding: 0;">
                        <h1 class="main_title al_center">{{ trans('cart.yourCart') }}:</h1>
                        <div class="basket_w">

                            <table class="basket_table" id="order">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>{{ trans('cart.price') }}:</th>
                                    <th>{{ trans('cart.qu-ty') }}:</th>
                                    <th>{{ trans('cart.total') }}:</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                {{-- */ $total_sum = 0; /* --}}
                                {{-- */ $total_count = 0; /* --}}
                                {{-- */ $total_weight = 0; /* --}}
                                @foreach($cart_products as $cart_product)
                                    <tr data-id="{{ $cart_product->id }}" id="pr{{ $cart_product->id }}" class="product-cart-item">
                                        <td data-table-title="{{trans('cart.product')}}:">
                                            <input type="hidden" class="product-weight-by1" value="{{ round($cart_product->weight * 0.035274, 2) }}">
                                            <div class="table_prod">
                                                <div class="table_img">
                                                    <a href="{{ route('product.url',[$cart_product->upi_id, $cart_product->slug]) }}" class="link_modal"><img src="{{ $cart_product->getMainImage('md') }}" alt="" style="width: 79px; height: auto;"/></a>
                                                </div>
                                                <div class="table_about">
                                                    <h3 class="table_title">
                                                        <a href="{{ route('product.url',[$cart_product->upi_id, $cart_product->slug]) }}" class="link_modal">{!! $cart_product->$local !!}</a>
                                                    </h3>
                                                    <ul class="characteristic_list">
                                                        <li>
                                                            <i><img src="{{ asset('images/icons/characteristic-icon.jpg') }}" alt=""></i> {{ trans('cart.weight') }}:
                                                            <span class="product-weight">{{ round($cart_product->weight * 0.035274 * $quantity[$cart_product->id], 2) }}</span> ounces
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-table-title="{{trans('cart.price')}}:">
                                            <div class="table_price">
                                                $<span class="prod_price">{{ $cart_product->price }}</span>
                                            </div>
                                        </td>
                                        <td data-table-title="{{trans('cart.qu-ty')}}:">
                                            <div class="table_cols">
                                                <input class="table_input product-basket-count" type="text" min="1" value="{{ $quantity[$cart_product->id] }}"/>
                                            </div>
                                        </td>
                                        <td data-table-title="{{trans('cart.sum')}}:">
                                            <div class="table_price table_sum">
                                                $<span class="prod_sum">{!! $cart_product->price * $quantity[$cart_product->id] !!}</span>
                                            </div>
                                        </td>
                                        <td class="table_close_td delete-icon-cart">
                                            <a href="{{ url('cart/delete') }}" data-owner-id="{{ $cart_product->id }}" class="table_close ajaxActionDeleteProduct">{{--<img src="{{ asset('images/icons/close-icon.png') }}" alt="">--}}</a>
                                        </td>
                                    </tr>
                                    {{-- */ $total_weight += $cart_product->weight * 0.035274 * $quantity[$cart_product->id] /* --}}
                                    {{-- */ $total_count += $quantity[$cart_product->id] /* --}}
                                    {{-- */ $total_sum += $cart_product->price * $quantity[$cart_product->id] /* --}}
                                @endforeach
                                <tr class="delivery_tr">
                                    <td>
                                        <div class="table_prod">
                                            <div class="table_img">
                                                <img src="{{ asset('images/icons/table-icon.png') }}" alt="">
                                            </div>
                                            <div class="table_about">
                                                <div class="delivery_txt">
                                                    <span>{{ trans('cart.delivery') }}</span>
                                                    <i data-popup="#cart_delivery_popup"><img src="{{ asset('images/icons/question-icon.png') }}" alt=""></i>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-table-title="{{ trans('cart.price') }}:">
                                        <div class="table_price">
                                            $<span><?= app('Setting')->getSettingValue('shipping_price', 0.42) ?></span>
                                        </div>
                                    </td>
                                    <td data-table-title="{{ trans('cart.weight') }}:">
                                        <div class="table_cols total-weight">{{ round($total_weight, 2) }} oz</div>
                                    </td>
                                    <td data-table-title="{{ trans('cart.sum') }}:">
                                        <div class="table_price table_sum">
                                            $<span class="deliv-price-total-weight">{{ round(0.42 * $total_weight, 2) }}</span>
                                        </div>
                                    </td>
                                    <td class="table_close_td delete-icon-cart">
                                        <div class="table_close" style="visibility: hidden">
                                            <img src="{{ asset('images/icons/close-icon.png') }}" alt="">
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            {!! Form::model($user, array('url' => 'delivery/save', 'id'=>'delivery', 'class' => 'form-inline')) !!}

                            <div class="coupon_box">
                                <div class="delivery_txt coupon_txt">
                                    <span>{{ trans('cart.coupon') }}</span>
                                    <i data-popup="#coupon_popup"><img src="{{ asset('images/icons/question-icon.png') }}" alt=""></i>
                                </div>
                                <div class="coupon_field">
                                    <input type="text" name="coupon" placeholder="B00XMWSM" id="coupon"/>
                                </div>
                                <div class="coupon_sum">{{---$<span class="coupon">4</span>--}}</div>
                            </div>
                            {{-- */ $total_sum += round(0.42 * $total_weight, 2); /* --}}
                            <div class="pay_box">
                                <div>{{ trans('cart.totalToPay') }}:</div>
                                <div class="pay_sum">$<span class="total-price">{{$total_sum }}</span>
                                </div>
                            </div>

                            <div style="padding-bottom: 15px;">
                                {!! Form::textarea('comment', null, ['class'=>'form_input', 'placeholder' => trans('cart.comment'), 'style' => 'height: 52px;'] ) !!}
                            </div>

                            <h4 class="basket_form_title al_center">{{ trans('cart.allFieldsMustBeFilledIn') }}</h4>
                            <div class="basket_form_wrap">
                                <div class="form_row w144">
                                    {!! Form::text('name', null, ['class'=>'form_input', 'id' => 'name', 'placeholder' => trans('cart.first_name'), 'required'] ) !!}
                                </div>
                                <div class="form_row w144">
                                    {!! Form::text('last_name', null, ['class'=>'form_input', 'id' => 'last_name', 'placeholder' => trans('cart.last_name'), 'required'] ) !!}
                                </div>
                                <div class="form_row w251">
                                    {!! Form::text('d_user_address', null, ['class'=>'form_input', 'id' => 'd_user_address', 'placeholder'=> trans('cart.d_address'),'required'] ) !!}
                                </div>
                                <div class="form_row w168">
                                    {!! Form::text('d_user_city', null, ['class'=>'form_input', 'id' => 'd_user_city', 'placeholder' => trans('cart.city'), 'required'] ) !!}
                                </div>
                                <div class="form_row w143">
                                    {!! Form::text('state', null, ['class'=>'form_input', 'id' => 'd_user_index', 'placeholder'=> trans('cart.state'),'required'] ) !!}
                                </div>
                                <div class="form_row w143">
                                    {!! Form::text('d_user_index', null, ['class'=>'form_input', 'id' => 'd_user_index', 'placeholder'=> trans('cart.d_index'),'required'] ) !!}
                                </div>
                                <div class="form_row w160">
                                    {!! Form::text('d_user_phone',null, ['class'=>'form_input phone-multi', 'id' => 'd_user_phone', 'placeholder'=> trans('cart.d_phone'),'required'] ) !!}
                                </div>
                                <div class="form_row w143">
                                    {!! Form::email('email', null, ['class'=>'form_input','id' => 'email', 'placeholder'=> trans('cart.yourEmail'), 'required'] ) !!}
                                </div>
                            </div>
                            <div class="form_row_button">
                                <button class="form_btn" type="submit">
                                    <i><img src="{{ asset('images/icons/lock-icon.png') }}" alt=""></i><span>{{ trans('cart.pay') }}</span>
                                </button>
                                <div style="display: none;" id="delivery-submit-success-block" class="text-success">Thank you! We will
                                    be in touch with you ASAP to proceed with
                                    your order !
                                </div>
                                <img class="abs_img" src="{{ asset('images/card-img.png') }}" alt="">
                            </div>
                            {!! Form::close() !!}

                        </div>

                        <div id="stripe_checkout" style="display: none">
                            <style>
                                .StripeElement {
                                    box-sizing: border-box;

                                    height: 40px;

                                    padding: 10px 12px;

                                    border: 1px solid transparent;
                                    border-radius: 4px;
                                    background-color: white;

                                    box-shadow: 0 1px 3px 0 #e6ebf1;
                                    -webkit-transition: box-shadow 150ms ease;
                                    transition: box-shadow 150ms ease;
                                }

                                .StripeElement--focus {
                                    box-shadow: 0 1px 3px 0 #cfd7df;
                                }

                                .StripeElement--invalid {
                                    border-color: #fa755a;
                                }

                                .StripeElement--webkit-autofill {
                                    background-color: #fefde5 !important;
                                }
                            </style>
                            <form action="/order/charge" method="post" id="payment-form">
                                {!! Form::token() !!}
                                <div class="form-row">
                                    <label for="card-element">Credit or debit card</label>
                                    <div id="card-element"></div>
                                    <div id="card-errors" role="alert"></div>
                                </div>

                                <div class="cart-header text-center">
                                    <button class="btn btn-success">Submit Payment</button>
                                </div>
                            </form>
                        </div>
                        <script>
                            $('#d_user_city').autocomplete({
                                source: function (request, response) {
                                    $.ajax(
                                        {
                                            url: '/cart/get_city',
                                            dataType: "json",
                                            data:
                                                {
                                                    term: request.term,
                                                },
                                            success: function (data) {
                                                response(data);
                                            }
                                        });
                                },
                            });

                            $('#delivery').on('submit', function (e) {
                                e.preventDefault();

                                // show success message block
                                $.ajax({
                                    method: "POST",
                                    url: "delivery/save",
                                    data: $('#delivery').serialize()
                                }).done(function (response) {
                                    if (response.result === 'OK') {
                                        $('#delivery-submit-success-block').show();
                                    }
                                });
                            });

                            function stripeTokenHandler(token, order_id) {
                                var form = document.getElementById('payment-form');
                                var hiddenInput = document.createElement('input');
                                hiddenInput.setAttribute('type', 'hidden');
                                hiddenInput.setAttribute('name', 'stripeToken');
                                hiddenInput.setAttribute('value', token.id);
                                form.appendChild(hiddenInput);
                                var hiddenInput = document.createElement('input');
                                hiddenInput.setAttribute('type', 'hidden');
                                hiddenInput.setAttribute('name', 'order_id');
                                hiddenInput.setAttribute('value', order_id);
                                form.appendChild(hiddenInput);
                                form.submit();
                            }
                        </script>

                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="empty_cart" style="text-align: center; font-size: 24px;">{{ trans('cart.emptyCart') }}</div>
    @endif
@endsection