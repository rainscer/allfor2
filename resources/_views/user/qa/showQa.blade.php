@extends('user.index')
@section('user_content')

    <div class="container-fluid thread-block user-setting-block">
        <div class="thread-header">
            {{ trans('user.getQa') }} <span class="bold">{!! $qa->product->name_ru !!}</span>
        </div>
        @if(isset($support) && $support)
            {{-- */ $class_qa = 'admin';
                    $class_answer = 'me';
                    $class_block = 'support';
                    $name_answer = trans('user.you');
                    $name_qa = $qa->user_id ? $qa->user->name : $qa->quest . ', ' . $qa->city; /* --}}
        @else
            {{-- */ $class_qa = 'me';
                    $class_answer = 'admin';
                    $class_block = 'not-support';
                    $name_qa = trans('user.you');
                    $name_answer = trans('user.admin'); /* --}}
        @endif
        <div class="message-block {{ $class_block }}">
            <div class="message-item {{ $class_qa }}">
                <div class="message-body">
                    <div class="message-user">
                        {{ $name_qa }}
                        <span>({!! $qa->created_at->format('d.m.Y, H:i:s') !!})</span></div>

                    <div class="message-text">{!! $qa->text !!}</div>
                </div>
            </div>
            @if($qa->answer)
                <div class="message-item {{ $class_answer }}">
                    <div class="message-body">
                        <div class="message-user">
                            {{ $name_answer }}
                            <span>({!! (new Carbon\Carbon($qa->answered_at))->format('d.m.Y, H:i:s') !!})</span>
                        </div>
                        <div class="message-text">{!! $qa->answer !!}</div>
                    </div>
                </div>
            @endif
        </div>

        @if(isset($support) && $support)
        <div class="message-send-block">
            {!! Form::open(['route' => ['user.updateQa', $qa->id], 'id' => 'send-new-message-form']) !!}
            <div class="form-group message-send-textarea">
                {!! Form::textarea('answer', null, ['class' => 'form-control', 'id' => 'threadTextArea', 'size' => '50x3', 'placeholder' => trans('user.messageText')]) !!}
            </div>
            <div class="form-group">
                {!! Form::submit( trans('user.send'), ['class' => 'btn message-submit']) !!}
            </div>
            {!! Form::close() !!}
        </div>
        @endif

        <a class="btn message-submit" href="{{ url('user/getQa') }}">{{ trans('user.toQaList') }}</a>
        @if($qa->answer)
            <div class="qa-description">* Если ответ был не в полном обьеме или у Вас остались ещё вопросы воспользуйтесь формой
                <a href="#threadModal" data-toggle="modal">{{ trans('user.askQuestion') }}</a>
            </div>
        @endif
    </div>
    <style>
        .not-support{
            border-bottom: 1px solid #C5C5C5;
            margin-bottom: 15px;
        }
        .qa-description{
            margin-top: 10px;
        }
    </style>
@endsection
