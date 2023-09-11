@extends('admin.layout.default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-success">
                    <div class="panel-heading">Новый пункт меню</div>
                    <div class="panel-body">
                        {!! Form::open(array('route' => 'administrator.menu.store', 'class' => 'form-horizontal'))!!}
                        <div class="form-group">
                            {!! Form::label('name','Название',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('name', null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('type','Тип меню',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('type', ['href' => 'Ссылка','article' => 'Статья'], null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('href','Адресс ссылки',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('href', null, ['class'=>'form-control href-for-menu'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('article','Статья',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('article', $articles, null, ['class' => 'form-control']) !!}
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