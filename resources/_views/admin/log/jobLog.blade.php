<div id="jobLog-container">
    <div class="panel panel-success">
        <div class="panel-heading">{{ $jobName }}</div>
            <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                    @foreach($jobLog as $logRow)
                        <tr>
                            <td> {{ $logRow->updated_at }}</td>
                            <td> {!! str_replace('\n', '<br />',$logRow->description) !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                {!! $jobLog->render() !!}
            </div>
        </div>
    </div>
</div>