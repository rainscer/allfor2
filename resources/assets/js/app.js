/* validate e-mail */
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    // alert( pattern.test(emailAddress) );
    return pattern.test(emailAddress);
}
/* validate e-mail */

/* generate Random String  */
function generateRandomString($length) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < $length; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
}
/* end generate Random String  */


/* convert Text To Slug  */
function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-')
        ;
}
/* end convert Text To Slug  */

$(document).ready(function () {

    var delay = 100,
        catalog_view_type = true,
        scrolling_down = true,
        modal = $('#myModal'),
        isReadyForSubmitLiq = true,
        bodyheight = $(window).height(),
        products = $('.product-list'),
        isReadyForSubmit = true,
        catalogControls = $('.catalog-control'),
        btnViewStyle =  $('.view-style-products'),
        currentCategory = $('#current_category'),
        category_slug = '',
        url_cart_update = 'cart/update',
        url_order_delete='/administrator/orders/delete';
    //FIXME hardcode urls

    //VK Api
    /*VK.init({
     apiId: 5065541
     });*/

    //Facebook api
    window.fbAsyncInit = function () {
        FB.init({
            appId: '729581887146145',
            xfbml: true,
            version: 'v2.5'
        });
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    //Facebook api

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // change small cart height if its smaller then window
    $(".basket").css("max-height", bodyheight - 70);
    $(".main_menu_mobile").css("max-height", $(window).height() - 70);

    //Stars in admin reviews
    $('.star_admin').raty({
        number: 5,
        path: '/images',
        readOnly: true,
        score: function() {
            return $(this).attr('data-score');
        }
    });


    // grid for product carts
    var grid = $('.grid').masonry({
        //columnWidth: 255,
        itemSelector: '.grid-item'
    });
    grid.imagesLoaded().progress( function() {
        grid.masonry('layout');
        $('.grid').animate({
            opacity: 1
        }, 500 );
    });

    // stars for review list in search products
    $('.star_review_catalog').raty({
        number: function() {
            return $(this).attr('data-score');
        },
        path: '/images',
        readOnly: true,
        score: function() {
            return $(this).attr('data-score');
        }
    });

    // for block adult on category 18+
    if($('.block-adult').length){
        $('.grid').blurjs({
            customClass: 'blurjs',
            radius: 10,
            persist: false
        });
    }

    $(document).on('click', '.yes-adult', function(e){
        e.preventDefault();
        $.blurjs('reset');
        $('.block-adult').hide();
        $.post(
            $('#url-adult').val(),
            { },
            function(data){
                $('.block-adult').append(data);
            }
        );
    });
    // for block adult on category 18+

    // for toggle dop menu category items
    $(document).on('click', '.roll-li-category-menu', function(){
        $(this).toggleClass('closed');
        $(this).next('ul').toggle("blind", 300, function(){
            grid.masonry('layout');
        });
    });

    // mobile menu toggle
    $(document).on('click','.main_menu_mobile span',function(){
        $(this).toggleClass('closed').next('ul').toggle();
        $(this).prev().toggleClass('active-mm');
    });

    // confirm delete all errors in admin page
    $(document).on('click', '.delete-all-errors-btn', function (e) {
        return confirm('Are you sure ?')
    });

    // toggle table view
    $(document).on('click', '.toggle-table', function(e){
        e.preventDefault();
        $(this).parent().next('.panel-body').toggle('fast');
    });

    // Phone mask
    $(".phone").mask("+38(099) 999-9999");
    $(".phone-multi").mask("+99(999) 999-9999");

    //Main menu
    $('.level-1>.menu-link').hover(function () {
        var element = $(this);
        //setTimeoutConst = setTimeout(function () {
        $('.image_menu').css("opacity", "0");
        $('.level-1>.menu-link').removeClass('menu_active');
        $('.level-2>.menu-link').removeClass('menu_active');
        element.addClass('menu_active');
        var ownerId = element.data('ownerId');
        $('.level-2').hide();
        $('.level-2[data-owner-id="' + ownerId + '"]').show();
        if (element.data('image')) {
            if($('#image_menu_' + ownerId).length){
                $('#image_menu_' + ownerId).css("opacity", "1");
            }else{
                $('.main_menu').append($('<img/>')
                    .attr('src', element.data('image'))
                    .attr('class', 'image_menu')
                    .attr('id', 'image_menu_' + ownerId));
                $('#image_menu_' + ownerId).css("opacity", "1");
            }
        }
        /* }, delay);
         }, function () {
         clearTimeout(setTimeoutConst);*/

    });

    // Saving order delivery data
    /*$(document).on('submit', '#delivery', function (e) {
        e.preventDefault();
    });*/

    $(document).on('click', '.change-delivery-address', function (e) {
        $('.delivery_block').removeClass('hidden');
        $('.chosen-select').chosen('destroy');
        $('.chosen-select').chosen({no_results_text: "Ничего не найдено!"});
        $('.save-delivery-block').show();
        $(this).parent().hide();
    });

    $(document).on('click', '.clear-delivery', function (e) {
        var deliv_form = $('#delivery');
        $(deliv_form).find('input[type="text"]').css({'border-color': '#ccc'}).val('');
        $(deliv_form).find('select').css({'border-color': '#ccc'}).val('-1');
        $('.chosen-select').chosen('destroy');
        $('.chosen-select').chosen({no_results_text: "Ничего не найдено!"});
        $(deliv_form).find('.validation-error-delivery').remove();

    });

    $(document).on('click', '.pay-btn', function (e) {
        e.preventDefault();
        var el = $(this),
            deliv_form = $('#delivery'),
            payment = $(this).data('payment');
        $(deliv_form).find('input[type="text"]').css({'border-color': '#ccc'});
        $(deliv_form).find('select').css({'border-color': '#ccc'});
        $(deliv_form).find('.validation-error-delivery').remove();

        $.post(
            deliv_form.attr("action"),
            $(deliv_form).serialize())
            .done(function (data) {

                if (data.result == 'OK') {
                    $('.payments').append($('<div/>')
                        .attr('class', 'payment_item')
                        .append(data.liqpay));
                    $('.payments').append($('<div/>')
                        .attr('class', 'payment_item')
                        .append(data.payPal));

                    $('#' + payment).submit();

                    if(el.data('payment') == 'wayforpay'){

                        //console.log(data.order.delivery_cost);

                        var wayforpay = new Wayforpay();

                        var pay = function() {
                            wayforpay.run({
                                    merchantAccount : data.merchantAccount,
                                    merchantDomainName : data.merchantDomainName,
                                    authorizationType : "SimpleSignature",
                                    merchantTransactionSecureType: 'AUTO',
                                    merchantSignature : data.merchantSignature,
                                    orderReference : data.order.id,
                                    //orderReference : (data.order.id+9999), //for testing data WFP
                                    orderDate : data.time,
                                    regularMode: 'none',
                                    amount : data.order.order_total + data.order.delivery_cost,
                                    //amount : 1 + data.order.delivery_cost,           //for testing data
                                    currency : "UAH",
                                    productName : data.products_for_wfp,
                                    productPrice : data.prices_for_wfp,
                                    productCount : data.quantities_for_wfp,
                                    //productPrice : [1],   //for testing data
                                    //productCount : [1],   //for testing data
                                    clientFirstName : $(deliv_form).find('input[name="name"]').val(),
                                    clientLastName : $(deliv_form).find('input[name="last_name"]').val(),
                                    clientEmail : $(deliv_form).find('input[name="email"]').val(),
                                    clientPhone: $(deliv_form).find('input[name="d_user_phone"]').val()

                                },
                                function (response) {
                                    //console.log(response.transactionStatus);
                                    if(response.transactionStatus == 'Approved')
                                    {
                                        $.ajax({
                                        type: "POST",
                                        url: data.hash_order_url,
                                        success: function(data_success){
                                            window.location.href = data_success.success_url;
                                        }
                                    });
                                    }
                                },
                                function (response) {
                                    // on declined
                                    // например отказ на этапе подтверждения оплаты  на стороне банка эмитента карты - ошибка 1108

                                    //console.log('declined');console.log(response);
                                    $.ajax({
                                        type: "POST",
                                        url: data.send_error_url_wfp,
                                        success: function(data_success){

                                        }
                                    });
                                },
                                function (response) {
                                    // on pending or in processing
                                    //console.log(' pending or in processing');console.log(response);
                                }
                            );
                        };

                        //loading_block.hide();

                        pay();
                    }
                }
            })
            .fail(function (data) {
                if (data.status == 422) {
                    var errors = data.responseJSON;

                    $.each(errors, function (key, value) {
                        $('#' + key).css({'border-color': 'red'});
                        $('#' + key).parent().append($('<div>' + value + '</div>')
                            .attr('class', 'alert alert-danger validation-error-delivery'));
                    });
                } else {
                    if (data.status == 409) {
                        $('.delivery_block').append($('<div>' + data.responseText + '</div>')
                            .attr('class', 'alert alert-danger'));
                    }
                }
            });
    });

    $(document).on('click', '.save-delivery', function (e) {
        e.preventDefault();
        var el = $(this),
            deliv_form = $('#delivery');
        $(deliv_form).find('input[type="text"]').css({'border-color': '#ccc'});
        $(deliv_form).find('select').css({'border-color': '#ccc'});
        $(deliv_form).find('.validation-error-delivery').remove();
        $.post(
            el.data("url"),
            $(deliv_form).serialize())
            .done(function (data) {
                if (data.result == 'OK') {
                    $('.delivery_block').addClass('hidden');
                    $('.delivery-text').find('p').empty().text(data.address);
                    $('.change-delivery-block').show();
                    $('.save-delivery-block').hide();
                }
            })
            .fail(function (data) {
                if (data.status == 422) {
                    var errors = data.responseJSON;

                    $.each(errors, function (key, value) {
                        $('#' + key).css({'border-color': 'red'});
                        $('#' + key).parent().append($('<div>' + value + '</div>')
                            .attr('class','alert alert-danger validation-error-delivery'));
                    });
                }
            });
    });

    // admin form for liqpay
    $(document).on('submit', '#liqpay-form-admin', function(e){
        e.preventDefault();
        $('#result-liqpay').text('');
        if(isReadyForSubmitLiq) {
            isReadyForSubmitLiq = false;
            $.post(
                $(this).attr('action'),
                $(this).serialize(),
                function (data) {
                    isReadyForSubmitLiq = true;
                    $('#result-liqpay').append($('<div/>').text('Статус заказа: ' +
                        data.result.status));

                    if (data.result.err_description) {
                        $('#result-liqpay').append($('<div/>').text(
                            'Описание ошибки: ' + data.result.err_description));
                    }
                    if (data.result.url_to_order) {
                        $('#result-liqpay').append($("<a>Изменить статус заказа</a>")
                            .attr('href', data.result.url_to_order)
                            .attr('value', data.result.url_to_order)
                            .attr('class', 'btn btn-primary'));
                    }

                }
            );
        }
    });

    // Modal link for products attribute
    $(document).on('change', '.attribute', function (e) {
        e.preventDefault();
        var el = $(this);
        $(modal).data('href', $(el).val());
        reloadModal(modal);
    });

    // Ajax getting city by region id
    $(document).on('change', '.delivery_region', function ()
    {
        var citySelect = $('.delivery_city');
        $.post($(this).data('url'),
            {
                region:this.value
            })
            .done(function(cityList){
                citySelect.html('');
                citySelect.attr('disabled',false);
                $.each(cityList, function(i){
                    citySelect.append('<option value="' + cityList[i].id + '">' + cityList[i].name + '</option>');
                });
                $(".chosen-select").trigger("chosen:updated");
            });
    });

    $(".chosen-select").chosen({
        no_results_text: "Ничего не найдено!",
        placeholder_text_multiple: "Выберите опции"
    });

    //Add checked products from likes to cart
    $(document).on('click', '.add-to-cart-likes', function (e) {
        var itemsData = {};
        var el = $(this);
        $.each($('.block-products-with-event input[type=checkbox]:checked'), function (i) {
            itemsData[i] = {
                'itemId': this.value
            };
        });
        if($.isEmptyObject(itemsData) == false) {

            $.post($(el).data('ownerId')
                , {
                    products: itemsData
                }, function (data) {
                    $('.add-to-cart-block').append(data);
                    //FIXME temporary
                    location.href = $(el).attr('href');
                });
        }
        return false;
    });

    //Add checked products from not-paid to cart
    $(document).on('click', '.buy-btn', function (e) {
        var itemsData = {};
        var el = $(this);
        $.each($('.block-products-with-event input[type=checkbox]:checked'), function (i) {
            itemsData[i] = {
                'itemId': this.value
            };
        });
        if($.isEmptyObject(itemsData) == false) {

            $.post($(el).data('ownerId')
                , {
                    products: itemsData
                }, function (data) {
                    //FIXME temporary
                    location.href = $(el).attr('href');
                });
        }
        return false;
    });

    //Not-paid or liked products in user page
    $(document).on('change', '.block-products-with-event input[type=checkbox]', function () {
        var i = $('.block-products-with-event').find('input[type=checkbox]:checked').length;
        if(i > 0){
            $('.add-to-cart-block .total-checked').text(i);
        }else{
            $('.add-to-cart-block .total-checked').text(0);
        }
    });

    $(document).on('click','.clear-likes',function(){
        $('input[type=checkbox]:checked').attr('checked',false);
        $('.add-to-cart-block .total-checked').text(0);
    });

    //Edit menu item in admin
    $(document).on('click', '.edit-menu-btn', function () {
        var menu = $(this).data('url') + '/' + $('#dop-menu-block input[type=checkbox]:checked').val() + '/edit';
        if($('#dop-menu-block input[type=checkbox]:checked').val()) {
            document.location.href = menu;
        }

    });

    //Menu choose type
    $(document).on('change', 'select#type', function () {
        if (this.value == 'href') {
            $('.href-for-menu').attr('disabled', false);
            $('select#article').attr('disabled', true);
        }else{
            $('.href-for-menu').attr('disabled', true);
            $('select#article').attr('disabled', false);
        }
    });

    //Change status active in admin
    $(document).on('change', 'input.change-active-admin[type=checkbox]', function () {
        var active = 0,
            link_update = $(this).data('ownerId');
        if($(this).is(':checked')){
            active = 1;
        }
        $.post(
            link_update, {
                active: active,
                item_id:$(this).val()
            }, function (data) {
            });
    });

    //Change status active in admin
    $(document).on('change', 'input.change-hidden-admin[type=checkbox]', function () {
        var hidden = 0,
            link_update = $(this).data('ownerId');
        if($(this).is(':checked')){
            hidden = 1;
        }
        $.post(
            link_update, {
                hidden: hidden,
                item_id:$(this).val()
            });
    });

    //For drag n drop sorting
    $( ".sortable" ).sortable({
        revert: true,
        opacity: 0.5
    });

    $( ".sortable" ).sortable({
        update: function( event, ui ) {
            var sorted = $( ".sortable" ).sortable( "toArray",{attribute:'data-owner-id'} );
            $.post(
                $(this).data('ownerId'), {
                    menu_items: sorted
                }, function (data) {
                });
        }
    });

    $( ".sortable" ).disableSelection();
    //For drag n drop sorting

    //System errors detail in modal
    $(document).on('click', '.modalFormToggle', function (e) {
        e.preventDefault();
        var el = $(this);
        $(modal).data('href', $(el).attr('href'));
        reloadModal(modal);
        return false;
    });

    //Recalculate sum and product in admin
    $(document).on('change', '.admin-product-quantity', function (e) {
        recalculation_admin();
    });

    //Delete items in admin
    $(document).on('click', '.delete-admin-btn', function () {
        var itemsData = {};
        var link_delete = $(this).data('ownerId');
        var item;
        $.each($('input.delete-box-admin[type=checkbox]:checked'), function (i) {
            itemsData[i] = {
                'itemId': this.value
            };
            delay =(i)*500;
            setTimeout(function ()
            {
                item='#item-'+itemsData[i].itemId;
                $(item).animate({
                    opacity: 0
                }, 400, null, function() {
                    $(item).remove();
                    // if this is order table make recalculation
                    if($("table").is("#order_items_table")){
                        recalculation_admin();
                    }
                });
            }, delay, $(this));
        });
        if($.isEmptyObject(itemsData) == false) {
            $.post(
                link_delete, {
                    items: itemsData
                }, function (data) {
                });
        }

    });

    function recalculation_admin() {
        var itemLine = $('.admin-order-item'),
            total = $('.admin-order-total'),
            table = $('#order_items_table'),
            totalSum = 0;

        var itemsData = {},
            item = {};
        itemLine.each(function (i, el) {
            var iPrice = parseFloat($('.product-price', this).text());
            var iQuantity = parseInt($('.admin-product-quantity', this).val(), 10);
            itemsData[i] = {
                'itemQuantity': iQuantity,
                'itemPrice': iPrice,
                'itemSum': (iQuantity * iPrice).toFixed(0)
            };
            $('.product-cost', this).text(itemsData[i].itemSum);
            totalSum += parseInt(itemsData[i].itemSum,10);
        });
        total.text(totalSum);
        // if total sum is 0 - delete order
        if(totalSum == 0){
            item[0] = {
                'itemId': table.data('ownerId')
            };
            table.remove();
            $.post(
                url_order_delete, {
                    items: item
                }, function () {
                });

        }
    }

    // Delete Product from user profile
    $(document).on('click', '.delete-btn', function () {
        var el = $(this);
        var ownerId = $(el).data('ownerId'),
            url = $(el).data('url'),
            product_id = '#block-'+ownerId;

        $.post(url, {
            id: ownerId
        }, function (data) {
            $(product_id).animate({
                opacity: 0
            }, 500, "linear" , function(){
                $(product_id).remove();
                grid.masonry('layout');
                if($('.count-likes').length){
                    var count = parseInt($('.count-likes').text(), 10);
                    $('.count-likes').text(count - 1);
                }
            });

        });
    });

    // Delete Product from user profile
    $(document).on('click', '.delete-icon-btn', function () {
        var el = $(this);
        var ownerId = $(el).data('ownerId'),
            url = $(el).data('url'),
            product_id = '#block-icon-'+ownerId;

        $.post(url, {
            id: ownerId
        }, function (data) {
            $(product_id).animate({
                opacity: 0
            }, 500, "linear" , function(){
                $(product_id).remove();
            });

        });
    });

    // Modal link for products
    $(document).on('click', '.link_modal', function (e) {
        e.preventDefault();
        var el = $(this);
        $(modal).data('href', $(el).attr('href'));
        reloadModal(modal);

        $('.modal').animate({scrollTop: top}, 1500);

    });


    function recalculation() {
        var totalSum = 0,
            totalWeight = 0,
            totalSumWithDelivery = 0,
            itemsData = {},
            delivery_box = '',
            itemLine = $('.product-cart-item'),
            total = $('.total-price'),
            img_for_clone = $('.img-for-clone'),
            delivery_boxes = $('.delivery-boxes-block'),
            totalDeliveryByWeight = $('.deliv-price-total-weight'),
            totalWeightText = $('.total-weight');

        itemLine.each(function (i, el) {
            var iPrice = parseFloat($(this).find('.prod_price').first().text());
            var iQuantity = 0;
            // get value from vissible input - mobile it or not
            $(this).find('.product-basket-count').each(function( index ) {
                if($(this).css('display') != 'none') {
                    iQuantity = parseInt($(this).val(), 10);
                }
            });
            var weight = parseInt($(this).find('.product-weight-by1').val(), 10);
            if(isNaN(iQuantity) || iQuantity == 0) {
                iQuantity = 1;
            }
            iQuantity = Math.abs(iQuantity);
            $('.product-basket-count', this).val(iQuantity);
            itemsData[i] = {
                'itemId': $(this).data('id'),
                'itemQuantity': iQuantity,
                'itemPrice': iPrice,
                'itemSum': (iQuantity * iPrice).toFixed(0),
                'itemWeight' : iQuantity * weight,
            };
            $('.prod_sum', this).text(itemsData[i].itemSum);
            $('.product-weight', this).text(itemsData[i].itemWeight);
            totalSum += parseFloat(itemsData[i].itemSum);
            totalWeight += parseInt(itemsData[i].itemWeight);
        });
        totalSum = totalSum.toFixed(0);

        if($.isEmptyObject(itemsData) == false) {
            $.post(
                url_cart_update, {
                    products: itemsData
                }, function (data) {

                    // get delivery cost as total with all delivery parameters
                    // totalSumWithDelivery = parseInt(data.packing_price * data.coef_delivery + data.ua_deliv_price * data.coef_delivery + parseInt(totalSum), 10);

                    // get delivery cost as collection from delivery parameters
                    totalSumWithDelivery = parseInt(parseInt(data.ua_deliv_price) + parseInt(totalSum));

                    total.text(totalSumWithDelivery);
                    totalWeightText.text((totalWeight/1000).toFixed(2));
                    totalDeliveryByWeight.text(data.packing_price * data.coef_delivery + data.ua_deliv_price * data.coef_delivery);

                    delivery_box = img_for_clone.find('img');
                    delivery_boxes.empty();
                    while(data.coef_delivery){
                        delivery_box.clone().appendTo(delivery_boxes);
                        data.coef_delivery--;
                    }

                    //$('#delivery-cost').empty().html(data.deliveryCost);
                    $('.basket').empty().html(data.smallCart);
                    $('.cart_total_header').empty().html(data.cart_total_count);
                });
        }else{
            $('.basket-page-title').text('There are no products in the cart, you will be redirected to the main page');
            $('.cart-body').text('');
            setTimeout(function() { document.location.href = "/";}, 2500);
        }
    }
    // change count of products on cart page
    $('.product-basket-count').change(recalculation);

    // Deleting product from cart
    $(document).on('click', '.ajaxActionDeleteProduct', function (e) {
        e.preventDefault();
        var el = $(this),
            ownerId = $(el).data('ownerId'),
            product_id = '#pr'+ownerId,
            total = $('.total-price'),
            cart_page = $('.basket-page').length;

        $.post($(el).attr('href'), {
            product_id: ownerId
        }, function (data) {
            console.log(data);
            updateCartState();
            if(cart_page) {
                $(product_id).animate({
                    opacity: 0
                }, 400 ,"linear" , function(){
                    $(product_id).remove();
                    recalculation();
                } );
                $('.basket').empty().html(data.smallCart);
            }else{
                if(data.cart_total_count){
                    $('.cart_total_header').empty().html(data.cart_total_count);
                }else{
                    $('.cart_total_header').empty();
                }
                $('.basket').empty().html(data.smallCart);
            }
        });
    });

    // Add like to product
    $(document).on('click', '.ajaxLikeProduct', function (e) {
        e.preventDefault();
        var el = $(this),
            message_box = $('.message-box'),
            ownerId = $(el).data('ownerId');

        $.post($(el).attr('href'), {
            product_id: ownerId
        }, function (data) {
            if(data.quest){
                $('.message').empty().append('<a href="#modal_user" data-toggle="modal">' + data.message + '</a>');
                $(message_box).css({opacity: 0});
                $(message_box).show();
                $(message_box).animate(
                    {opacity: 1}, 500);
            }
            else {
                $('.message').empty().text(data.message);
                $(message_box).css({opacity: 0})
                    .show()
                    .animate(
                        {opacity: 1}, 500);
                setTimeout(function () {
                    $(message_box).animate(
                        {opacity: 0}, 500, "linear", function () {
                            $(message_box).hide();
                        });
                }, 2000);
            }
        });
    });

    //Post vk
    $(document).on('click', '.postVk', function (e) {
        e.preventDefault();
        var friend = $('#friends').val(),
            url_for_vk = '',
            ownerId = $(this).data('ownerId'),
            message = $('.message-vk').val();

        if(ownerId == 1) {
            url_for_vk = location.href;
        } else{
            url_for_vk ='http://'+location.host;
        }
        VK.Api.call('wall.post', {owner_id: friend, message: message,attachments: url_for_vk}, function(r) {
            if(r.response.post_id != undefined) {
                $('.alert-success').removeClass('hide').text('Запись успешно опубликована!');
            }
        });
    });

    //Post fb
    $(document).on('click', '.postfb', function (e) {
        e.preventDefault();
        var ownerId = $(this).data('ownerId'),
            message = $('.message-fb').val(),
            url_for_fb = '';

        if(ownerId == 1) {
            url_for_fb = location.href;
        } else{
            url_for_fb ='http://'+location.host;
        }
        FB.api('/me/feed', 'post', {message:message,link: url_for_fb});
        $('.alert-success').removeClass('hide').text('Запись успешно опубликована!');

        /*FB.api(
         "/me/friends",
         {fields: 'name,id'},
         function (response) {
         if (response && !response.error) {
         var data=response.data;
         //alert(data);
         for (var friendIndex=0; friendIndex<data.length; friendIndex++)
         {
         alert(data[friendIndex].name);
         }

         }
         }
         );*/

    });


    //Save review
    $(document).on('submit', '.form-review', function (e) {
        e.preventDefault();
        var el = $(this),
            type = $(this).find($('.type')).val(),
            result = $(this).parent('.form-reviews').prev('.form-reviews-result');

        $.post(el.attr("action"),
            $(el).serialize())
            .done(function (data) {
                $(el).find('input[type="text"]').val('').removeClass('borderRed');
                $(el).find('textarea').val('').removeClass('borderRed');
                if($(el).find('#files').length) {
                    $(el).find('#files').empty();
                    $(el).find('.progress-bar-success').css({'width': 0});
                }
                $('.form-reviews').hide("slide", { direction: "right" }, 500);
                $('#star').raty('reload');
                $(result).html(data);
            })
            .fail(function (data) {
                if (data.status == 422) {

                    var errors = data.responseJSON;

                    $.each(errors, function (key, value) {
                        $('.' + type + '-' + key).addClass('borderRed');
                        /*$('.' + type + '-' + key).parent().append($('<div>' + value + '</div>')
                         .attr('class','alert alert-danger validation-error-review'));*/
                    });
                }
            });
    });


    //Save review
    $(document).on('submit', '.form-review-order', function (e) {
        e.preventDefault();
        var el = $(this),
            type = $(this).find($('.type')).val(),
            result = $(this).next('.form-reviews-result');

        $.post(el.attr("action"),
            $(el).serialize())
            .done(function (data) {
                $(el).find('input[type="text"]').val('').removeClass('borderRed');
                $(el).find('textarea').val('').removeClass('borderRed');
                if($(el).find('#files').length) {
                    $(el).find('#files').empty();
                    $(el).find('.progress-bar-success').css({'width': 0});
                }
                $('#star').raty('reload');
                $(result).html(data);
            })
            .fail(function (data) {
                if (data.status == 422) {

                    var errors = data.responseJSON;

                    $.each(errors, function (key, value) {
                        $('.' + type + '-' + key).addClass('borderRed');
                        /*$('.' + type + '-' + key).parent().append($('<div>' + value + '</div>')
                         .attr('class','alert alert-danger validation-error-review'));*/
                    });
                }
            });
    });

    // toggle form review or qa
    $(document).on('click','.btn-review-qa-close', function()
    {
        $(this).parent('.form-reviews').toggle("slide", { direction: "right" }, 500);
        return false;
    });

    $(document).on('click','.btn-review-qa', function()
    {
        $('.form-reviews').hide("slide", { direction: "right" }, 500);
        $(this).next().next('.form-reviews').toggle("slide", { direction: "right" }, 500);
        return false;
    });

    $(document).on('click', '.modal-body', function(e){
        var elem = $(".form-reviews");
        if(e.target != elem[0] && !elem.has(e.target).length)
        {
            elem.hide("slide", { direction: "right" }, 500);
        }
    });

    $(document).on('click', '.show-want-free-block', function(e){
        e.preventDefault();
        var elem = $(this),
            wantFreeBlock = elem.attr('href');
        $(wantFreeBlock).show();
        $(wantFreeBlock).animateCss('fadeInDown');
    });

    $(document).on('click', '#want-free', function(e){
        $(this).animateCssAndHide('fadeOutUp');
    });

    // toggle form review or qa

    // pagination review and qa in product page
    $(document).on('click','.product .pagination a', function()
    {
        var url = $(this).attr('href');
        var type = $(this).parent().parent().parent().data('ownerId');
        var block = ".review_block." + type;

        $.get(url, {
            type:type
        })
            .done(function( data ) {
                $(block).empty().html(data);
                $('.star_review').raty({
                    number: 5,
                    path: '/images',
                    readOnly: true,
                    score: function() {
                        return $(this).attr('data-score');
                    }
                });
            });
        return false;
    });

    /* Paid and delivered user pages */
    $('.user-orders .progress-bar[data-toggle="popover"]').popover({
        animated: 'fade',
        trigger: 'hover',
        html: true,
        content: (function () {
            return $(this).next().html();
        })
    });

    $('.order-delivery-data[data-toggle="popover"]').popover({
        animated: 'fade',
        trigger: 'hover',
        html: true,
        content: (function () {
            return $(this).find('.popover-adress').html();
        })
    });

    $('.profile-setting[data-toggle="popover"]').popover({
        animated: 'fade',
        trigger: 'hover',
        html: true,
        content: (function () {
            return $('.popover-info').html();
        })
    });

    $(document).on('click','.toggle-block',function(e){
        e.preventDefault();
        $(this).parent().parent().next().toggle('fast').next().toggle('fast');
    });
    /* Paid and delivered user pages */

    // Add product to cart ajax
    $(document).on('click', '.ajaxAddProductToCart', function (e) {
        e.preventDefault();
        var el = $(this),
            ownerId = $(el).data('ownerId'),
            quantity = 1;

        if($('input[name="quantity"]').length){
            quantity = parseInt($('input[name="quantity"]').val(),10);
        }

        $.post($(el).data('url'), {
            product_id: ownerId,
            quantity: quantity,
            current_url : window.location.href
        }, function (data) {
            console.log(data);
            updateCartState();
            modal.modal('hide');
        });
    });

    $(document).on('click','.continue-ship', function()
    {
        //$('.to-cart-or-cont').animateCssAndHide('rubberBand');
        //$('.to-cart-or-cont').toggle("blind", 200);
        modal.modal('hide');
    });

    // ajax load products
    function getProducts(page) {
        location.hash = page;
        var aTop = $('.grid').height();
        $.get('?page=' + page,{
        }).done(function (data) {
            // put current url to hidden input for next use when product page will close
            var url = window.location.href;
            $('#site-url').attr('value', url);

            $('.grid').append(data);
            $('.grid').imagesLoaded( function() {
                scrolling_down = true;
                window.onmousewheel = function(){ return true; }
                var msnry = new Masonry( '.grid', {
                    //columnWidth: 255,
                    itemSelector: '.grid-item'
                });
                $('.ajax-load').animate({
                    opacity: 1
                }, 500 );
            });
        }).fail(function () {
            console.log('Products could not be loaded.');
        });
    }

    // scrolling for ajax adding products in category
    // fix with indexOf for product page - there was duplication of data loaded
    $(window).scroll(function () {
        if ($('#last_page').length &&
            ($('#last_page').text() != '1') &&
            (window.location.toString().indexOf("product") < 0))
        {
            var last_page = parseInt( $('#last_page').text(), 10);
            var aTop = $('.grid').height();
            var page = window.location.hash.replace('#', '');
            if ($(window).scrollTop() >= (aTop - 3000)) {
                if (scrolling_down == true) {
                    if (page == Number.NaN || page <= 0) {
                        getProducts(2);
                        scrolling_down = false;
                    } else {
                        page = parseInt(page, 10);
                        if (last_page != page) {
                            page += 1;
                            scrolling_down = false;
                            getProducts(page);
                        }
                    }
                }
            }
        }
    });

    // make visible carousel on init in user profile
    $('.product-likes-block-carousel').on('init', function(){
        $(this).animate({
            opacity: 1
        }, 500);
    });

    // isReadyForSubmit needs for submit search form once because when press Enter
    // it submits two times - event press Enter needs because when user press enter first
    // time form not submit - there is only typeahead is hiding so we submit it manualy
    // search products


    /*$('input[name="search"]').typeahead(
        {
            source: function (query, process) {
                var el = $('input[name="search"]');
                category_slug = '';
                if(currentCategory.length){
                    category_slug = currentCategory.val();
                }
                return $.post(
                    el.data('url'),
                    {
                        search: query,
                        category_slug: category_slug
                    },
                    function (response) {
                        var data = new Array();
                        //преобразовываем данные из json в массив
                        $.each(response.options, function(i, name)
                        {
                            data.push(i+'_'+name);
                        });
                        return process(data);
                    }, 'json');
            }, highlighter: function(item) {
            var parts = item.split('_'),
                part_query = this.query.split(' ');
            parts.shift();
            parts = parts.join('_');

            $.each(part_query, function(i){

                if(part_query[i] != '') {
                    parts = parts.replace(new RegExp('(' + part_query[i] + ')', 'ig'), function ($1, match) {
                        return '<strong>' + match + '</strong>'
                    });
                }
            });

            return parts;
        },
            matcher: function (item) {
                var parts = item.split('_'),
                    part_query = this.query.split(' '),
                    res = 0;
                parts.shift();
                parts = parts.join('_');

                $.each(part_query, function(i){
                    if(parts.toLowerCase().indexOf(part_query[i].toLowerCase()) > -1){
                        res++;
                    }
                });

                if(part_query.length == res) {
                    return true;
                }
                return false;
            }
            , updater: function(item) {
            isReadyForSubmit = false;
            var el = $('input[name="search"]'),
                parts = item.split('_'),
                product_id = parts.shift();
            category_slug = '';
            if(currentCategory.length){
                category_slug = currentCategory.val();
            }
            $.post(el.data('backUrl'), {
                    product_id : product_id,
                    toggle : 'list',
                    category_slug : category_slug
                },
                function (data) {
                    $(btnViewStyle).removeClass('view-style-list').attr('data-click-state', 'list');
                    if($(catalogControls).hasClass('hidden')) $(catalogControls).removeClass('hidden');

                    loadDataSearch(data.response);
                },
                'json'
            );
            return parts.join('_');
        },
            autoSelect:false,
            items:15,
            showHintOnFocus:true
        });

    $('input[name="search"]').keyup(function(e){
        var code = e.which;
        if (code == 13) {
            if(isReadyForSubmit) {
                $(this).parent().submit(); // $('.search_form')
            }
            isReadyForSubmit = true;
        }

    });*/

    $(document).on('submit', '.search_form', function(e){
        e.preventDefault();
        isReadyForSubmit = false;
        var el = $(this).find($('input[name="search"]')),
            form = $(this);
        //console.log($(el));
        category_slug = '';
        if(currentCategory.length){
            category_slug = currentCategory.val();
        }
        $.post($(form).attr('action'),
            {
                search: el.val(),
                toggle: 'blocks',
                category_slug: category_slug
            },
            function (data) {
                if(data.action == 'reload_page'){
                    window.location.reload();
                } else {
                    if ($(window).width() <= '767') {
                        $('.col-xs-12.search-form').slideToggle(250);
                    }
                    $(btnViewStyle).addClass('view-style-list')
                        .attr('data-click-state', 'blocks');
                    $(catalogControls).removeClass('hidden');
                    loadDataSearch(data.response);
                }
            },
            'json'
        );
    });

    // change style of view product in search
    $(document).on('click', '.view-style-products',function(e){
        e.preventDefault();
        category_slug = '';
        if(currentCategory.length){
            category_slug = currentCategory.val();
        }
        if(catalog_view_type) {
            var search = '',
                el = $(this);

            // filter search forms where input not empty
            search = $('.search_form').filter(function(index){
                return $('input[name="search"]', this).val() != '';
            }).find($('input[name="search"]')).val();

            products.animate({
                opacity: 0
            }, 300);

            catalog_view_type = false;

            if ($(el).attr('data-click-state') === 'blocks') {
                $(el).removeClass('view-style-list');
                $(el).attr('data-click-state', 'list');
                $.post(el.attr('href'),
                    {
                        search: search,
                        toggle: 'list',
                        category_slug: category_slug
                    }
                )
                    .done(function (data) {
                            loadDataSearch(data.response);
                        },
                        'json'
                    );

            } else {
                $(el).addClass('view-style-list');
                $(el).attr('data-click-state', 'blocks');

                $.post(el.attr('href'),
                    {
                        search: search,
                        toggle: 'blocks',
                        category_slug: category_slug
                    }
                )
                    .done(function (data) {
                            loadDataSearch(data.response);
                        },
                        'json');

            }
        }
        return false;

    });

    function loadDataSearch(data){
        products.empty().append(data);
        products.animate({
            opacity: 1
        }, 500);
        if($('.grid').length) {
            var grid = $('.grid').masonry({
                //columnWidth: 255,
                itemSelector: '.grid-item'
            });

            grid.imagesLoaded().progress(function () {
                grid.masonry('layout');
                $('.grid').animate({
                    opacity: 1
                }, 500);
                $('.ajax-load').animate({
                    opacity: 1
                }, 500);
            });
        }else{
            $('.ajax-load').animate({
                opacity: 1
            }, 500);
        }
        catalog_view_type = true;
    }
    // search products

    // scheduler controls
    $(document).on('click', '.schedule-item-link', function (e) {
        e.preventDefault();
        $('.schedule-item-form-container').load($(this).attr('href'), function (data) {
            var jobLogContainer = $('#scheduler-job-log-container');
            $(jobLogContainer).load($(jobLogContainer).data('action'), function (data) {
            });
        });
    });

    $(document).on('click', '#scheduler-job-log-container>ul.pagination>li>a', function (e) {
        e.preventDefault();
        var jobLogContainer = $('#scheduler-job-log-container');
        $(jobLogContainer).load($(this).attr('href'), function (data) {
        });
    });
    // scheduler controls

    // jobLog
    $(document).on('click', '.jobLog-item-link', function (e) {
        e.preventDefault();
        $('.jobLog-item-form-container').load($(this).attr('href'), function (data) {
        });
    });

    $(document).on('click', '#jobLog-container ul.pagination>li>a', function (e) {
        e.preventDefault();
        var jobLogContainer = $('#jobLog-container');
        $(jobLogContainer).load($(this).attr('href'), function (data) {
        });
    });
    // jobLog

    // for how to pay page
    $('.change-img').mouseover(function() {
        $('.change-img').removeClass('active');
        $(this).addClass('active');
        $( '.how-to-pay-img' ).attr( 'src', $(this).data('img') );
    });

    $(document).on('click', '.how-to-pay-img', function(){
        var modal = $('#modal-for-img');
        loadModalImg(modal, $(this).attr('src'));
    });

    // for toggle how to pay items
    $(document).on('click', '.roll-li-how-pay', function(){
        $(this).toggleClass('closed');
        $(this).parent().next('ul').toggle("blind", 300);
    });

    $(document).on('click', '.change-img', function(){
        if($(this).next('ul').length){
            $(this).find('.roll-li-how-pay').toggleClass('closed');
            $(this).next('ul').toggle("blind", 300);
        }
    });

    // Tickets
    $(document).on('submit', '#new-thread', function (e) {
        var textaereaMes = $(this).find('textarea'),
            subject = $(this).find('.subject-thread'),
            valid = true;
        textaereaMes.css({'border-color': '#ccc'});
        subject.css({'border-color': '#ccc'});
        if(textaereaMes.val() == ''){
            textaereaMes.css({'border-color': 'red'});
            valid = false;
        }
        if(subject.val() == ''){
            subject.css({'border-color': 'red'});
            valid = false;
        }
        if(!valid){
            return false;
        }
    });

    // pagination tickets
    $(document).on('click', '.goto-action', function(e){
        e.preventDefault();
        var linkTo = $(this).attr('href'),
            page = parseInt($('.goto-input').val(), 10),
            totalPages = parseInt($('.goto-input-last-page').val(), 10);

        if(!isNaN(page) && page != 0 && page <= totalPages) {
            window.location = linkTo + '?page=' + page;
        }
    });

    $('input[name="thread-goto"]').keyup(function(e){
        e.preventDefault();
        var code = e.which;
        if (code == 13) {
            $('.goto-action').trigger('click');
        }
    });


    $(document).on('shown.bs.modal', '#threadModal', function (e) {
        $('.chosen-select').chosen('destroy');
        $('.chosen-select').chosen({no_results_text: "Ничего не найдено!"});
    });

    $(document).on('submit', '#send-new-message-form',function(e){
        e.preventDefault();
        var containerThread = $('.message-block');
        var textaereaMes = $(this).find('textarea'),
            height = containerThread[0].scrollHeight;

        textaereaMes.css({'border-color': '#ccc'});
        if(textaereaMes.val() == ''){
            textaereaMes.css({'border-color': 'red'});
        }else{
            $.post($(this).attr('action'),
                $(this).serialize())
                .done(function (data) {
                    if (data.result == 'OK') {
                        containerThread.append(data.messages);
                        containerThread.scrollTop(height);
                        textaereaMes.val('');
                    }
                });
        }
    });

    $('#threadTextArea').keydown(function (e) {
        if (e.ctrlKey && e.keyCode == 13) {
            $('#send-new-message-form').submit();
        }
    });

    $(document).on('click','.validation-error-delivery',function(){
        $(this).hide();
    });

    $(document).on('click','.call-me-if-exists', function(e){
        var modal = $('#modal-wait-product'),
            image_pr = $('#waiting-product-img'),
            name_pr = $('#waiting-product-name');

        modal.find('input[name="product_id"]').val($(this).data('ownerId'));
        image_pr.attr('src',$(this).data('productImage'));
        name_pr.text($(this).data('productTitle'));

        return true;
    });

    $(document).on('click','.call-me-if-exists-auth', function(e){
        var button = $(this),
            text_box = $(this).next('div');

        $.post(button.data('url'),
            {
                product_id:button.data('ownerId')
            })
            .done(function(data){
                button.text(data.btn);
                text_box.text(data.info);
                if($('.grid').length) {
                    var grid = $('.grid').masonry({
                        //columnWidth: 255,
                        itemSelector: '.grid-item'
                    });
                    grid.masonry('layout');
                }
            }).error(function(data){
            console.log(data);
        });
    });

    $(document).on('submit','#waiting-for-product', function(e){
        e.preventDefault();
        var form = $(this),
            prod = form.find('input[name="product_id"]').val(),
            modal = $('#modal-wait-product'),
            button = $('.call-me-if-exists[data-owner-id=' + prod +']'),
            text_box = button.next('div');

        $.post(form.attr('action'),
            form.serialize())
            .done(function(data){
                form.find('input[type="text"]').val('');
                form.find('input[type="email"]').val('');
                button.text(data.btn);
                text_box.text(data.info);
                if($('.grid').length) {
                    var grid = $('.grid').masonry({
                        //columnWidth: 255,
                        itemSelector: '.grid-item'
                    });
                    grid.masonry('layout');
                }
                modal.modal('hide');
            }).error(function(data){
            console.log(data);
        });
    });

    var timerFoQntCallBack;

    $(document).on('submit', '#call-me', function(e){
        e.preventDefault();
        var phone = $(this).find('.phone');
        if(phone.val() == ""){
            phone.parent().css({ 'border': "1px solid red" });
        }else{
            $.post($(this).attr('action'),
                $(this).serialize()
            ).done(function(data){
                phone.val('');
                phone.parent().css({ 'border-width': "0px" });
                $('#callBack').modal('hide');
                $('#myModal').find('.info').hide();
                $('#myModal').find('.info-ok').show();
            });
        }
    });

    $(document).on('change', '.qty', function(e){
        var cur_value = parseInt($(this).val());
        if(cur_value < $(this).data('minValue') || cur_value > $(this).data('maxValue') ||
            isNaN(cur_value)){
            $(this).val($(this).data('defaultValue'));
        }
    });

    $(document).on('mouseup', '.qtyplus', function(e){
        clearInterval(timerFoQntCallBack);
    });

    $(document).on('mouseup', '.qtyminus', function(e){
        clearInterval(timerFoQntCallBack);
    });

    // This button will increment the value
    $(document).on('mousedown', '.qtyplus', function(e){
        e.preventDefault();
        var el = $(this),
            value_field;

        timerFoQntCallBack = setInterval(function() {
            // Get the field name
            fieldName = el.attr('field');
            // Get its current value
            var currentVal = parseInt($('input[name='+fieldName+']').val());
            // If is not undefined
            if (!isNaN(currentVal)) {
                if(currentVal < el.data('maxValue')){
                    value_field = currentVal + 1;
                    if (value_field <= 9){
                        value_field = '0' + value_field;
                    }
                    // Increment
                    $('input[name='+fieldName+']').val(value_field);
                }
            } else {
                // Otherwise put a 0 there
                $('input[name='+fieldName+']').val(el.data('defaultValue'));
            }
        }, 80);
    });

    // This button will decrement the value till 0
    $(document).on('mousedown', ".qtyminus",function(e) {
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        var el = $(this),
            value_field;

        timerFoQntCallBack = setInterval(function() {
            // Get the field name
            fieldName = el.attr('field');
            // Get its current value
            var currentVal = parseInt($('input[name='+fieldName+']').val());
            // If it isn't undefined or its greater than 0
            if (!isNaN(currentVal) && currentVal > 0) {
                if(currentVal > el.data('minValue')) {
                    value_field = currentVal - 1;
                    if (value_field <= 9) {
                        value_field = '0' + value_field;
                    }

                    $('input[name=' + fieldName + ']').val(value_field);
                }
            } else {
                // Otherwise put a 0 there
                $('input[name='+fieldName+']').val(el.data('defaultValue'));
            }
        }, 80);
    });

    $(document).on('submit', '#new-message', function(e){
        e.preventDefault();
    });

    $(document).on('change', 'input[type="checkbox"].entry-chk-box', function () {
        var count_checked = $('input[type="checkbox"].entry-chk-box:checked').length;
        if (count_checked < 1) {
            count_checked = '';
        }
        $('span#display_checked_count').html(count_checked);
    });

    $(document).on('change', 'input.check-all', function () {
        var count_checked = 0;

        if ($(this).is(':checked')) {

            $('input[type="checkbox"].entry-chk-box').each(function () {
                $(this).prop("checked", true);
                count_checked++;
            });
        } else {

            $('input[type="checkbox"].entry-chk-box').prop('checked', false);
        }

        if (count_checked < 1) {
            count_checked = '';
        }
        $('span#display_checked_count').html(count_checked);
    });

    $(document).on('click', '.icon_c.livechat', function (e) {
        jivo_api.open();
    });

    $( function() {
        $( ".datepicker" ).datepicker({
            dateFormat: "dd/mm/yy"
        });
    } );

});



// change small cart height if its smaller then window
$(window).resize(function() {
    var bodyheight = $(this).height();
    $(".basket").css("max-height", bodyheight - 70);
    $(".main_menu_mobile").css("max-height", bodyheight - 70);

    mediaChangeMenu;
}).resize();

// set params for carousel on window load
$(window).load(function() {

    if($('.product-likes-block-carousel').length) {
        $('.product-likes-block-carousel').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
    }

    $('#slider').nivoSlider({
        effect: 'random',                 // Specify sets like: 'fold,fade,sliceDown'
        slices: 15,                     // For slice animations
        boxCols: 8,                     // For box animations
        boxRows: 4,                     // For box animations
        animSpeed: 500,                 // Slide transition speed
        pauseTime: 5000,                 // How long each slide will show
        startSlide: 0,                     // Set starting Slide (0 index)
        directionNav: false,             // Next & Prev navigation
        controlNav: false,                 // 1,2,3... navigation
        controlNavThumbs: false,         // Use thumbnails for Control Nav
        pauseOnHover: false,             // Stop animation while hovering
        manualAdvance: false,             // Force manual transitions
        prevText: 'Prev',                 // Prev directionNav text
        nextText: 'Next',                 // Next directionNav text
        randomStart: false,             // Start on a random slide
        beforeChange: function(){},     // Triggers before a slide transition
        afterChange: function(){},         // Triggers after a slide transition
        slideshowEnd: function(){},     // Triggers after all slides have been shown
        lastSlide: function(){},         // Triggers when last slide is shown
        afterLoad: function(){}         // Triggers when slider has loaded
    });

    $(document).on('click', function(e) {
        mediaChangeMenu;
    });
});

function mediaChangeMenu() {
    if($(window).width() <= '991') {
        /*if (!$(e.target).closest(".top_main_menu").length && !$(e.target).closest(".category").length) {
         $('.top_main_menu').slideUp(250).hide();
         }*/
        if (!$(e.target).closest(".cart-img").length && !$(e.target).closest(".top-basket").length) {
            $('.top-basket').hide();
        }
        if (!$(e.target).closest(".hash_tags_menu").length && !$(e.target).closest(".hash_tags_mobile").length) {
            $('.hash_tags_mobile').hide();
        }
        if (!$(e.target).closest(".phone").length && !$(e.target).closest(".phone_mobile").length) {
            $('.phone_mobile').slideUp(250).hide();
        }
        e.stopPropagation();
    }
    if($(window).width() <= '767') {
        if (!$(e.target).closest(".search-form").length && !$(e.target).closest(".search_mobile").length) {
            $('.search-form').slideUp(250).hide();
        }
    }
}

var title = document.title,
    push_history_back = true;

// Load img to in modal
function loadModalImg(modal, img_src) {
    var href_url = $(modal).data('href');
    modal.find('.modal-body').html('');

    modal.modal('show');
    modal.find('.modal-body').html($('<img/>')
        .attr('src', img_src)
        .attr('class', 'how-to-pay-image-full width_100'));
}

// Load product page in modal
function reloadModal(modal, push_history) {
    var href_url = $(modal).data('href');
    modal.find('.modal-body').html('');

    if (push_history === undefined) {
        setLocation(href_url);
    }
    push_history_back = true;
    modal.modal('show');
    modal.modal('resetScrollbar');
    modal.find('.modal-body').load($(modal).data('href'), {  },function (html) {
        document.title = $(modal).find('.title-ajax').text();
    });


}



// Set location for product url
function setLocation(curLoc){
    try {
        history.pushState(null, null, curLoc);
        return;
    } catch(e) {}
    location.hash = '#' + curLoc;
}

/* add listener for browser navigation arrows
 if url is "product" - load this product to modal
 else hide modal and remove manually all modal-backdrop
 because when click wherry often it can stay and not close - this is bug of modal
 */
window.addEventListener('popstate', function(event) {
    if (window.location.toString().indexOf("product") >= 0) {
        if(!$("#myModal").data('bs.modal').isShown) {
            $('.modal-backdrop').remove();
        }
        $('#myModal').data('href', window.location.toString());
        reloadModal($('#myModal'), true);
    } else {
        push_history_back = false;
        $('#myModal').modal('hide');
        $('.modal-backdrop').remove();
    }
});


// On modal hide change location
$(document).on('hidden.bs.modal', '.modal.fade', function (e) {

    $(this).find('.modal-body').removeClass('popup_info');
    if ($(this).is('#myModal')) { $(this).find('.modal-dialog').addClass('modal-lg');}

    if(push_history_back){
        var cur_url = $('#site-url').val();
        setLocation(cur_url);
    }else{
        push_history_back = true;
    }
    document.title = $('#site-title').val();
    if($( window ).width() >= 768 && $( window ).height() <= $('body').height())
    {
        $('#header-main').css('padding-right', 15);
        $('#textback_widget').css('right', 20);
    }

});

// On modal show
$(document).on('show.bs.modal', '.modal.fade', function (e) {
    if ($(window).width() <= '991') {
        $('.phone_mobile, #search_mobile, .top-basket, .hash_tags_mobile').hide();
    }
    if($( window ).width() >= 768 && $( window ).height() <= $('body').height())
    {
        $('#header-main').css('padding-right', 30);
        $('#textback_widget').css('right', 35);
    }
});

//show element of View table Campaign
$(document).on('click','#campaign_views .open-list', function () {
    var id = $(this).closest('tr').data('id');

    if ($(this).hasClass('glyphicon-arrow-down'))
    {
        $(this).removeClass('glyphicon-arrow-down').addClass('glyphicon-arrow-up');
        $(this).closest('tr').next('tr');
        $.each($('.detail_view'), function (key, value) {
            if ($(value).data('actionId') == id) {
                $(value).show();
            }
        });
    }else if($(this).hasClass('glyphicon-arrow-up'))
    {
        $(this).removeClass('glyphicon-arrow-up').addClass('glyphicon-arrow-down');
        $(this).closest('tr').next('tr');
        $.each($('.detail_view'), function (key, value) {
            if ($(value).data('actionId') == id) {
                $(value).hide();
            }
        });
    }
});

// clearning url from # for ajax load
var hash_temp = window.location.hash.replace('#', '');
if(isInt(hash_temp)) {
    history.pushState('', document.title, window.location.pathname);
    window.scrollTo(0, 0);
}
function isInt(n){
    return Number(n) === parseInt(n, 10) && n % 1 === 0;
}

$.fn.extend({
    animateCss: function (animationName) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        $(this).addClass('animated ' + animationName).one(animationEnd, function() {
            $(this).removeClass('animated ' + animationName);
        });
    }
});

$.fn.extend({
    animateCssAndHide: function (animationName) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        $(this).addClass('animated ' + animationName).one(animationEnd, function() {
            $(this).removeClass('animated ' + animationName);
            $(this).hide();
        });
    }
});

function openImageWindow(src) {
    var image = new Image();
    image.src = src;
    var width = image.width;
    var height = image.height;
    var left = (screen.width/2)-(width/2);
    var top = (screen.height/2)-(height/2);
    return window.open(src, "Отзыв о товаре", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, ' +
        'resizable=no, copyhistory=no, width='+width+', height='+height+', top='+top+', left='+left);
}

