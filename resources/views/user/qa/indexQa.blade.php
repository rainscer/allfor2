@extends('user.index')
@section('user_content')

    <div class="container-fluid user-setting-block all-threads">
        @include('messenger.headerLinks')
        {!! Form::open(['route' => 'qa.delete']) !!}
        {{-- */  $curRouteTrashed = Request::route()->getName() == 'user.getQaTrashed' ? true : false;  /* --}}
        <div class="table-responsive">
            <table class="table table-hover">
                <tr class="active">
                    <th>{{ trans('user.productName') }}</th>
                    <th>{{ trans('user.question') }}</th>
                    <th>{{ trans('user.date') }}</th>
                </tr>
                @if($qas->count() > 0)
                    @foreach($qas as $qa)
                        @if(isset($support) && $support)
                            {{-- */ $class_new = $qa->new ? 'bold' : '';
                                    $new_label = $qa->new ? trans('qa.new') : ''; /* --}}
                        @else
                            {{-- */ $class_new = $qa->user_unread ? 'bold' : '';
                                    $new_label = ''; /* --}}
                        @endif
                        <tr class="normal {{ $class_new }}">
                            <td class="width-20">
                                <a href="{{ route('product.url',[$qa->product->upi_id, $qa->product->slug]) }}" class="link_modal">
                                    {{ $qa->product->name_en }}
                                </a>
                            </td>
                            <td class="width_50" onclick="window.location.href = '{!! url('user/showQa/' . $qa->id) !!}'">
                                <div>
                                    <span class="badge" style="font-size: 10px;">{{ $new_label }}</span>
                                    <span class="badge" style="font-size: 10px;">{{ $qa->answer ? '' : trans('qa.noAnswerYet') }}</span>
                                </div>
                                {{ $qa->text }}
                            </td>
                            <td class="width-19">
                                {!! $qa->created_at->format('d.m.Y, H:i:s') !!}
                                @if(isset($support) && $support)
                                    <input type="checkbox" name="delete_qas[]" value="{!!$qa->id!!}">
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="normal">
                        <td>
                            {{ trans('user.noQa') }}
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

        @if ($qas instanceof \Illuminate\Pagination\AbstractPaginator && $qas->lastPage() > 1)
            <div class="threads-pagination">
                {!! $qas->render() !!}
                <div class="goto-page">
                    <span class="goto-title">{{ trans('qa.gotopage') }}</span>
                    <input type="text" name="thread-goto" class="goto-input" value="{{ $qas->currentPage() }}">
                    <input type="hidden" class="goto-input-last-page" value="{{ $qas->lastPage() }}">
                    <a href="{{ route('user.getQa') }}" class="goto-action">{{ trans('qa.goto') }}</a>
                </div>
            </div>
        @endif

    </div>
    <style>
        .colored-af{
            color: #AFAFAF;
            font-style: italic;
        }
        .width_50{
            width: 50%;
        }
    </style>
@endsection