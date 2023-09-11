@extends('admin.layout.default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Ошибка</strong><br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="panel panel-success">
                    <div class="panel-heading">Новое письмо</div>
                    <div class="panel-body">
                        {!! Form::open(array('route' => 'mail.store', 'class' => 'form-horizontal'))!!}
                        <div class="form-group">
                            {!! Form::label('subject','Тема',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('subject', null, ['class'=>'form-control','required'] ) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('body','Сообщение',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                <textarea name="body" id="body">
                                    @include('emails.default')
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('emails','Пользователи',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('emails', $email_list, null, ['class' => 'form-control chosen-select', 'multiple'=>'multiple', 'name'=>'emails[]']) !!}
                            </div>
                        </div>
                        <div class="form-group"><div class="col-sm-10 col-sm-offset-2 centered">и/или</div></div>
                        <div class="form-group">
                            {!! Form::label('participants','Emails',['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::textarea('participants', null, ['class'=>'form-control', 'size' => '50x5'] ) !!}
                            </div>
                        </div>
                        <div class="form-group"><div class="col-sm-10 col-sm-offset-2">* Вводить каждый адрес с новой строки</div></div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                {!! Form::submit('Сохранить', ['class'=>'btn btn-primary', 'name' => 'save']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                {!! Form::submit('Сохранить и отправить', ['class'=>'btn btn-primary', 'name' => 'send']) !!}
                            </div>
                        </div>
                        {!! Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var editor = CKEDITOR.replace( 'body',{
            filebrowserBrowseUrl : '/elfinder/ckeditor'
        } );
    </script>
@endsection