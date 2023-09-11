@if ($job)

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ $jobName }}</h3>
        </div>
        <div class="panel-body">
            {!! Form::open(array('url' => 'administrator/scheduler/job/'.$jobName, 'class' => 'form-horizontal')) !!}

            <div class="form-group">
                {!! Form::label('active', 'Active', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    {!! Form::checkbox('active', 1, $job->active) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('description', 'Description', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    {!! Form::text('description', $job->description, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('scheduleRule', 'Schedule Rule', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    {!! Form::text('scheduleRule', $job->scheduleRule, ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('scheduleRuleParameter', 'Schedule Rule Parameter', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    {!! Form::text('scheduleRuleParameter', $job->scheduleRuleParameter, ['class' => 'form-control']) !!}
                </div>
            </div>

            {!! Form::submit('Save', ['class'=>'btn btn-default pull-right']) !!}

            {!! Form::close()!!}
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Job log</h3>
        </div>
        <div class="panel-body">
            <div id="scheduler-job-log-container" data-action="{{ url('administrator/scheduler/jobLog/'.$jobName) }}"></div>
        </div>
    </div>

@else
    <h4>Job {{ $jobName }} not found</h4>
@endif