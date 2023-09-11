<div class="threads-links">
    @if(Auth::user()->name == 'support')
        {{-- */ $qaCount = Auth::user()->newQuestionsCount() /* --}}
    @else
        {{-- */ $qaCount = Auth::user()->newAnswersCount() /* --}}
    @endif
    {{-- */ $threadMessagesNewCount = Auth::user()->newMessagesCount() /* --}}
    @if(Request::route()->getName() == 'messages')
        <span class="bold">{{ trans('user.allThreads') }} ({{ $threadMessagesNewCount }})</span>
        <a href="{{ url('messages/new') }}">{{ trans('user.newThread') }} ({{ $threadMessagesNewCount }})</a>
        <a href="{{ url('user/getQa') }}">{{ trans('user.getQa') }} ({{ $qaCount }})</a>
        @if(isset($support) && $support)
            <a href="{{ url('messages/trashed') }}">{{ trans('user.trashedThreads') }}</a>
            <a href="{{ url('user/getQa/trashed') }}">{{ trans('user.getQaTrashed') }}</a>
        @endif
    @elseif(Request::route()->getName() == 'messages.new')
        <a href="{{ url('messages') }}">{{ trans('user.allThreads') }} ({{ $threadMessagesNewCount }})</a>
        <span class="bold">{{ trans('user.newThread') }} ({{ $threadMessagesNewCount }})</span>
        <a href="{{ url('user/getQa') }}">{{ trans('user.getQa') }} ({{ $qaCount }})</a>
        @if(isset($support) && $support)
            <a href="{{ url('messages/trashed') }}">{{ trans('user.trashedThreads') }}</a>
            <a href="{{ url('user/getQa/trashed') }}">{{ trans('user.getQaTrashed') }}</a>
        @endif
    @elseif(Request::route()->getName() == 'messages.trashed')
        <a href="{{ url('messages') }}">{{ trans('user.allThreads') }} ({{ $threadMessagesNewCount }})</a>
        <a href="{{ url('messages/new') }}">{{ trans('user.newThread') }} ({{ $threadMessagesNewCount }})</a>
        <a href="{{ url('user/getQa') }}">{{ trans('user.getQa') }} ({{ $qaCount }})</a>
        @if(isset($support) && $support)
            <span class="bold">{{ trans('user.trashedThreads') }}</span>
            <a href="{{ url('user/getQa/trashed') }}">{{ trans('user.getQaTrashed') }}</a>
        @endif
    @elseif(Request::route()->getName() == 'user.getQa')
        <a href="{{ url('messages') }}">{{ trans('user.allThreads') }} ({{ $threadMessagesNewCount }})</a>
        <a href="{{ url('messages/new') }}">{{ trans('user.newThread') }} ({{ $threadMessagesNewCount }})</a>
        <span class="bold">{{ trans('user.getQa') }} ({{ $qaCount }})</span>
        @if(isset($support) && $support)
            <a href="{{ url('messages/trashed') }}">{{ trans('user.trashedThreads') }}</a>
            <a href="{{ url('user/getQa/trashed') }}">{{ trans('user.getQaTrashed') }}</a>
        @endif
    @elseif(Request::route()->getName() == 'user.getQaTrashed')
        <a href="{{ url('messages') }}">{{ trans('user.allThreads') }} ({{ $threadMessagesNewCount }})</a>
        <a href="{{ url('messages/new') }}">{{ trans('user.newThread') }} ({{ $threadMessagesNewCount }})</a>
        <a href="{{ url('user/getQa') }}">{{ trans('user.getQa') }} ({{ $qaCount }})</a>
        @if(isset($support) && $support)
            <a href="{{ url('messages/trashed') }}">{{ trans('user.trashedThreads') }}</a>
            <span class="bold">{{ trans('user.getQaTrashed') }}</span>
        @endif
    @endif
</div>