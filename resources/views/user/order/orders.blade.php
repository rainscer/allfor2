@extends('user.index')
@section('user_content')
    <script type="text/javascript" src="//www.17track.net/externalcall.js"></script>

    <div class="likes_header prod-pad">
        {{ trans('user.productsPaid') }}
    </div>
    <div class="user-orders">
    @foreach($orders as $order)
        <div class="user-order-item">
            <div class="user-order-header container-fluid">
                <div class="order-date col-md-2 col-sm-3 col-xs-5">
                    <div>
                        <strong>{{ trans('user.orderMade') }}</strong>
                    </div>
                    <div>
                        {{ date_rus($order->created_at) }}
                    </div>
                </div>
                <div class="order-total col-md-2 col-sm-3 col-xs-4">
                    <div>
                        <strong>{{ trans('user.total') }}</strong>
                    </div>
                    <div>
                        ${{ $order->order_total + $order->delivery_cost }} {{--{{ $curency_code }}--}}
                    </div>
                </div>
                <div class="order-delivery-data col-md-4 col-sm-3 hidden-xs" data-toggle="popover" data-placement="bottom">
                    {{--  */    $order->contacts = unserialize($order->contacts);
                                $order->contacts = (array)$order->contacts;
                                foreach ($order->contactFields as $field){
                                    isset($order->contacts[$field]) ? $order->$field = $order->contacts[$field] : $order->$field = '';
                                }
                        /* --}}
                    <div>
                        <strong>{{ trans('user.delivery') }}</strong>
                    </div>
                    <div>
                        <span class="order-username-mark">{{ $order->d_user_name }}</span>
                        <span>&#9660;</span>
                    </div>
                    <div class="hidden popover-adress">
                        <div class="header-deliv-adress">{{ trans('user.deliveryAddress') }}:</div>
                        <table class="table">
                            <tr>
                                <td>
                                    {{ trans('user.name') }}
                                </td>
                                <td>
                                    <strong>{{ $order->d_user_name }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ trans('user.region') }}
                                </td>
                                <td>
                                    <strong>{{ $order->d_user_region }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ trans('user.city') }}
                                </td>
                                <td>
                                    <strong>{{ $order->d_user_city }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ trans('user.address') }}
                                </td>
                                <td>
                                    <strong>{{ $order->d_user_address }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ trans('user.d_index') }}
                                </td>
                                <td>
                                    <strong>{{ $order->d_user_index }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ trans('user.telphone') }}
                                </td>
                                <td>
                                    <strong>{{ $order->d_user_phone }}</strong>
                                </td>
                            </tr>
                            {{--<tr>
                                <td>
                                    {{ trans('user.email') }}
                                </td>
                                <td>
                                    <strong>{{ $order->d_user_email }}</strong>
                                </td>
                            </tr>--}}
                        </table>
                    </div>
                </div>
                <div class="order-number col-md-2 col-xs-3 col-sm-3 pull-right">
                    <div><strong>{{ trans('user.order') }} №{{ $order->id }}</strong></div>
                    <a href="#" class="toggle-block">{{ trans('user.detail') }}</a>
                </div>
            </div>
            <div class="user-order-header-arrow"></div>

            <div class="user-order-body container-fluid">
                <div class="col-md-9">
                    @if($order->tracking_number)
                        {{-- */  $status == \App\Models\Order::STATUS_DELIVERED ? $delivered = true : $delivered = false  /* --}}
                        @if($delivered)
                            <h4 class="bold">{{ trans('user.delivered') }}</h4>
                            <div class="progress">
                                <div class="progress-bar progress-bar-{{ $status }}" role="progressbar"
                                     data-toggle="popover" data-placement="top"
                                     aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                     style="width: 100%">
                                    <span class="sr-only">100% Complete (success)</span>
                                </div>
                            </div>
                        @else
                            <div class="row order-plane-date-block">
                                {{-- */ $sendDate = $order->created_at->copy()->adddays(1);
                                        $planeDate = $sendDate->copy(); /* --}}
                                <h4><strong>{{ trans('user.orderWaight') }}: {{ $planeDate->addDays(20)->format('d.m.Y') }} -
                                        {{ $planeDate->addDays(10)->format('d.m.Y') }}</strong></h4>
                                <div class="order-send-date color-{{ $status }}">{{ trans('user.sent') }}:
                                    {{ $sendDate->format('d.m.Y') }}
                                </div>
                                {{-- */ $diff = $planeDate->diffInHours($sendDate);
                                        $diff_now = $now->diffInHours($sendDate);
                                        $diff_days = $now->diffInDays($sendDate);
                                        $res = round(($diff_now/$diff) *100);  /* --}}
                                <div class="progress">
                                    <div class="progress-bar progress-bar-{{ $status }}" role="progressbar"
                                         data-toggle="popover" data-placement="top"
                                         aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                         style="width: {{ $res }}%">
                                        <span class="sr-only">{{ $res }}% Complete (success)</span>
                                    </div>
                                    <div class="hidden">
                                        {{ trans('user.inWay') }} <strong>{{ $diff_days }} {{ trans('user.days') }}</strong>
                                    </div>
                                </div>
                                <div class="watch-count"><img src="{{ asset('/images/watch.png') }}"></div>
                                <div class="countdown countdown{{ $order->id }}">

                                </div>
                                <script>
                                    $(window).load(function() {
                                        $('.countdown' + '{{ $order->id }}').timeTo({
                                            timeTo: new Date('{{ $planeDate->format('m d Y h:i:s') }}'),
                                            displayDays: 1,
                                            theme: "white",
                                            lang: 'ru',
                                            fontSize: 16,
                                            width: 12,
                                            height: 17
                                        });
                                    });
                                </script>

                                <script type="text/javascript">

                                    $(function () {
                                        YQV5.trackSingleF1({
                                            YQ_ElementId: 'result-track-{{ $order->id }}',      //Требуется, укажите позицию плавания идентификатора элемента.
                                            YQ_Width:900,        //Дополнительно укажите ширину результата отслеживания, минимальная ширина 600px, по умолчанию заполняет контейнер.
                                            YQ_Height:600,       //Дополнительно, укажите высоту результата отслеживания, максимальная высота 800px, по умолчанию заполняет контейнер.
                                            YQ_Fc:"0",       //Опционально, выберите перевозчика, по умолчанию - авто идентификация.
                                            YQ_Lang:"ru",        //Дополнительно укажите язык пользовательского интерфейса, по умолчанию язык будет определен по настройкам браузера.
                                            YQ_Num: '{{ $order->tracking_number }}'      //Требуется, укажите номер, который необходимо отслеживать.
                                        });
                                    });

                                </script>
                            </div>
                        @endif
                    @else
                        <h4 class="bold you-order-is-waiting">{{ trans('user.youOrderIsWaiting') }}</h4>
                    @endif

                    @foreach($order->order_item as $order_item)
                        <div class="order-product-item row">
                            <div class="col-md-3 col-xs-6">
                                <div class="order-product-img-block">
                                <a href="{{ route('product.url',[$order_item->product->upi_id, $order_item->product->slug]) }}" class="link_modal">
                                    <img src="{{ $order_item->product->getMainImage('md') }}" alt="{!! $order_item->product->$local !!}">
                                </a>
                                </div>
                            </div>
                            <div class="order-product-name col-md-9 col-xs-6">
                                <a href="{{ route('product.url',[$order_item->product->upi_id, $order_item->product->slug]) }}" class="link_modal">
                                    {{ $order_item->product->$local }}
                                </a>
                            <div class="order-product-price">${{ $order_item->product->price }}{{--{{$curency_code}}--}}</div>
                            {{--<div class="order-product-price">{{ $order_item->product_quantity }} шт.</div>--}}
                                <a href="#" class="btn order-add-to-cart ajaxAddProductToCart"
                                   data-url="{{ url('cart/add') }}" data-owner-id="{{ $order_item->product->id }}">
                                    {{ trans('user.buyAgain') }}</a>

                                <a href="{{ url('user/order/add-review/' . $order_item->product->id) }}" class="btn question modalFormToggle">{{ trans('product.giveFeedback') }}</a>

                                <div class="alert alert-success message-box" role="alert">
                                    <div class="message"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>


                <div class="col-md-3">
                    <div class="row centered">
                        <a href="#threadModal" class="btn question" data-toggle="modal">{{ trans('user.askQuestion') }}</a>

                        @if($order->tracking_number && (!isset($delivered) || !$delivered))
                            @if($order->isTotoTrack())
                                <a href="http://totopost.com/en/track?track={{ $order->tracking_number }}" class="btn check-order" target="_blank">{{ trans('user.trackPackage') }}</a>
                            @else
                                <a href="javascript:void(0);" id="result-track-{{ $order->id }}" class="btn check-order">{{ trans('user.trackPackage') }}</a>
                            @endif
                        @endif
                    </div>
                    <div class="transport-company">
                        {{ trans('user.transportCompany') }}:
                        <div class="bold">
                            Expedite Post
                        </div>
                    </div>
                    @if($order->tracking_number)
                        <div class="tracking-number">
                            {{ trans('user.tackNumber') }}:
                            <div class="bold">
                                {{ $order->tracking_number }}
                            </div>
                        </div>
                    @endif
                    @if($order->delivery_description)
                        <div class="delivery-description-post-block">
                            <br>
                            <div>
                                {{ $order->delivery_description }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
    </div>

@endsection