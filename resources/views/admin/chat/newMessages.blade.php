{{-- */ !isset($chat->messages) ? : $messages = $chat->messages;  /* --}}
@foreach($messages as $message)
    @if(Auth::check() && Auth::user()->name == "support")
        <div class="message-item {{ !$message->participant->support ? 'admin' : 'me' }}">
            <div class="message-body">
                <div class="message-user">
                    @if($message->participant->support)
                        {{ trans('user.you') }}
                    @else
                        {{ trans('user.user') }}
                    @endif
                    <span>({!! $message->created_at->format('d.m.Y, H:i:s') !!})</span></div>

                <div class="message-text">{!! $message->body !!}</div>
            </div>
        </div>
    @else
        <div class="message-item {{ $message->participant->support ? 'admin' : 'me' }}">
            <div class="message-body">
                <div class="message-user">
                    @if(!$message->participant->support)
                        {{ trans('user.you') }}
                    @else
                        {{ trans('user.admin') }}
                    @endif
                    <span>({!! $message->created_at->format('d.m.Y, H:i:s') !!})</span></div>

                <div class="message-text">{!! $message->body !!}</div>
            </div>
        </div>
    @endif
@endforeach