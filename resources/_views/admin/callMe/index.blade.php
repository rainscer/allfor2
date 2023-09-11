@extends('admin.layout.default')
@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Заказные звонки</div>
        <div class="panel-body">
            <input type="hidden" id="set-completed" value="{{ url('administrator/call-me/set-completed') }}">
            <div class="table-responsive">
                <table class="article_table table table-striped" id="article_table">
                    <tr>
                        <th>Номер телефона</th>
                        <th>Дата перезвона</th>
                        <th></th>
                    </tr>
                    @foreach($call_mes as $call_me)
                        <tr id="item-{{ $call_me->id }}" class="{{ $call_me->completed ? "call-completed" : "call-waiting" }}">
                            <td>{{ $call_me->phone }}</td>
                            <td>{{ $call_me->call_time ?
                            $call_me->call_time : 'Сегодня' }}</td>
                            <td>
                                <button data-owner-id="{{ $call_me->id }}" class="call-me-complete btn btn-{{ $call_me->completed ? "success" : "danger" }}">
                                    {{ $call_me->completed ? "Completed" : "Waiting" }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @if ($call_mes instanceof \Illuminate\Pagination\AbstractPaginator)
                {!! $call_mes->render() !!}
            @endif
        </div>
    </div>
    <script>
        $(document).on('click','.call-me-complete',function(){
            var el = $(this);
            el.removeClass('btn-danger').addClass('btn-info').text('Working...');

            $.post($('#set-completed').val(),
                    {
                        call_id : el.data('ownerId')
                }
            ).done(function(){
                        $('#item-' + el.data('ownerId')).addClass('call-completed');
                        el.removeClass('btn-info').addClass('btn-success').text('Completed');
                    });
        });
    </script>
    <style>
        .call-completed{
            opacity: 0.5;
        }
    </style>
@endsection


