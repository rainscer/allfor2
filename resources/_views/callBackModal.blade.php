<div class="modal fade" id="callBack" tabindex="-1" role="dialog" aria-labelledby="modalForCallBack" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <button type="button" class="close modal-product-close-btn" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="modal-body">
                <div class="container-fluid call-back-fluid">
                    <h2 class="centered">Обратная связь</h2>
                    <ul class="callBack nav nav-tabs">
                        <li class="active">
                            <a class="mess-call" data-toggle="tab" href="#panel-write">
                                <span class="call-back-mess-icon"></span>
                                <span class="call-back-text">Напишите нам</span>
                            </a>
                        </li>
                        <li>
                            <a class="call-me" data-toggle="tab" href="#panel-call-me">
                                <span class="call-back-call-me-icon"></span>
                                <span class="call-back-text">Мы перезвоним</span>
                            </a>
                        </li>
                        <li>
                            <a class="call-you" data-toggle="tab" href="#panel-call-you">
                                <span class="call-back-call-you-icon"></span>
                                <span class="call-back-text">Перезвоните нам</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="panel-write" class="tab-pane fade in active">
                            {!! Form::open(['route' => 'chat.create', 'id' => 'new-message2']) !!}
                            <div>
                                <!-- Message Form Input -->
                                <div class="form-group without-radius message-send-textarea">
                                    {!! Form::label('body', 'Введите текст сообщения', ['class' => 'control-label message-call-back-label']) !!}
                                    {!! Form::textarea('body', null, ['class' => 'form-control message-call-back', 'size' => '50x6']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::submit('Отправить', ['class' => 'btn message-submit']) !!}
                                </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                        <div id="panel-call-me" class="tab-pane fade">

                            {{-- */ $now = \Carbon\Carbon::now() /* --}}
                            @if($now->dayOfWeek == 7 || ($now->dayOfWeek == 6 && $now->hour > 12) || $now->hour > 22 )
                            <div>
                                <div class="call-me-text1">
                                    К сожалению мы сейчас не в сети.
                                </div>
                                <div class="call-me-text2">
                                    Хотите мы перезвоним вам точно в
                                </div>
                            </div>
                            {!! Form::open(['url' => 'call-me-save', 'id' => 'call-me']) !!}
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-inline clearfix" style="margin: 15px 0;">
                                        <div class="quant fl-left">
                                            <input type='button' value='' class='qtyminus' field='qty_hours' data-min-value="8" data-default-value="08" />
                                            <input type='text' name='qty_hours' value='08'  data-min-value="8" data-max-value="22" data-default-value="08" class='qty qty_hours' />
                                            <input type='button' value='' class='qtyplus' field='qty_hours' data-max-value="22" data-default-value="08" />
                                        </div>

                                        <div class="quant fl-right">
                                            <input type='button' value='' class='qtyminus' field='qty_minuts' data-min-value="0" data-default-value="00" />
                                            <input type='text' name='qty_minuts' value='00' data-min-value="0" data-max-value="59" data-default-value="00" class='qty qty_minuts' />
                                            <input type='button' value='' class='qtyplus' field='qty_minuts' data-max-value="59" data-default-value="00" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::text('phone',null, ['class'=>'form-control phone', 'require', 'placeholder' => '+38(0__) ___-____'] ) !!}
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div style="margin: 15px 0;">
                                    <select class="form-control day-select" name="day_of_week">
                                        <option value="MON">Понедельник</option>
                                        <option value="TUE">Вторник</option>
                                        <option value="WED">Среда</option>
                                        <option value="THU">Четверг</option>
                                        <option value="FRI">Пятница</option>
                                        <option value="SAT">Суббота</option>
                                    </select>
                                        </div>
                                    <div class="form-group call-me-btn-group">
                                        {!! Form::submit('Заказать звонок', ['class'=>'btn call-me-btn'] ) !!}
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}

                            @else
                                <div style="margin-bottom: 20px;">
                                    <div class="call-me-text3">
                                        Введите свой номер телефона и наши операторы свяжутся с вами в ближайшее время
                                    </div>
                                </div>
                                {!! Form::open(['url' => 'call-me-save', 'id' => 'call-me']) !!}
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            {!! Form::text('phone',null, ['class'=>'form-control phone', 'require', 'placeholder' => '+38(0__) ___-____'] ) !!}
                                        </div>
                                        <div class="form-group call-me-btn-group">
                                            {!! Form::submit('Перезвоните', ['class'=>'btn call-me-btn'] ) !!}
                                        </div>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                                <div style="color: #888888; margin-top: 20px;">
                                    <p>
                                        ПН-ПТ: 08:00 - 22:00, СБ: 08:00 - 12:00, НД: Выходной
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div id="panel-call-you" class="tab-pane fade">
                            <div class="row" style="color: #888888;">
                                <div class="col-sm-8">
                                    <div class="call-you-text">
                                        Вы всегда можете позвонить нам по одному из указанных номеров
                                    </div>
                                    <div class="phone-numbers">
                                        <div>
                                        <span class="phone-number-icon">
                                            <img src="{{ asset('/images/voda.png') }}">
                                        </span>
                                        <span>
                                            066 123 45 67
                                        </span>
                                        </div>
                                        <div>
                                        <span class="phone-number-icon">
                                            <img src="{{ asset('/images/kiev.png') }}">
                                        </span>
                                        <span>
                                            067 123 45 67
                                        </span>
                                        </div>
                                    </div>
                                    <div class="work-hours">
                                        <div class="row">
                                            <div class="col-sm-3 col-xs-5">
                                                <p>
                                                    ПН-ПТ:
                                                </p>
                                                <p>
                                                    СБ:
                                                </p>
                                                <p>
                                                    НД:
                                                </p>
                                                </div>
                                            <div class="col-sm-9 col-xs-7">
                                                <p>
                                                    08:00 - 22:00
                                                </p>
                                                <p>
                                                    08:00 - 12:00
                                                </p>
                                                <p>
                                                    Выходной
                                                </p>
                                            </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

