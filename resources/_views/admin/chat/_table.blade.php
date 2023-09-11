
<div class="table-responsive">
    <table class="table table-hover">
        <tr class="active">
            <th>Чат</th>
            <th>{{ trans('user.message') }}</th>
            <th>{{ trans('user.date') }}</th>
        </tr>
        @if($chats->count() > 0)
            {{--  */ $now = \Carbon\Carbon::now();  /* --}}
            @foreach($chats as $chat)
                {{-- */  $user = $chat->participants->first(function($key,$value){
                    return !is_null($value->user_session_id);
                });
                    if($user){
					    $dif = $now->diffInSeconds($user->updated_at);
					}else{
					    $dif = 999;
					}
					 /* --}}

                <tr class="normal {{ $chat->supportUnread ? 'bold' : '' }}">
                    <td class="padding-right-25 width-16" onclick="window.location.href = '{!! url('chat/' . $chat->id) !!}'">
                        Чат №{{ $chat->id }}
                        @if($dif <= 4)
                            <span class="badge" style="color: #65FF65;">Online</span>
                        @endif
                    </td>
                    <td class="relative width-65" onclick="window.location.href = '{!! url('chat/' . $chat->id) !!}'">
                        {!! $chat->latestMessageBody !!}
                    </td>
                    <td class="width-19">
                        {!! $chat->created_at->format('d.m.Y, H:i:s') !!}
                    </td>
                </tr>
            @endforeach
        @else
            <tr class="normal">
                <td>
                    {{ trans('user.noThreads') }}
                </td>
                <td>
                </td>
                <td>
                </td>
            </tr>
        @endif
    </table>
</div>

@if ($chats instanceof \Illuminate\Pagination\AbstractPaginator && $threads->lastPage() > 1)
    <div class="threads-pagination">
        {!! $chats->render() !!}
        <div class="goto-page">
            <span class="goto-title">Перейти к странице</span>
            <input type="text" name="thread-goto" class="goto-input" value="{{ $chats->currentPage() }}">
            <input type="hidden" class="goto-input-last-page" value="{{ $chats->lastPage() }}">
            <a href="{{ route('messages') }}" class="goto-action">Перейти</a>
        </div>
    </div>
@endif
