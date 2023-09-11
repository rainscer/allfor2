{{--<script type="text/javascript" src="/bower_components/jquery/jquery.min.js"></script>--}}
<script type="text/javascript" src="/js/moment.min.js"></script>
{{--<script type="text/javascript" src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>--}}
<script type="text/javascript" src="/js/bootstrap-datetimepicker.min.js"></script>
{{--<link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css" />--}}
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css" />

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-success">
            <div class="panel-heading"><h3 class="panel-title">Импорт товаров</h3>
            </div>
            <div class="panel-body">
                {!! Form::open(array('action' => 'ApiController@callApiImportCategories')) !!}
                <div class="form-group">
                    {!! Form::submit('Импорт категорий товаров', ['class'=>'btn btn-primary', 'disabled' => true]) !!}
                </div>
                {!! Form::close()!!}

                {!! Form::open(array('action' => 'ApiController@callApiImportProducts')) !!}
                <div class="form-group">
                    {!! Form::label('shelf_id', 'ID Полки') !!}
                    {!! Form::text('shelf_id', 443, ['class'=>'form-control'] ) !!}
                </div>
                <div class="form-group">
                    {!! Form::submit('Импорт товаров с полки', ['class'=>'btn btn-primary', 'disabled' => true]) !!}
                </div>
                {!! Form::close()!!}
                {!! Form::open(array('action' => 'ProductController@deleteAll')) !!}
                <div class="form-group">
                    {!! Form::submit('Удалить все товары', ['class'=>'btn btn-primary', 'disabled' => true]) !!}
                </div>
                {!! Form::close()!!}
                {!! Form::open(array('action' => 'AdminController@clearCache')) !!}
                <div class="form-group">
                    {!! Form::submit('Очистить кеш', ['class'=>'btn btn-primary']) !!}
                </div>

                {!! Form::close()!!}
                <div class="form-group">
                    <a href="{{ url('administrator/get-info-from-ukr-poshta') }}" class="btn btn-primary">Check ukrposhta</a>
                </div>

                <div class="form-group">
                    <form method="get" action="{{ url('administrator/orders_xls')  }}" target="_blank">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class='input-group date' id='date_start'>
                                    <input type='text' class="form-control" name="start"/>
                                    <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#date_start').datetimepicker();
                                });
                            </script>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class='input-group date' id='date_end'>
                                    <input type='text' class="form-control" name="end"/>
                                    <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#date_end').datetimepicker();
                                });
                            </script>
                        </div>

                        <div class="col-md-4">
                            <input type="submit" class="btn btn-primary"
                                   value="{{ trans('admin.orders_export_xls')  }}"/>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>