;(function($){

	"use strict";

	var Core = {

		DOMReady: function(){

			var self = this;

			self.events();
        },

        windowLoad: function(){

            var self = this;
            
			
		},

        /**
        ** events
        **/

        events : function(){

            $('.header_category').on('click',  function(event) {
                event.preventDefault();
                $('body').find('.category_link.mob_category').toggleClass('active');
                $('body').find('.header_right .main_menu, .header_center_top').removeClass('active');
            });

            $('.header_menu').on('click',  function(event) {
                event.preventDefault();
                $('body').find('.header_right .main_menu').toggleClass('active');
                $('body').find('.category_link.mob_category, .header_center_top').removeClass('active');
            });

        	$('.search_btn').on('click',  function(event) {
        		event.preventDefault();
        		$('body').find('.header_center_top').toggleClass('active');
        		$('body').find('.header_center_bottom').removeClass('active');
        	});

            $('.js-social-link').on('click',  function(event) {
                $(this).addClass('active').closest('li').siblings('li').find('.js-social-link').removeClass('active');

                if ($(this).hasClass('viber')) {
                    $('.popup_hide_link').hide();
                    $('.popup_hide_link.viber').show();
				} else if ($(this).hasClass('telegram')) {
                    $('.popup_hide_link').hide();
                    $('.popup_hide_link.telegram').show();
				} else if ($(this).hasClass('whatsapp')) {
                    $('.popup_hide_link').hide();
                    $('.popup_hide_link.whatsapp').show();
				} else if ($(this).hasClass('email')) {
                    $('.popup_hide_link').hide();
                    $('.popup_hide_link.email').show();
				} else if ($(this).hasClass('tel')) {
                    $('.popup_hide_link').hide();
                    $('.popup_hide_link.tel').show();
                } else if ($(this).hasClass('msg')) {
                    $('.popup_hide_link').hide();
                    $('.popup_hide_link.msg').show();
				} else $('.popup_hide_link').hide();
            });

        },

	}

	$(document).ready(function(){

		Core.DOMReady();

        // $('.scroll_y').mCustomScrollbar({
        //     axis:"y",
        //     theme:"dark",
        //     onScrollStart: function(){
        //         console.log('scroll');
        //         return btnUp.addClass('show');
        //     },
        //     onTotalScrollBack: function(){
        //         return btnUp.removeClass('show');
        //     }
        // });

	});

	$(window).load(function(){

		Core.windowLoad();

	});

})(jQuery);