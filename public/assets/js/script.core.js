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

        	$('.header_menu').on('click',  function(event) {
        		event.preventDefault();
        		$('body').find('.header_center_bottom').toggleClass('active');
        		$('body').find('.header_center_top').removeClass('active');
        	});

        	$('.search_btn').on('click',  function(event) {
        		event.preventDefault();
        		$('body').find('.header_center_top').toggleClass('active');
        		$('body').find('.header_center_bottom').removeClass('active');
        	});

            $('.js-social-link').on('click',  function(event) {
                $(this).addClass('active').closest('li').siblings('li').find('.js-social-link').removeClass('active');

                if($(this).hasClass('tel')){
                    $('.popup_hide_link.email').hide();
                    $('.popup_hide_link.tel').show();
                }
                else if($(this).hasClass('email')){
                    $('.popup_hide_link.email').show();
                    $('.popup_hide_link.tel').hide();
                }
				else if ($(this).hasClass('msg')) {
					$('.popup_hide_link').hide();
					$('.popup_hide_link.msg').show();
				}
                else{
                    $('.popup_hide_link.email').hide();
                    $('.popup_hide_link.tel').hide();   
                }
            });

        },

	}


	$(document).ready(function(){

		Core.DOMReady();

		// $('.scroll_y').mCustomScrollbar({
		// 	axis:"y",
		// 	theme:"dark"
		// });
	});

	$(window).load(function(){

		Core.windowLoad();

	});

})(jQuery);