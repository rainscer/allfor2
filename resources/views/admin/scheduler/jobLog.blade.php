@foreach($jobLog as $logRow)
    <div>
        <strong>at</strong> {{ $logRow->updated_at }}
        <strong>said</strong> {!! str_replace('\n', '<br />',$logRow->description) !!}</div>
@endforeach
{!! $jobLog->render() !!}