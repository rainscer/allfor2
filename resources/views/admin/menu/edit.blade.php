@extends('admin.layout.default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-success">
                    <div class="panel-heading">Изменить пункт меню</div>
                    <div class="panel-body">
                        {!! Form::model($menu, array('route' => ['administrator.menu.update', $menu->id], 'method' => 'PUT', 'class' => 'form-horizontal'))!!}
                        {!! Form::hidden('id', $menu->id) !!}
                        <div class="form-group">
                            {!! Form::label('name','Название',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('name', null, ['class'=>'form-control'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('type','Тип меню',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('type', ['href' => 'Ссылка','article' => 'Статья'], $menu->type, ['class' => 'form-control']) !!}
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
                                {!! Form::submit('Изменить', ['class'=>'btn btn-primary']) !!}
                            </div>
                        </div>
                        {!! Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            //Function for choose menu type
            function checkTypeOfHref() {
                if ($('#type').val() == 'href') {
                    $('.href-for-menu').attr('disabled', false);
                    $('select#article').attr('disabled', true);
                } else {
                    $('.href-for-menu').attr('disabled', true);
                    $('select#article').attr('disabled', false);
                }
            }

            checkTypeOfHref();
        });
    </script>
@endsection