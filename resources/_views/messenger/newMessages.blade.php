@foreach($new_messages as $message)
    <div class="message-item {{ $message->user->id == Auth::user()->id ? 'me' : 'admin' }}">
        <div class="message-body">
            <div class="message-user">
                @if($message->user->id != Auth::user()->id)
                    <span class="badge">{{ trans('user.new') }}</span>
                @endif
                @if($message->user->id == Auth::user()->id)
                        {{ trans('user.you') }}
                @elseif(isset($support) && $support)
                    {{ $message->user->getFullName() }}
                @else
                        {{ trans('user.admin') }}
                @endif
                <span>({!! $message->created_at->format('d.m.Y, H:i:s') !!})</span></div>

            <div class="message-text">{!! $message->body !!}</div>
        </div>
    </div>
@endforeach