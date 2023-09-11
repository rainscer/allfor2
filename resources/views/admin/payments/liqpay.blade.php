@extends('admin.layout.default')
@section('content')
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-success">
            <div class="panel-heading"><h3 class="panel-title">Проверка статуса оплаты заказа Liqpay</h3>
            </div>
            <div class="panel-body">
                {!! Form::open(array('action' => 'AdminController@checkOrderStatusOfLiqpay', 'id' => 'liqpay-form-admin')) !!}
                <div class="form-group">
                    {!! Form::label('payment_id', 'ID платежа') !!}
                    {!! Form::text('payment_id', null, ['class'=>'form-control'] ) !!}
                </div>
                <div class="form-group">
                    {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
                </div>
                {!! Form::close()!!}

            <div id="result-liqpay"></div>
            </div>
        </div>
    </div>
</div>
@endsection