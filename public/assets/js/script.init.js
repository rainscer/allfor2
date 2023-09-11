$(document).ready(function(){

	
	/* ------------------------------------------------
	FORMSTYLER START
	------------------------------------------------ */

			if ($('.styler').length){
				$('.styler').styler({
					selectSmartPositioning: true,
					selectSearch: true
				});
			}

	/* ------------------------------------------------
	FORMSTYLER END
	------------------------------------------------ */


	/* ------------------------------------------------
	POPUP START
	------------------------------------------------ */

			if($('[data-popup]').length){
				$('[data-popup]').on('click',function(){
				    var modal = $(this).data("popup");
				    $(modal).arcticmodal({
				    	afterOpen: function(){
				    		
				    	}
				    });
				});
			};

	/* ------------------------------------------------
	POPUP END
	------------------------------------------------ */


});

$(window).load(function() {
	
	
	/* ------------------------------------------------
	mCustomScrollbar START
	------------------------------------------------ */

			if($('.Scrollbar').length){
	            $(".Scrollbar").mCustomScrollbar({
	            	setHeight: 520,
	            	setWidth:"100%"
	            });
			};
			
    		if($('.mCustomScrollbarPopup').length){
    			$(".mCustomScrollbarPopup").mCustomScrollbar({
    				setHeight: 520,
    				setWidth:"100%",
    				mouseWheel:{
    					scrollAmount:188,
    					normalizeDelta:true
    				},
	    			scrollButtons:{enable:true,scrollType:"stepped"},
	    			keyboard:{scrollType:"stepped"},
	    			autoExpandScrollbar:true,
	    			snapAmount:188,
	    			snapOffset:65
    			});
    		}

	/* ------------------------------------------------
	mCustomScrollbar END
	------------------------------------------------ */

	/* hide right menu */
	$(document).mouseup(function (e){ // отслеживаем событие клика по веб-документу
		var menu = $('.header_right .main_menu'); // определяем элемент, к которому будем применять условия (можем указывать ID, класс либо любой другой идентификатор элемента)
		if (!menu.is(e.target) // проверка условия если клик был не по нашему блоку
			&& menu.has(e.target).length === 0) { // проверка условия если клик не по его дочерним элементам
			menu.removeClass('active'); // если условия выполняются - скрываем наш элемент
		}
	});

	$('.header_right .main_menu.active')
		?
		$('.header_right .main_menu.active a').each(function(){
			$(this).click(function(){
				$(this).closest('.main_menu').removeClass('active');
			})
		})
		: null;
	/* hide right menu */
});