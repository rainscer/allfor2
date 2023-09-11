@extends('admin.layout.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-success">
                    <div class="panel-heading">Новый пользователь</div>
                    <div class="panel-body">
                        {!! Form::open(array('route' => 'user.store', 'class' => 'form-horizontal'))!!}
                        <div class="form-group">
                            {!! Form::label('name','Имя',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('name', null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('email','Email',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('email', null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            {!! Form::label('password','Пароль',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::password('password', ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('active') !!} Администратор
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                {!! Form::submit('Создать', ['class'=>'btn btn-primary']) !!}
                            </div>
                        </div>
                        {!! Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection