@extends('admin.layout.default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Ошибка сохранения</strong><br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="panel panel-success">
                    <div class="panel-heading">Новая статья</div>
                    <div class="panel-body">
                        {!! Form::open(array('route' => 'administrator.articles.store', 'class' => 'form-horizontal'))!!}
                        <div class="form-group">
                            {!! Form::label('title_ru','Название',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('title_ru', null, ['class'=>'form-control','required'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('text_ru','Текст',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                <textarea name="text_ru" id="text_ru">
                                    {{ Input::old('text_ru') }}
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
        var editor = CKEDITOR.replace( 'text_ru',{
            filebrowserBrowseUrl : '/elfinder/ckeditor'
        } );
    </script>
@endsection