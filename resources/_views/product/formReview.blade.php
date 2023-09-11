<div class="reviews-block">
    <a href="#" class="btn-review-qa">
        {{ trans('product.giveFeedback') }}
    </a>
    <div class="form-reviews-result"></div>

    <div class="form-reviews" id="form-reviews">
        <button type="button" class="close btn-review-qa-close">×</button>
        {!! Form::open(array('action' => 'ProductController@storeReviewOrQA', 'id' => 'form-review', 'class' => 'form-review'))!!}
        {!! Form::hidden('product_id', $product->id) !!}
        {!! Form::hidden('type', 'review', ['class'=>'type']) !!}
        {!! Form::hidden('rating', null, ['class'=>'form-control', 'id' => 'rating'] ) !!}
        @if (!Auth::check())
            <div class="form-group">
                {!! Form::text('name', null, ['class'=>'form-control review-name', 'placeholder' => trans('product.username')] ) !!}
            </div>
            <div class="form-group">
                {!! Form::text('city', null, ['class'=>'form-control review-city', 'placeholder' => trans('product.city')] ) !!}
            </div>
        @else
            <div class="form-group">
                {!! Form::hidden('name', Auth::user()->getFullName(), ['class'=>'form-control review-name'] ) !!}
            </div>
            @if(isset($user_city))
                <div class="form-group">
                    {!! Form::hidden('city', $user_city, ['class'=>'form-control'] ) !!}
                </div>
                @else
                <div class="form-group">
                    {!! Form::text('city', null, ['class'=>'form-control review-city', 'placeholder' => trans('product.city')] ) !!}
                </div>
            @endif
        @endif
        <div id="star"></div>
        <div class="form-group message-send-textarea">
            {!! Form::textarea('text', null, ['class'=>'form-control review-text', 'size' => '50x5',
             'placeholder' => trans('product.yourFeedback')] ) !!}
        </div>


        <div class="row review-success">
            <div class="col-xs-12">
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span>Прикрепить фото...</span>
                <!-- The file input field used as target for the file upload widget -->
                <input id="fileupload-rewiev" type="file" name="files[]" multiple>
            </span>
                <br>
                <br>
                <!-- The global progress bar -->
                <div id="progress" class="progress">
                    <div class="progress-bar progress-bar-success"></div>
                </div>
                <!-- The container for the uploaded files -->
                <div id="files" class="files radio-inputs without-select">

                </div>
            </div>
        </div>

        <div class="form-group centered">
            {!! Form::submit( trans('product.giveFeedback'), ['class'=>'btn btn-ask']) !!}
        </div>
        {!! Form::close()!!}
        <div class="form-reviews-result"></div>
    </div>
</div>