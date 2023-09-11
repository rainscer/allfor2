<div class="modal fade" id="threadModal" tabindex="-1" role="dialog" aria-labelledby="modalForProduct" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <button type="button" class="close modal-product-close-btn" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="modal-body">

                <div class="container-fluid thread-block">
                    <h4 class="centered">{{ trans('user.createNewThread') }}</h4>
                    {!! Form::open(['route' => 'messages.store', 'id' => 'new-thread']) !!}
                    <div>
                        <!-- Subject Form Input -->
                        <div class="form-group without-radius">
                            {!! Form::label('subject', trans('user.topic'), ['class' => 'control-label']) !!}
                            {!! Form::text('subject', null, ['class' => 'form-control subject-thread']) !!}
                        </div>

                        <!-- Message Form Input -->
                        <div class="form-group without-radius message-send-textarea">
                            {!! Form::label('message', trans('user.messageText'), ['class' => 'control-label']) !!}
                            {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
                        </div>

                        @if(isset($support) && $support && isset($users) && count($users) > 0)
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
                            @endif

                            <div class="form-group">
                                {!! Form::submit(trans('user.create'), ['class' => 'btn message-submit']) !!}
                            </div>
                    </div>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>
    </div>
</div>

