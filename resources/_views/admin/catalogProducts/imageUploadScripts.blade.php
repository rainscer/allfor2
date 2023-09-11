<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<script src="{{ elixir("js/file-upload.js") }}"></script>

<script>
    /*jslint unparam: true */
    /*global window, $ */
    function bindFileUpload(type, selector, formName) {

        // Change this to the location of your server-side upload handler:
        var url = '{{ url('administrator/uploadImage') }}',
                deleteUrl = '{{ url('administrator/deleteImage') }}',
                maxFiles = 10;

        $('#fileupload').fileupload({
            url: url + '/' + type,
            dataType: 'json',
            singleFileUploads: false,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            disableImageResize: /Android(?!.*Chrome)|Opera/
                    .test(window.navigator && navigator.userAgent),
            /*imageForceResize: true,
             imageCrop : true,
             imageMinWidth : 800,
             imageMinHeight : 600,
             imageMaxWidth : 800,
             imageMaxHeight : 600,*/
            done: function (e, data) {

                $.each(data.result.files, function (index, file) {
                    if ($('#block-' + file.id).length == 0) {
                        var node = $('<li/>')
                                .attr('class', 'upi-image-preview-block ui-state-default solid-border uploaded-item')
                                .attr('id', 'block-' + file.id)
                                .append($('<div/>').append($('<img/>')
                                        .attr('src', file.image_url)
                                        .attr('class', 'upi-image-preview')))
                                .append($('<input/>')
                                        .attr('type', 'hidden')
                                        .attr('name', formName + '[]')
                                        .attr('value', file.id))
                                .append($('<button/>')
                                        .attr('type', 'button')
                                        .attr('class', 'delete-image-btn')
                                        .attr('data-owner-id', file.id)
                                        .attr('data-selector', selector)
                                        .attr('data-url', deleteUrl + '/' + type));

                        $('#' + selector).find('li.ui-state-disabled2').first().before(node).remove();


                    }else {

                        $('#' + selector + ' li.ui-state-disabled2').find('span').text('+').addClass('bordered-add-image')
                                .removeClass('glyphicon glyphicon-refresh glyphicon-refresh-animate');
                    }
                });
               /* $('#upi_images li > span').text('+').addClass('bordered-add-image')
                        .removeClass('glyphicon glyphicon-refresh glyphicon-refresh-animate');*/
            },
            add: function(e,data){
                var fileCount = data.files.length,
                        uploadedImages = $('.uploaded-item').length;

                if (data.autoUpload || (data.autoUpload !== false &&
                        $(this).fileupload('option', 'autoUpload')) && fileCount <= (maxFiles - uploadedImages)) {

                    $('#' + selector + ' li.ui-state-disabled2:lt(' + fileCount +')').find('span').text('').removeClass('bordered-add-image')
                            .addClass('glyphicon glyphicon-refresh glyphicon-refresh-animate');

                    data.process().done(function () {
                        data.submit();
                    });
                    $('#errorLimit').hide();
                }else{
                    $('#progress .progress-bar').css('width', '0%');
                    $('#errorLimit').show().find('.number_uploads').text(maxFiles - uploadedImages);
                }
            },
            send: function (e, data) {
                $('#progress').show();
                $('#progress .progress-bar').css('width', '0%');
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
    }

        // Delete Product from user profile
        $(document).on('click', '.delete-image-btn', function () {
            var el = $(this);
            var ownerId = el.data('ownerId'),
                    url = el.data('url'),
                    type = el.data('type'),
                    selector = el.data('selector'),
                    block_id = '#block-'+ownerId;

            $.post(url, {
                id: ownerId
            }, function () {
                $(block_id).remove();
                var node = $('<li/>')
                        .attr('class', 'ui-state-default ui-state-disabled2')
                        .append($('<span>+</span>')
                                .attr('class', 'add-image-span bordered-add-image'));

                $('#' + selector).append(node);

                bindFileUpload(type, selector);
            });
        });

    $(document).on('click', '#upi_images li > span',function(){
        $('#fileupload').click();

        bindFileUpload('upi', 'upi_images', 'images');
    });
</script>
