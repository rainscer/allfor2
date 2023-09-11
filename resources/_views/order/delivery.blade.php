@if(Auth::check())
    <div class="your-order-ready"><p>{{ trans('cart.hello') }} <span class="bold">{{ $user->getFullName() }}!</span>
        </p>
        <p>{{ trans('cart.yourOrder') }} {{ trans('cart.made') }}</p>
    </div>
    @if(isset($validated_address))
        <div class="delivery-text">
            <h4 class="centered-mobile"><span class="bold">{{ trans('cart.deliveryAddress') }}:</span></h4>
            <p>{{ $validated_address }}</p>
        </div>
        <div class="change-delivery-block centered">
            <span class="change-delivery-address link">{{ trans('cart.edit') }}</span>
        </div>
    @else
        <div class="put-delivery centered colored-737373">{{ trans('cart.allFieldsMustBeFilledIn') }}</div>
    @endif
@else
    <div class="your-order-ready">
        <p>{{ trans('cart.hello') }} {{ trans('cart.guest') }}!
            {{ trans('cart.yourOrder') }} {{ trans('cart.made') }}</p>
    </div>
    <div class="put-delivery centered colored-737373">{{ trans('cart.allFieldsMustBeFilledIn') }}</div>
@endif
<div class="margin-block">
    <div class="delivery_block {{ !isset($validated_address) ? '' : 'hidden' }}">
        {!! Form::model($user,array('url' => 'delivery/save', 'id'=>'delivery', 'class' => 'form-inline')) !!}
        <div>
        <div class="form-group">
            {!! Form::text('name',null, ['class'=>'form-control', 'id' => 'name', 'placeholder' => trans('cart.first_name'), 'required'] ) !!}
        </div>
        <div class="form-group">
            {!! Form::text('last_name',null, ['class'=>'form-control', 'id' => 'last_name', 'placeholder' => trans('cart.last_name'), 'required'] ) !!}
        </div>
        <div class="form-group width-2">
            <select class="delivery_city form-control chosen-select" autocomplete="off" id="d_user_city" name="d_user_city">
                @if(Auth::check())
                    @if(($user->d_user_city == 0) || ($user->d_user_city == -1))
                        <option value = "-1" selected>
                            {{ trans('cart.chooseCity') }}
                        </option>
                    @else
                        <option value = "-1">
                            {{ trans('cart.chooseCity') }}
                        </option>
                    @endif
                    @foreach($cities as $city)
                            @if(($user->d_user_city == $city->name) &&
                                    ($user->d_user_region == $city->region_name))
                                <option value = "{{ $city->id }}" selected>
                                    {{ $city->name . ' (' . $city->region_name . ')'  }}
                                </option>
                            @else
                                <option value = "{{ $city->id }}">
                                    {{ $city->name . ' (' . $city->region_name . ')'  }}
                                </option>
                            @endif
                    @endforeach
                @else
                    <option value = "-1" selected>
                        {{ trans('cart.chooseCity') }}
                    </option>
                    @foreach($cities as $city)
                        <option value = "{{ $city->id }}">
                            {{ $city->name . ' (' . $city->region_name . ')'  }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="form-group">
            {!! Form::text('d_user_address',null, ['class'=>'form-control', 'id' => 'd_user_address', 'placeholder'=> trans('cart.d_address'),'required'] ) !!}
        </div>
        </div>
        <div class="form-group">
            {!! Form::text('d_user_index',null, ['class'=>'form-control', 'id' => 'd_user_index', 'placeholder'=> trans('cart.d_index'),'required'] ) !!}
        </div>
        <div class="form-group">
            {!! Form::text('d_user_phone',null, ['class'=>'form-control phone', 'id' => 'd_user_phone', 'placeholder'=> trans('cart.d_phone'),'required'] ) !!}
        </div>
        @if(Auth::check())
            <div class="form-group">
                {!! Form::email('email',null, ['class'=>'form-control','id' => 'email', 'placeholder'=> trans('cart.yourEmail')] ) !!}
            </div>
        @else
            <div class="form-group">
                {!! Form::email('email',null, ['class'=>'form-control','id' => 'email', 'placeholder'=> trans('cart.yourEmail')] ) !!}
            </div>
        @endif
        {!! Form::close() !!}
    </div>
</div>
@if(Auth::check())
    <div class="centered save-delivery-block">
        <span class="save-delivery link margin-20"
              data-url="{{ url('/save-delivery-user') }}">{{ trans('cart.save') }}</span>
        <span class="clear-delivery link margin-20"
              data-url="{{ url('/save-delivery-user') }}">{{ trans('cart.clearForm') }}</span>
    </div>
@endif
{{--<h3>{{ trans('cart.deliveryAddress') }}:</h3>--}}

{{--<div class="form-group clearfix">
    {!! Form::submit('Перейти к оплате', ['class'=>'btn btn-delivery']) !!}
    <a href="{{  url('/cart/clean') }}" class="basket-page-order btn">{{ trans('cart.clearCart') }}</a>
</div>
{!! Form::close() !!}
<div class="setting_title_cart hidden">{{ trans('cart.deliveriInfo') }}</div> --}}
{{--<div class="form-group">
<select class="delivery_region form-control chosen-select" data-url="{{ url('delivery/getcity') }}" id="d_region" name="d_region" >
    @if(Auth::check())
        @if((Auth::user()->d_region == 0) || (Auth::user()->d_region == -1))
            <option value = "-1" selected>
                {{ trans('cart.chooseRegion') }}
            </option>
        @endif
        @foreach($regions as $region)
            <option value = "{{ $region->id }}" {{ Auth::user()->d_region == $region->id ? 'selected':'' }}>
                {{ $region->name  }}
            </option>
        @endforeach
    @else
        <option value = "-1" selected>
            {{ trans('cart.chooseRegion') }}
        </option>
        @foreach($regions as $region)
            <option value = "{{ $region->id }}">
                {{ $region->name  }}
            </option>
        @endforeach
    @endif
</select>
</div>--}}