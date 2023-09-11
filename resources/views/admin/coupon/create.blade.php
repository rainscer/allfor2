@extends('admin.layout.default')

@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Create coupon</div>
        <div class="panel-body">

            {!! Form::open(['url' => route('coupon.store')]) !!}

            <div class="col-xs-12 form-horizontal">

                <div class="form-group">
                    {!! Form::label('code', 'Купон', ['class'=>'col-sm-2 control-label required']) !!}
                    <div class="col-sm-8">
                        @if ($errors->has('code'))
                            <span class="help-block">
                                   <strong>{{ $errors->first('code') }}</strong>
                               </span>
                        @endif
                        <div class="input-group">
                            {!! Form::text('code', str_random(8), ['class' => 'form-control code', 'required'] ) !!}
                            <span class="input-group-btn">
                                <button class="btn btn-default random" type="button">Go!</button>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('count', 'Количество', ['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::input('number', 'count', 1, ['class'=>'form-control', 'min' => 1, 'required'] ) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('amount', 'Скидка', ['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::input('number', 'amount', 1, ['class'=>'form-control', 'min' => 1, 'step' => 'any', 'required'] ) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('expired_at', 'Годен до', ['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::input('date','expired_at', null, ['class'=>'form-control', 'required', 'style' => 'line-height: 1.42857', 'min' => date('Y-m-d')] ) !!}
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group align-center">
                            {!! Form::submit('Создать', ['class'=>'btn btn-success',
                                'name' => 'save']) !!}
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group align-center">
                            {!! Form::submit('Отмена', ['class'=>'btn btn-danger',
                         'name' => 'cancel']) !!}
                        </div>
                    </div>
                </div>

            </div>
            {!! Form::close()!!}
        </div>
    </div>
    <script>
        function makeid(length) {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for (var i = 0; i < length; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }

        $('.random').on('click', function () {
            $('.code').val(makeid(8));
        })
    </script>
@endsection

