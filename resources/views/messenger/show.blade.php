@extends('user.index')
@section('user_content')

    <div class="container-fluid thread-block user-setting-block">
        <div class="thread-header">
            {{ trans('user.threadHistory') }} <span class="bold">{!! $thread->subject !!}</span>
            <div class="support-online bold">
                @if(isset($support_online) && !$support_online)
                    {{ trans('messenger.operators_busy') }}
                @endif
            </div>
        </div>
        <input data-url="{{ url('messages/getNewMessages') }}" type="hidden" class="thread_id" value="{{ $thread->id }}">
        <div class="message-block">
            @foreach($thread->messages as $message)
                <div class="message-item {{ $message->user->id == Auth::user()->id ? 'me' : 'admin' }}">
                    <div class="message-body">
                        <div class="message-user">
                            @if(in_array($message->id, $new_messages))
                                <span class="badge">{{ trans('user.new') }}</span>
                            @endif
                            @if($message->user->id == Auth::user()->id)
                                {{ trans('user.you') }}
                            @elseif($support)
                                {{ $message->user->getFullName() }}
                            @else
                                {{ trans('user.admin') }}
                            @endif
                            <span>({!! $message->created_at->format('d.m.Y, H:i:s') !!})</span></div>

                        <div class="message-text">{!! $message->body !!}</div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="message-send-block">
            {!! Form::open(['route' => ['messages.update', $thread->id], 'id' => 'send-new-message-form']) !!}
            <div class="form-group message-send-textarea">
                {!! Form::textarea('message', null, ['class' => 'form-control', 'id' => 'threadTextArea', 'size' => '50x3', 'placeholder' => trans('user.messageText')]) !!}
            </div>
            <div class="form-group">
                {!! Form::submit( trans('user.send'), ['class' => 'btn message-submit']) !!}
            </div>
            {!! Form::close() !!}
        </div>

        <a class="btn message-submit" href="{{ url('messages') }}">{{ trans('user.toThreadsList') }}</a>
    </div>

    <script>
        var documentNatTitle = document.title,
                isBlur = false,
                countOfNewMessages = 0;
        $(document).ready(function() {

            var containerThread = $('.message-block'),
                    heightThread = containerThread[0].scrollHeight;
            containerThread.scrollTop(heightThread);
            var newTitle = '';

            function checkForNewMessages() {
                var threadId = $('.thread_id').val(),
                        linkTo = $('.thread_id').data('url') + '/' + threadId,
                        support_online_text = $('.support-online'),
                        height = containerThread[0].scrollHeight;

                $.post(linkTo)
                        .done(function (data) {
                            if (data.result == 'OK') {
                                containerThread.append(data.messages);
                                containerThread.scrollTop(height);
                                support_online_text.text('');
                                if (isBlur) {
                                    countOfNewMessages = countOfNewMessages + data.countMessages;
                                    newTitle = '(' + countOfNewMessages + ') ' + documentNatTitle;
                                    document.title = newTitle;
                                } else {
                                    document.title = documentNatTitle;
                                }
                            }
                        });
            }

            setInterval(checkForNewMessages, 3000);
        });

        $(window).focus(function() {
            isBlur = false;
            document.title = documentNatTitle;
            countOfNewMessages = 0;
        });

        $(window).blur(function() {
            isBlur = true;
        });
    </script>
    <style>
        .support-online{
            font-size: 11px;
            color: #709FFB;
        }
    </style>
@endsection
