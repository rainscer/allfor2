@extends('admin.layout.default')
@section('content')

    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="min-height: 400px;">
        <div class="panel panel-success">
            <div class="panel-heading">Job log</div>
            <div class="panel-body">
                {!! Form::open(array('url' => '/administrator/job-logs', 'method' => 'GET'))!!}

                <div class="form-group">
                    {!! Form::label('dayRange', 'Select Range') !!}
                    {!! Form::select('dayRange', $day_range, $dayRange, ['class' => 'form-control', 'style' => 'width: 50%; display: inline-block;']) !!}
                    {!! Form::submit('Go!', ['class'=>'btn btn-success pull-right']) !!}
                </div>
                or
                <div class="form-group">
                    {!! Form::label('selectedDateEnd', 'From') !!}
                    {!! Form::text('selectedDateStart', isset($selectedDateStart) ? $selectedDateStart->format('Y-m-d') : '', [
                    'readonly',
                    'data-provide'=>'datepicker',
                    'data-date-format'=>'yyyy-mm-dd',
                    'data-date-week-start'=>'1',
                    'data-date-end-date' => 'Today'
                    ] ) !!}

                    {!! Form::label('selectedDateEnd', 'To') !!}
                    {!! Form::text('selectedDateEnd', isset($selectedDateEnd) ? $selectedDateEnd->format('Y-m-d'): '', [
                    'readonly',
                    'data-provide'=>'datepicker',
                    'data-date-format'=>'yyyy-mm-dd',
                    'data-date-week-start'=>'1',
                    'data-date-end-date' => 'Today'
                    ]) !!}
                </div>
                <div class="form-group">

                </div>
                {!! Form::close()!!}

                @if ($jobs)
                    <div class="list-group">
                        @foreach ($jobs as $key=>$job)
                            <a href="{{ url('administrator/job/'.$key) }}" class="list-group-item jobLog-item-link">
                                <strong>{{ $key }}</strong><span class="pull-right">${{ collect($job)->sum('amount') }} {{--{{ $curency_code }}--}}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 jobLog-item-form-container"></div>

    <script>
        $(function(){

            $("[name=dayRange]").change(function(){
                $("[name=selectedDateStart]").val("");
                $("[name=selectedDateEnd]").val("");
            });

            $("[name=selectedDateStart]").change(function(){
                $('[name=dayRange] option[value="0"]').prop('selected', true);
            });
            $("[name=selectedDateEnd]").change(function(){
                $('[name=dayRange] option[value="0"]').prop('selected', true);
            });


        });
    </script>
@endsection
