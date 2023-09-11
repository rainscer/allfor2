@extends('admin.layout.default')
@section('content')
    <div class="panel panel-success">
        <div class="panel-heading">Рассылка
            <button type="button" class="btn btn-success navbar-right delete-admin-btn"
                    data-owner-id="{{ url('administrator/mail/delete') }}">Удалить</button>

            @if(Request::route()->getName() == 'mail.archived')
                <button type="button" class="btn btn-success navbar-right delete-admin-btn"
                        data-owner-id="{{ url('administrator/mail/restore') }}" style="margin-right: 10px !important;">
                    Востановить
                </button>
            @else
                <button type="button" class="btn btn-success navbar-right delete-admin-btn"
                        data-owner-id="{{ url('administrator/mail/archive') }}" style="margin-right: 10px !important;">
                    Архивировать
                </button>
            @endif

            <a href="{{ url('administrator/mail/create') }}" class="btn btn-success navbar-right" style="
            margin-right: 10px !important;
    margin: -7px -10px 0px 0px;">Создать</a>

        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped" id="review_table">
                    <tr>
                        <th>Тема</th>
                        <th>Получатели</th>
                        <th>Кол-во рассылок</th>
                        {{--<th>Scheduled</th>--}}
                        <th></th>
                    </tr>
                    @foreach($mails as $mail)
                        <tr id="item-{{ $mail->id }}">
                            <td>
                                <a href="{{ url('administrator/mail/show/' . $mail->id) }}">{{ $mail->subject }}</a>
                            </td>
                            <td>
                                {{ str_replace(PHP_EOL, ', ', $mail->participants) }}
                            </td>
                            <td>
                                {{ $mail->hit }}
                            </td>
                            {{--<td>
                                <input type="checkbox" id="mail-active-box-{{ $mail->id }}"
                                       data-owner-id ="{{ url('administrator/mail/setScheduled') }}"
                                       class="mail-active change-active-admin" name="mail_scheduled_item[]"
                                       value="{{ $mail->id }}"
                                       {{ $mail->scheduled == 1 ? 'checked' : '' }}
                                       >
                                <label for="review-active-box-{{ $mail->id }}"><span></span></label>
                            </td>--}}
                            <td>
                                <input type="checkbox" class="delete-box-admin" id="mail-box-{{ $mail->id }}"
                                       class="mail-delete" name="mail_item[]" value="{{ $mail->id }}">
                                <label for="mail-box-{{ $mail->id }}"><span></span></label>
                            </td>
                            @endforeach
                        </tr>
                </table>
            </div>
            {!! $mails->render()  !!}
        </div>
    </div>
@endsection