$(document).ready(function () {
    // Instantiate EasyZoom instances
    var $easyzoom = $('.easyzoom').easyZoom();
    // Get an instance API
    var easyzoomAPI = $easyzoom.data("easyZoom");

    $('#star').raty({ number: 5,
        path: '/images',
        target     : '#rating',
        targetType : 'score',
        targetKeep : true
    });

    $('.star_review').raty({
        number: 5,
        path: '/images',
        readOnly: true,
        score: function() {
            return $(this).attr('data-score');
        }
    });

    var big = $("#bigfoto img"),
        link_for_zoom = $(".zoom");

    $(document).on('click','.dop-img-href', function(event){
        event.preventDefault();
    });

    $(document).on('mouseover','.dop-img-href', function(event){
        event.preventDefault();
        var el = $(this);
        big.attr({
            "src": $(el)
                .attr("href")
        });
        link_for_zoom.attr({
            "href": $(el)
                .attr("href")
        });

        easyzoomAPI.swap($(el).attr("href"), $(el).attr("href"));
    });

    $('.carousel').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: false
    });

});

// grid for product carts
var dopProduct = $('.dop-product').masonry({
    itemSelector: '.dop-item'
});
dopProduct.imagesLoaded().progress( function() {
    dopProduct.masonry('layout');
    $('.dop-product').animate({
        opacity: 1
    }, 500 );
});

// $('.modal').scroll(function () {
//
//     var margin = 0;
//
//     if ($(window).width() <= '768'){
//         margin = 0;
//     }else{
//         margin = parseInt($(".modal-dialog").css("margin-top")) + parseInt($(".modal-dialog").css("margin-bottom"));
//     }
//     if($('.modal-dialog').height() -  $('.modal').height() + margin ==  $('.modal').scrollTop() )
//     {
//
//         $.post($('.dop-product').data('href'),{
//         }).done(function (data) {
//
//             $('.dop-product').append(data.list);
//             console.log(data.list);
//             $('.dop-product').imagesLoaded( function() {
//                 window.onmousewheel = function(){ return true; };
//                 var msnry = new Masonry( '.dop-product', {
//                     itemSelector: '.dop-item'
//                 });
//                 $('.ajax-load').animate({
//                     opacity: 1
//                 }, 500 );
//             });
//
//         }).fail(function () {
//             console.log('Products could not be loaded.');
//         });
//     }
// });



$(document).on('click','.zoom', function(event){
    event.preventDefault();
});

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