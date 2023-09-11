@extends('user.index')
@section('user_content')

    @if (Session::has('error_message'))
        <div class="alert alert-danger" role="alert">
            {!! Session::get('error_message') !!}
        </div>
    @endif
    <div class="container-fluid user-setting-block all-threads">
        @include('messenger.headerLinks')

        {!! Form::open(['route' => 'messages.delete']) !!}
        <div class="table-responsive">
            <table class="table table-hover">
                <tr class="active">
                    <th>{{ trans('user.topic') }}</th>
                    <th>{{ trans('user.message') }}</th>
                    <th>{{ trans('user.date') }}</th>
                </tr>
                {{-- */  $curRouteTrashed = Request::route()->getName() == 'messages.trashed' ? true : false;  /* --}}
                @if($threads->count() > 0)
                    @foreach($threads as $thread)
                        <?php $class = $thread->isUnread($currentUserId) ? 'bold' : ''; ?>
                        <tr class="normal {!! $curRouteTrashed ? : $class !!}">
                            <td class="padding-right-25 width-16" onclick="window.location.href = '{!! url('messages/' . $thread->id) !!}'">
                                {{ $thread->subject }}
                            </td>
                            <td class="relative width-65" onclick="window.location.href = '{!! url('messages/' . $thread->id) !!}'">
                                @if(!$curRouteTrashed)
                                    @if($thread->userUnreadMessagesCount($currentUserId) > 0)
                                        <span class="new-message-count">
                                    {{ $thread->userUnreadMessagesCount($currentUserId) }}
                                </span>
                                    @endif
                                @endif
                                @if($support)
                                    <?php $class_read_user = $thread->isUnread(head(array_unique($thread->participantsUserIdsWithoutUser(Auth::id())))) ? trans('user.notRead') : ''; ?>
                                    <p>
                                        <small>
                                            <strong>
                                                {{ trans('user.user') }}:
                                            </strong> {!! $thread->participantsString(Auth::id()) !!}
                                            <span class="badge" style="font-size: 10px;">{{ $curRouteTrashed ? '' : $class_read_user }}</span>
                                        </small>
                                    </p>
                                @endif
                                {!! $thread->latestMessage->body !!}</td>
                            {{--<p><small><strong>Автор:</strong> {!! $thread->creator()->name !!}</small></p>--}}
                            <td class="width-19">
                                {!! $thread->created_at->format('d.m.Y, H:i:s') !!}
                                @if($support)
                                    <input type="checkbox" name="delete_threads[]" value="{!!$thread->id!!}">
                                @endif
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
        @if($support)
            @if($curRouteTrashed)
                <div class="form-group" style="margin: 15px 0;">
                    {!! Form::submit(trans('user.restore'), ['name' => 'restore', 'class' => 'btn message-submit']) !!}
                </div>
            @else
                <div class="form-group" style="margin: 15px 0;">
                    {!! Form::submit(trans('user.archive'), ['name' => 'archive', 'class' => 'btn message-submit']) !!}
                </div>
            @endif
            <div class="form-group" style="margin: 15px 0;">
                {!! Form::submit(trans('user.delete'), ['name' => 'delete', 'class' => 'btn message-submit']) !!}
            </div>
        @endif
        {!! Form::close() !!}

        @if ($threads instanceof \Illuminate\Pagination\AbstractPaginator && $threads->lastPage() > 1)
            <div class="threads-pagination">
            {!! $threads->render() !!}
            <div class="goto-page">
                <span class="goto-title">Перейти к странице</span>
                <input type="text" name="thread-goto" class="goto-input" value="{{ $threads->currentPage() }}">
                <input type="hidden" class="goto-input-last-page" value="{{ $threads->lastPage() }}">
                <a href="{{ route('messages') }}" class="goto-action">Перейти</a>
            </div>
            </div>
        @endif

        <a class="btn message-submit" href="#threadModal" data-toggle="modal">{{ trans('user.createThread') }}</a>

        @if($support && count($users) > 0)
            <div class="row">
            <div class="col-md-6">
                <h4 class="all-threads-by-user">
                    {{ trans('user.allThreadsByUser') }}
                </h4>
                {!! Form::open(['route' => 'messages.getBetween']) !!}
                <div class="checkbox">
                    <select class="form-control chosen-select" autocomplete="off" name="recipient">
                        <option value = "-1" selected>
                            {{ trans('user.chooseUser') }}
                        </option>
                        @foreach($users as $id => $userTo)
                            <option value = "{{ $id }}">
                                {{ $userTo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    {!! Form::submit(trans('user.show'), ['class' => 'btn message-submit']) !!}
                </div>
                {!! Form::close() !!}
            </div>
            </div>
        @endif

        @if($support)
            <a class="btn message-submit" href="{{ url('chat') }}">Чаты</a>
        @endif
    </div>
@endsection
