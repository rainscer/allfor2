@extends('user.index')
@section('user_content')
    <div class="user-setting-block">
        <h3 class="profile_h1">{{ trans('user.profile') }}</h3>
        {!! Form::model($user, array('url' => array('user/setting/save'))) !!}
        <div class="container-fluid">
            <div class="profile_block col-md-4">
                <div class="profile_block_text">{{ trans('user.yourData') }}</div>
                <div class="form-group">
                    {!! Form::label('name', trans('user.name')) !!}
                    {!! Form::text('name',null, ['class'=>'form-control'] ) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('last_name', trans('user.last_name')) !!}
                    {!! Form::text('last_name',null, ['class'=>'form-control'] ) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('d_user_phone',trans('user.telphone')) !!}
                    {!! Form::text('d_user_phone',null, ['class'=>'form-control phone'] ) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('email',trans('user.email')) !!}
                    {!! Form::text('email',null, ['class'=>'form-control'] ) !!}
                </div>
            </div>
            <div class="profile_delivery_block col-md-4">
                <div class="delivery_text">{{ trans('user.yourFullAddress') }}</div>
                <div class="form-group">
                    {!! Form::label('d_user_city', trans('user.city')) !!}
                    <select class="delivery_city form-control chosen-select" name="d_user_city">
                        @if(($user->d_user_city == ''))
                            <option value = "-1" selected>
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
                    </select>
                </div>
                <div class="form-group">
                    {!! Form::label('d_user_address', trans('user.d_address')) !!}
                    {!! Form::text('d_user_address',null, ['class'=>'form-control'] ) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('d_user_index', trans('user.d_index')) !!}
                    {!! Form::text('d_user_index',null, ['class'=>'form-control'] ) !!}
                </div>
            </div>
            <div class="profile_block col-md-4">
                <div class="setting_text">{{ trans('user.forChangePassword') }}</div>
                <div class="form-group">
                    {!! Form::label('password',trans('user.password')) !!}
                    {!! Form::password('password', ['class'=>'form-control'] ) !!}
                </div>
                <div class="form-group">
                    {!! Form::label( 'password_confirmation', trans('user.confirmPassword')) !!}
                    {!! Form::password('password_confirmation', ['class'=>'form-control'] ) !!}
                </div>
            </div>
        </div>
        <div class="form-group centered btn-submit-block">
            {!! Form::submit(trans('user.save'), ['class'=>'btn']) !!}
        </div>
        {!! Form::close()!!}
    </div>
@endsection