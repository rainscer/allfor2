@extends('layout.default')
@section('content')

    <div class="container-fluid thread-block user-setting-block" style="position: relative;">
        <div class="thread-header">
            {{ trans('user.threadHistory') }}
        </div>
        <input data-url="{{ url('chat/getNewMessages') }}" type="hidden" class="thread_id" value="{{ $chat->id }}">
        <div class="message-block">
            @if(isset($chat->messages))

                @include('admin.chat.newMessages')

            @endif
        </div>

        <div class="message-send-block" style="position: relative;">
            <div class="isTyping-block" style="position: absolute; top: -20px;left: 15px;color: #C1C1C1;font-style: italic; opacity: 0;">
                <img src="{{ asset('/images/typing.gif') }}" style="padding-right: 5px;"><span class="isTyping"></span> </div>
            {!! Form::open(['route' => ['chat.new.message', $chat->id], 'id' => 'send-new-chat-message-form']) !!}
            <div class="form-group message-send-textarea">
                {!! Form::textarea('message', null, ['class' => 'form-control', 'id' => 'threadTextArea', 'size' => '50x3', 'placeholder' => trans('user.messageText')]) !!}
            </div>
            <div class="form-group">
                {!! Form::submit( trans('user.send'), ['class' => 'btn message-submit']) !!}
            </div>
            {!! Form::close() !!}
        </div>

        @if(Auth::check() && Auth::user()->name == "support")
            <div class="user_online" style="
    position: absolute;
    top: 11px;
    right: 25px;
    font-weight: 900;
    color: green;"></div>
            <a class="btn message-submit" href="{{ url('chat') }}">К списку чатов</a>
        @endif
    </div>

    <script>
        $(document).on('submit', '#send-new-chat-message-form',function(e){
            e.preventDefault();
            var containerThread = $('.message-block');
            var textaereaMes = $(this).find('textarea'),
                    height = containerThread[0].scrollHeight;

            textaereaMes.css({'border-color': '#ccc'});
            if(textaereaMes.val() == ''){
                textaereaMes.css({'border-color': 'red'});
            }else{
                $.post($(this).attr('action'),
                        $(this).serialize())
                        .done(function (data) {
                            if (data.result == 'OK') {
                                containerThread.append(data.messages);
                                containerThread.scrollTop(height);
                                textaereaMes.val('');
                            }
                        });
            }
        });

        $('#threadTextArea').keydown(function (e) {
            if (e.ctrlKey && e.keyCode == 13) {
                $('#send-new-chat-message-form').submit();
            }
        });

        var documentNatTitle = document.title,
                isBlur = false,
                countOfNewMessages = 0;
        $(document).ready(function() {

            var textarea = $('#threadTextArea');
            var typingStatus = false;
            var lastTypedTime = new Date(0); // it's 01/01/1970, actually some time in the past
            var typingDelayMillis = 6000;

            function refreshTypingStatus() {
                if (!textarea.is(':focus') || textarea.val() == '' || new Date().getTime() - lastTypedTime.getTime() > typingDelayMillis) {
                    typingStatus = true;
                } else {
                    typingStatus = false;
                }
            }
            function updateLastTypedTime() {
                lastTypedTime = new Date();
            }

            textarea.keypress(updateLastTypedTime);
            textarea.blur(refreshTypingStatus);


            var containerThread = $('.message-block'),
                    heightThread = containerThread[0].scrollHeight;
            containerThread.scrollTop(heightThread);
            var newTitle = '';

            function checkForNewMessages() {
                var threadId = $('.thread_id').val(),
                        linkTo = $('.thread_id').data('url') + '/' + threadId,
                        isTyping = $('.isTyping'),
                        isTypingBlock = $('.isTyping-block'),
                        height = containerThread[0].scrollHeight,
                        user_online = $('.user_online');

                if (!textarea.is(':focus') || textarea.val() == '' || new Date().getTime() - lastTypedTime.getTime() > typingDelayMillis) {
                    typingStatus = false;
                } else {
                    typingStatus = true;
                }

                $.post(linkTo,
                        {
                            typing : typingStatus
                        })
                        .done(function (data) {
                            if (data.result == 'OK') {
                                if(data.online > 4){
                                    user_online.css({ 'color' : 'red' }).text('Offline');
                                }else{
                                    user_online.css({ 'color' : 'green' }).text('Online');
                                }
                                if(data.messages != '') {
                                    containerThread.append(data.messages);
                                    containerThread.scrollTop(height);
                                }
                                if(data.isTyping == true) {
                                    isTyping.text(data.userTyping);
                                    isTypingBlock.animate({
                                        opacity: 1
                                    }, 200 );
                                }else{
                                    isTyping.text('');
                                    isTypingBlock.animate({
                                        opacity: 0
                                    }, 10 );
                                }
                                if (isBlur && (countOfNewMessages > 0 || data.countMessages > 0)) {
                                    countOfNewMessages = countOfNewMessages + data.countMessages;
                                    newTitle = '(' + countOfNewMessages + ') ' + documentNatTitle;
                                    document.title = newTitle;
                                } else {
                                    document.title = documentNatTitle;
                                }
                            }
                        });
            }

           setInterval(checkForNewMessages, 2000);
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
@endsection
