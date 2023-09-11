<div class="modal fade swipe_block" id="modal-wait-product" tabindex="-1" role="dialog" aria-labelledby="modalForWait" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <button type="button" class="close modal-product-close-btn" data-dismiss="modal" aria-hidden="true">&times;</button>
            <div class="modal-body">
                <h3 class="centered">{{ trans('product.notifyMe') }}</h3>
                <div class="row">
                    <div class="col-sm-4">
                        <img src="" id="waiting-product-img">
                    </div>
                    <div class="col-sm-8">
                        <div id="waiting-product-name" class="bold"></div>
                    </div>
                </div>
                <div>
                    {{ trans('product.enter') }} {{ Auth::check() ? trans('product.yourEmailLow') : trans('product.usernameAndEmail') }}.
                    {{ trans('product.youWillNotified') }}
                    @if(!Auth::check())
                        <div class="bold">
                            {{ trans('product.pleaseAuth') }}
                        </div>
                    @endif
                </div>
                {!! Form::open(['url' => url('waiting-for-product'), 'id' => 'waiting-for-product']) !!}
                {!! Form::hidden('product_id') !!}
                <div class="row">
                    @if(Auth::check())
                        {!! Form::hidden('name', Auth::user()->getFullName()) !!}
                    @else
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('name',trans('product.username')) !!}
                                {!! Form::text('name', null, ['class' => 'form-control name-wait-product', 'required',
                                'placeholder' => trans('product.username')]) !!}
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-{{ Auth::check() ? '12' : '6' }}">
                        <div class="form-group">
                            {!! Form::label('email',trans('product.yourEmail')) !!}
                            {!! Form::email('email', null, ['class' => 'form-control email-wait-product', 'required',
                            'placeholder' => trans('product.yourEmail')]) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group centered submit-gr">
                    {!! Form::submit(trans('product.notifyMe'), ['class' => 'btn btn-default']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>