@extends('admin.layout.default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-success">
                    <div class="panel-heading">allfor2.com</div>
                    <div class="panel-body">
                        {{ Auth::user()->getFullName() }}, вы авторизировались в административной части сайта allfor2.com. Теперь вы можете изменять доступные вам параметры.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


