<div class="reviews-block">
{{--    <a href="#" class="btn-review-qa">--}}
{{--        {{ trans('product.ask') }}--}}
{{--    </a>--}}
    <div class="form-reviews-result"></div>

    <div class="form-reviews" id="form-qa">
        <button type="button" class="close btn-review-qa-close">×</button>
        {!! Form::open(array('action' => 'ProductController@storeReviewOrQA', 'class' => 'form-review'))!!}
        {!! Form::hidden('product_id', $product->id) !!}
        {!! Form::hidden('type', 'qa', ['class'=>'type']) !!}
        @if (!Auth::check())
            <div class="form-group">
                {!! Form::text('name', null, ['class'=>'form-control qa-name', 'placeholder' => trans('product.username'), 'request'] ) !!}
            </div>
            <div class="form-group">
                {!! Form::email('email', null, ['class'=>'form-control qa-email', 'placeholder' => 'email', 'request'] ) !!}
            </div>
        @else
            <div class="form-group">
                {!! Form::hidden('name', Auth::user()->getFullName(), ['class'=>'form-control qa-name', 'request'] ) !!}
            </div>
            <div class="form-group">
                {!! Form::hidden('email', Auth::user()->email, ['class'=>'form-control'] ) !!}
            </div>
            {{--@if(isset($user_city))
                <div class="form-group">
                    {!! Form::hidden('city', $user_city, ['class'=>'form-control'] ) !!}
                </div>
            @else
                <div class="form-group">
                    {!! Form::text('city', null, ['class'=>'form-control qa-city', 'placeholder' => trans('product.city')] ) !!}
                </div>
            @endif--}}
        @endif
        <div class="form-group message-send-textarea">
            {!! Form::textarea('text', null, ['class'=>'form-control qa-text', 'size' => '50x5',
             'placeholder' => trans('product.yourQuestion'), 'request'] ) !!}
        </div>
        <div class="form-group centered">
            {!! Form::submit(trans('product.ask'), ['class'=>'btn btn-ask']) !!}
        </div>
        {!! Form::close()!!}
        <div class="form-reviews-result"></div>
    </div>
</div>