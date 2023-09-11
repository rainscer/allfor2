@extends('admin.layout.default')
@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Редактирование пользователя <a href="#" class="toggle-table btn btn-success">Свернуть/развернуть</a></div>
        <div class="panel-body">
            {!! Form::model($user, array('route' => 'user.update', 'class' => 'form-horizontal'))!!}
            {!! Form::hidden('id', null) !!}
            <div class="container-fluid">
                <div class="profile_block">
                    <div class="profile_block_text">{{ trans('user.yourData') }}</div>
                    <div class="form-group">
                        {!! Form::label('name', trans('user.name')) !!}
                        {!! Form::text('name',null, ['class'=>'form-control'] ) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('email',trans('user.email')) !!}
                        {!! Form::text('email',null, ['class'=>'form-control'] ) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('d_user_phone',trans('user.phone')) !!}
                        {!! Form::text('d_user_phone',null, ['class'=>'form-control phone'] ) !!}
                    </div>
                    <div class="setting_text">{{ trans('user.forChangePassword') }}</div>
                    <div class="form-group">
                        {!! Form::label('password',trans('user.password')) !!}
                        {!! Form::password('password', ['class'=>'form-control'] ) !!}
                    </div>
                    <div class="delivery_text">{{ trans('user.yourFullAddress') }}</div>
                    <div class="form-group">
                        {!! Form::label('d_user_city', trans('user.city')) !!}
                        <select class="delivery_city form-control chosen-select" name="d_user_city">
                            @if(($user->d_user_city == '' || !$user->d_user_city))
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
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('active') !!} Администратор
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::submit(trans('user.save'), ['class'=>'btn btn-primary']) !!}
                    </div>
                </div>
            </div>
            {!! Form::close()!!}
        </div>
    </div>
    @if(isset($user->order) && count($user->order))
        {{-- */ $orders = $user->order /* --}}
        @include('admin.users.orders')
    @endif
    @if(isset($products))
        <div class="panel panel-success">
            <div class="panel-heading">Просмотренные пользователем продукты <a href="#" class="toggle-table btn btn-success">Свернуть/развернуть</a></div>
            <div class="panel-body">
                @include('catalog.style_blocks')
            </div>
        </div>
    @endif
@endsection