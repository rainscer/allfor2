<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<script src="{{ elixir("js/file-upload.js") }}"></script>

<script>
    /*jslint unparam: true */
    /*global window, $ */
    $(function () {
        'use strict';
        // Change this to the location of your server-side upload handler:
        var url = '/uploadImage/';

        $('#fileupload').fileupload({
            url: url + 'background',
            dataType: 'json',
            singleFileUploads : false,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            disableImageResize: /Android(?!.*Chrome)|Opera/
                    .test(window.navigator && navigator.userAgent),
            imageForceResize: true,
            imageCrop : true,
            imageMinWidth : 800,
            imageMinHeight : 600,
            imageMaxWidth : 800,
            imageMaxHeight : 600,
            done: function (e, data) {

                $.each(data.result.files, function (index, file) {
                    if($('#block-' + file.id).length == 0) {
                        var node = $('<div/>')
                                .attr('class', 'menu-image-preview-block col-md-3 col-sm-12')
                                .attr('id', 'block-' + file.id)
                                .append($('<img/>')
                                        .attr('src', file.name)
                                        .attr('class', 'menu-image-preview'))
                                .append($('<input/>')
                                        .attr('type', 'radio')
                                        .attr('id', 'image_radio' + $('.menu-image-preview-block').length)
                                        .attr('name', 'image_radio')
                                        .attr('value', file.name)
                                        .attr('class', 'menu-image-preview-radio'))
                                .append($('<label/>')
                                        .attr('for', 'image_radio' + $('.menu-image-preview-block').length)
                                        .append($('<span/>')))
                                .append($('<button/>')
                                        .attr('type', 'button')
                                        .attr('class', 'delete-btn')
                                        .attr('data-owner-id', file.id)
                                        .attr('data-url', '/deleteImage/background'));

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


        $('#fileupload-icon').fileupload({
            url: url + 'icon',
            dataType: 'json',
            singleFileUploads : false,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            disableImageResize: /Android(?!.*Chrome)|Opera/
                    .test(window.navigator && navigator.userAgent),
            done: function (e, data) {

                $.each(data.result.files, function (index, file) {
                    if($('#block-icon-' + file.id).length == 0) {
                        var node = $('<div/>')
                                .attr('class', 'menu-image-preview-block col-md-1 col-md-offset-1 menu-preview-icon col-sm-6')
                                .attr('id', 'block-icon-' + file.id)
                                .append($('<img/>')
                                        .attr('src', file.name)
                                        .attr('class', 'menu-image-preview'))
                                .append($('<input/>')
                                        .attr('type', 'radio')
                                        .attr('id', 'image_icon_radio' + $('.menu-image-preview-block').length)
                                        .attr('name', 'image_icon_radio')
                                        .attr('value', file.name)
                                        .attr('class', 'menu-image-preview-radio'))
                                .append($('<label/>')
                                        .attr('for', 'image_icon_radio' + $('.menu-image-preview-block').length)
                                        .append($('<span/>')))
                                .append($('<button/>')
                                        .attr('type', 'button')
                                        .attr('class', 'delete-icon-btn')
                                        .attr('data-owner-id', file.id)
                                        .attr('data-url', '/deleteImage/icon'));

                        node.appendTo('#files-icon');
                    }
                });
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress-icon .progress-bar').css(
                        'width',
                        progress + '%'
                );
            }
        }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });


    /* uploadButton = $('<button/>')
     .addClass('btn btn-primary')
     .prop('disabled', true)
     .text('Processing...')
     .on('click', function () {
     var $this = $(this),
     data = $this.data();
     $this
     .off('click')
     .text('Abort')
     .on('click', function () {
     $this.remove();
     data.abort();
     });
     data.submit().always(function () {
     $this.remove();
     });
     });
     $('#fileupload').fileupload({
     url: url,
     dataType: 'json',
     autoUpload: false,
     acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
     maxFileSize: 999000,
     // Enable image resizing, except for Android and Opera,
     // which actually support image resizing, but fail to
     // send Blob objects via XHR requests:
     disableImageResize: /Android(?!.*Chrome)|Opera/
     .test(window.navigator && navigator.userAgent),
     limitMultiFileUploads: 1,
     imageForceResize: true,
     imageCrop : true,
     imageMinWidth : 500,
     imageMinHeight : 500,
     imageMaxWidth : 500,
     imageMaxHeight : 500,
     previewMaxWidth: 200,
     previewMaxHeight: 200,
     previewCrop: true
     }).on('fileuploadadd', function (e, data) {
     data.context = $('<div/>').appendTo('#files');
     $.each(data.files, function (index, file) {
     var node = $('<p/>')
     .append($('<span/>').text(file.name));
     if (!index) {
     node
     .append('<br>')
     .append(uploadButton.clone(true).data(data));
     }
     node.appendTo(data.context);
     });
     }).on('fileuploadprocessalways', function (e, data) {
     var index = data.index,
     file = data.files[index],
     node = $(data.context.children()[index]);
     if (file.preview) {
     node
     .prepend('<br>')
     .prepend(file.preview);
     }
     if (file.error) {
     node
     .append('<br>')
     .append($('<span class="text-danger"/>').text(file.error));
     }
     if (index + 1 === data.files.length) {
     data.context.find('button')
     .text('Upload')
     .prop('disabled', !!data.files.error);
     }
     }).on('fileuploadprogressall', function (e, data) {
     var progress = parseInt(data.loaded / data.total * 100, 10);
     $('#progress .progress-bar').css(
     'width',
     progress + '%'
     );
     }).on('fileuploaddone', function (e, data) {
     $.each(data.result.files, function (index, file) {
     if (file.url) {
     var link = $('<a>')
     .attr('target', '_blank')
     .prop('href', file.url);
     $(data.context.children()[index])
     .wrap(link);
     } else if (file.error) {
     var error = $('<span class="text-danger"/>').text(file.error);
     $(data.context.children()[index])
     .append('<br>')
     .append(error);
     }
     });
     }).on('fileuploadfail', function (e, data) {
     $.each(data.files, function (index) {
     var error = $('<span class="text-danger"/>').text('File upload failed.');
     $(data.context.children()[index])
     .append('<br>')
     .append(error);
     });
     }).prop('disabled', !$.support.fileInput)
     .parent().addClass($.support.fileInput ? undefined : 'disabled');
     });*/


</script>
