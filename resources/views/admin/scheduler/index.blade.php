@extends('admin.layout.default')
@section('content')

    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
        <h4>Scheduled jobs</h4>
        @if ($jobs)
        <div class="list-group">
            @foreach ($jobs as $key=>$job)
                <a href="{{ url('administrator/scheduler/job/'.$key) }}" class="list-group-item schedule-item-link">
                    @if ($job->active)
                        <span class="pull-right">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                    @endif
                    <strong>{{ $key }}</strong>
                    <div><small>{{ $job->description }}</small></div>
                </a>
            @endforeach
        </div>
        @endif
        <a href="{{ url('administrator/scheduler/clearJobs') }}" onclick="return confirm('Are you sure?')">Clear all jobs</a> |
        <a href="{{ url('administrator/scheduler/refreshJobs') }}">Search for new jobs</a>
    </div>

    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 schedule-item-form-container"></div>

@endsection
