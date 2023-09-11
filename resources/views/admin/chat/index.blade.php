@extends('layout.default')
@section('content')

    @if (Session::has('error_message'))
        <div class="alert alert-danger" role="alert">
            {!! Session::get('error_message') !!}
        </div>
    @endif
    <div class="container-fluid user-setting-block all-threads" data-url="{{ url('chat/get-chats') }}">

    @include('admin.chat._table')

    </div>

    <script>

        var documentNatTitle = document.title,
                isBlur = false,
                countOfNewMessages = 0;
        $(document).ready(function() {

            var containerThread = $('.all-threads');
            var newTitle = '';

            function checkForNewChats() {
                var linkTo = $('.all-threads').data('url');

                $.post(linkTo)
                        .done(function (data) {
                            if (data.result == 'OK') {
                                containerThread.html(data.chats);

                                if (isBlur && data.countMessages > 0) {
                                    //countOfNewMessages = countOfNewMessages + data.countMessages;
                                    newTitle = '(' + data.countMessages + ') ' + documentNatTitle;
                                    document.title = newTitle;
                                } else {
                                    document.title = documentNatTitle;
                                }
                            }
                        });
            }

            setInterval(checkForNewChats, 2000);
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
