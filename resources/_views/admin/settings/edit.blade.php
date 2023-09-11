@extends('admin.layout.default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-success">
                    <div class="panel-heading">Изменить настройки</div>
                    <div class="panel-body">
                        {!! Form::model($setting, array('route' => array('settings.update', $setting->id), 'class' => 'form-horizontal'))!!}
                        <div class="form-group">
                            {!! Form::label('key_name','Название',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('key_name', null, ['class'=>'form-control', 'required'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('description','Описание',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('description', null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('value','Значение',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('value', null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
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