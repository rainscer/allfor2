<div class="modal fade" id="callBack" tabindex="-1" role="dialog" aria-labelledby="modalForCallBack" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <button type="button" class="close modal-product-close-btn" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="modal-body">
                <div class="container-fluid call-back-fluid">
                    <h2 class="centered">{{ trans('callback.title') }}</h2>
                    <ul class="callBack nav nav-tabs">
                        <li class="active">
                            <a class="mess-call" data-toggle="tab" href="#panel-write">
                                <span class="call-back-mess-icon"></span>
                                <span class="call-back-text">{{ trans('callback.textUs') }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="call-me" data-toggle="tab" href="#panel-call-me">
                                <span class="call-back-call-me-icon"></span>
                                <span class="call-back-text">{{ trans('callback.callMe') }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="call-you" data-toggle="tab" href="#panel-call-you">
                                <span class="call-back-call-you-icon"></span>
                                <span class="call-back-text">{{ trans('callback.callYou') }}</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="panel-write" class="tab-pane fade in active">
                            {!! Form::open(['route' => 'chat.create', 'id' => 'new-message2']) !!}
                            <div>
                                <!-- Message Form Input -->
                                <div class="form-group without-radius message-send-textarea">
                                    {!! Form::label('body', trans('callback.messageText'), ['class' => 'control-label message-call-back-label']) !!}
                                    {!! Form::textarea('body', null, ['class' => 'form-control message-call-back', 'size' => '50x6']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::submit(trans('callback.send') , ['class' => 'btn message-submit']) !!}
                                </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                        <div id="panel-call-me" class="tab-pane fade">

                            {{-- */ $now = \Carbon\Carbon::now() /* --}}
                            @if($now->dayOfWeek == 7 || ($now->dayOfWeek == 6 && $now->hour > 12) || $now->hour > 22 )
                            <div>
                                <div class="call-me-text1">
                                    {{ trans('callback.offline') }}
                                </div>
                                <div class="call-me-text2">
                                    {{ trans('callback.callTime') }}
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
                                        {!! Form::text('phone',null, ['class'=>'form-control phone', 'require', 'placeholder' =>  trans('callback.phonePlaceholder') ] ) !!}
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div style="margin: 15px 0;">
                                    <select class="form-control day-select" name="day_of_week">
                                        <option value="MON">{{ trans('callback.monday') }}</option>
                                        <option value="TUE">{{ trans('callback.tuesday') }}</option>
                                        <option value="WED">{{ trans('callback.wednesday') }}</option>
                                        <option value="THU">{{ trans('callback.thursday') }}</option>
                                        <option value="FRI">{{ trans('callback.friday') }}</option>
                                        <option value="SAT">{{ trans('callback.saturday') }}</option>
                                    </select>
                                        </div>
                                    <div class="form-group call-me-btn-group">
                                        {!! Form::submit(trans('callback.orderCall'), ['class'=>'btn call-me-btn'] ) !!}
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}

                            @else
                                <div style="margin-bottom: 20px;">
                                    <div class="call-me-text3">
                                        {{ trans('callback.form') }}
                                    </div>
                                </div>
                                {!! Form::open(['url' => 'call-me-save', 'id' => 'call-me']) !!}
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            {!! Form::text('phone',null, ['class'=>'form-control phone', 'require', 'placeholder' => '+38(0__) ___-____'] ) !!}
                                        </div>
                                        <div class="form-group call-me-btn-group">
                                            {!! Form::submit(trans('callback.formSubmit'), ['class'=>'btn call-me-btn'] ) !!}
                                        </div>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                                <div style="color: #888888; margin-top: 20px;">
                                    <p>
                                        {{ trans('callback.text1') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div id="panel-call-you" class="tab-pane fade">
                            <div class="row" style="color: #888888;">
                                <div class="col-sm-8">
                                    <div class="call-you-text">
                                        {{ trans('callback.text2') }}
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
                                                    {{ trans('callback.mon-fri') }}
                                                </p>
                                                <p>
                                                    {{ trans('callback.sat') }}
                                                </p>
                                                <p>
                                                    {{ trans('callback.sun') }}
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
                                                    {{ trans('callback.weekend') }}
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

