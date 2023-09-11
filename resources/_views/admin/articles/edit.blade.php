@extends('admin.layout.default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-success">
                    <div class="panel-heading">Редактировать статью</div>
                    <div class="panel-body">
                        {!! Form::model($article, ['route' => ['administrator.articles.update', $article->id], 'method' => 'PUT', 'class' => 'form-horizontal'])!!}
                        <div class="form-group">
                            {!! Form::label('title_ru','Название',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('title_ru', null, ['class'=>'form-control', 'required'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('text_ru','Текст',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                <textarea name="text_ru" id="text_ru">
                                    {{ $article->text_ru }}
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('menu_item') !!} Пункт меню
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                {!! Form::submit('Сохранить и закрыть', ['name' => 'save', 'class'=>'btn btn-primary']) !!}
                                {!! Form::submit('Изменить', ['name' => 'update','class'=>'btn btn-primary']) !!}
                            </div>
                        </div>
                        {!! Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var editor = CKEDITOR.replace( 'text_ru',{
            filebrowserBrowseUrl : '/elfinder/ckeditor'
        } );
    </script>
@endsection