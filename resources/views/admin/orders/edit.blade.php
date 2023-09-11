@extends('admin.layout.default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-success">
                    <div class="panel-heading">Редактирование заказа</div>
                    <div class="panel-body">
                        {!! Form::model($order, array('route' => array('order.update', $order->id), 'class' => 'form-horizontal')) !!}
                        {!! Form::hidden('order_status',null) !!}
                        <div class="form-group">
                            {!! Form::label('d_user_name','Имя',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('d_user_name', null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('d_user_email','Email',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('d_user_email', null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('d_user_phone','Номер мобильного телефона',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('d_user_phone', null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('d_city','Город',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                <select class="delivery_city form-control chosen-select" name="d_user_city">
                                    @if($order->d_user_city == '')
                                        <option value="-1" selected>
                                            Выберите город
                                        </option>
                                    @endif
                                        @foreach($cities as $city)
                                            @if(($order->d_user_city == $city->name) &&
                                                        ($order->d_user_region == $city->region_name))
                                                <option value = "{{ $city->id }}" selected>
                                                    {{ $city->name . ' (' . $city->region_name . ')'  }}
                                                </option>
                                            @else
                                                <option value = "{{ $city->id }}">
                                                    {{ $city->name . ' (' . $city->region_name . ')'  }}
                                                </option>
                                            @endif
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('d_user_address','Адресс',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('d_user_address',null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('d_user_index','Индекс',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('d_user_index',null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('tracking_number','Трек номер',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('tracking_number',null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('last_office_index','Индекс укрочты',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('last_office_index',null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('delivery_description','Статус укрочты',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('delivery_description',null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>


                        @if(isset($users) && count($users) > 0)
                            <div class="form-group">
                                {!! Form::label('user_buyer','Пользователь',['class'=>'col-sm-2 control-label']) !!}
                                <div class="col-sm-10">
                                    <select class="form-control chosen-select" autocomplete="off" name="user_buyer">
                                        <option value = "-1" {{ !$order->user_id ? 'selected' : ''}}>
                                            {{ trans('user.chooseUser') }}
                                        </option>
                                        @foreach($users as $id => $userTo)
                                            <option value = "{{ $id }}" {{ $order->user_id == $id ? 'selected' : ''}}>
                                                {{ $userTo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="form-group">
                            {!! Form::label('order_status','Статус заказа',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('order_status', $order->all_statuses, null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('api') !!} Api
                                    </label>
                                </div>
                            </div>
                        </div>
                        @if($order->api==false)
                            <button type="button" class="btn btn-success delete-admin-btn delete-admin-order-btn"
                                    data-owner-id="{{ url('administrator/orders/delete-item') }}">Удалить</button>
                        @endif
                        <table class="table table-striped table-bordered" id="order_items_table" data-owner-id="{{ $order->id }}">
                            @foreach($order->order_item as $order_item)
                                <tr class="admin-order-item" id="item-{{ $order_item->id }}">
                                    <td class="order_item">
                                        @if($order->api == false)
                                            <input type="checkbox" class="delete-box-admin" id="order-box-{{ $order_item->id }}"
                                                   name="order_item[]" value="{{ $order_item->id }}">
                                            <label for="order-box-{{ $order_item->id }}"><span></span></label>
                                        @endif
                                        <a href="{{ url('upi/'.$order_item->product_upi) }}" target="_blank">
                                            {{ $order_item->product_name }}</a>
                                    </td>
                                    <td class="upi">Upi: {{ $order_item->product_upi }}</td>
                                    <td class="quantity">
                                        <input type="number" min="1" class="form-control admin-product-quantity"
                                               value="{{ $order_item->product_quantity }}"
                                               name="product_quantity_{{ $order_item->id }}"
                                               {{ $order->api ? 'disabled' : '' }}/>
                                    </td>
                                    <td class="price">
                                        <span class="hidden product-price">{{ $order_item->product_price }}</span>
                                        <span class="product-cost">
                                            ${{ $order_item->product_quantity * $order_item->product_price }}
                                        </span> {{--{{  ' '.$curency_code }}--}}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td><strong>Итого:</strong></td>
                                <td><strong><span class="admin-order-total">${{ $order->order_total }}</span>{{--{{ ' '.$curency_code }}--}}</strong></td>
                            </tr>
                        </table>
                        <div class="alert alert-danger" role="alert">
                            * Если все товары с таблицы будут удалены, то заказ будет автоматически удалён
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10">
                                {!! Form::submit('Изменить', ['class'=>'btn btn-primary']) !!}
                            </div>
                        </div>
                        {!! Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection