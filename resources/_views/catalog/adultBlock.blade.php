<div class="block-adult">
    <input type="hidden" id="url-adult" value="{{ url('/set-adult') }}">
    <div class="block-adult-form">
        <div class="img-adult">
            <img src="{{ asset('/images/18_warn.png') }}">
        </div>
        <div class="text-adult">
            <h3>{{ trans('product.warning') }}</h3>
            <div>
                {{ trans('product.warningText') }}
            </div>
        </div>
        <div class="buttons-adult">
            <a href="#" class="btn yes-adult">{{ trans('product.18years') }}</a>
            <a href="{{ url('/') }}" class="btn no-adult">{{ trans('product.leaveCategory') }}</a>
        </div>
    </div>
</div>