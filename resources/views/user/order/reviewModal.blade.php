<div class="container-fluid thread-block">
    <h4 class="centered title-ajax">
        {{ trans('product.giveFeedback') }}
    </h4>

    {!! Form::open(array('action' => 'ProductController@storeReviewOrQA', 'id' => 'form-review', 'class' => 'form-review-order'))!!}
    {!! Form::hidden('product_id', $product->id) !!}
    {!! Form::hidden('type', 'review', ['class'=>'type']) !!}
    {!! Form::hidden('rating', null, ['class'=>'form-control', 'id' => 'rating'] ) !!}

    <div class="form-group">
        {!! Form::hidden('name', Auth::user()->getFullName(), ['class'=>'form-control review-name'] ) !!}
    </div>

    <div class="form-group">
        {!! Form::text('city', null, ['class'=>'form-control review-city', 'placeholder' => trans('product.city')] ) !!}
    </div>
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

<style>
    #myModal .modal-body {
        min-height: 100px;
    }
</style>

<script>

    $(function () {
        'use strict';
        // Change this to the location of your server-side upload handler:
        var url = '/uploadImage/';

        $('#fileupload-rewiev').fileupload({
            url: url + 'review',
            dataType: 'json',
            singleFileUploads : false,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            disableImageResize: /Android(?!.*Chrome)|Opera/
                    .test(window.navigator && navigator.userAgent),
            done: function (e, data) {

                $.each(data.result.files, function (index, file) {
                    if($('#block-icon-' + file.id).length == 0) {
                        var node = $('<div/>')
                                .attr('class', 'menu-image-preview-block col-md-2 col-lg-2 col-xs-6 col-sm-4 menu-preview-icon')
                                .attr('id', 'block-icon-' + file.id)
                                .append($('<img/>')
                                        .attr('src', file.name)
                                        .attr('class', 'menu-image-preview-review'))
                                .append($('<input/>')
                                        .attr('type', 'checkbox')
                                        .attr('name', 'image_review[]')
                                        .attr('value', file.name)
                                        .attr('checked', true)
                                        .attr('class', 'menu-image-preview-checkbox'))
                                .append($('<button/>')
                                        .attr('type', 'button')
                                        .attr('class', 'delete-icon-btn')
                                        .attr('data-owner-id', file.id)
                                        .attr('data-url', '/deleteImage/review'));

                        node.appendTo('#files');
                    }
                });
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                        'width',
                        progress + '%'
                );
            }
        }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });

    $(document).ready(function () {

        $('#star').raty({
            number: 5,
            path: '/images',
            target: '#rating',
            targetType: 'score',
            targetKeep: true
        });

        $('.star_review').raty({
            number: 5,
            path: '/images',
            readOnly: true,
            score: function () {
                return $(this).attr('data-score');
            }
        });

    });
</script>
