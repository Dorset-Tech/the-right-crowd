/*global $:false */

jQuery(document).ready(function($){'use strict';


    /* -------------------------------------- */
    //      RTL Support Visual Composer
    /* -------------------------------------- */    
    var delay = 100;
    function themeum_rtl() {
        if( jQuery("html").attr("dir") == 'rtl' ){
            if( jQuery( ".entry-content > div" ).attr( "data-vc-full-width" ) =='true' )    {
                jQuery('.entry-content > div').css({'left':'auto','right':jQuery('.entry-content > div').css('left')}); 
            }
        }
    }
    setTimeout( themeum_rtl , delay);

    jQuery( window ).resize(function() {
        setTimeout( themeum_rtl , 1);
    }); 
   





/* ************************************* */
/* ***********	Sticky Nav ************* */
/* ************************************* */
	$(window).on('scroll', function(){'use strict';
		if ( $(window).scrollTop() > 130 ) {
			$('#masthead').addClass('sticky');
		} else {
			$('#masthead').removeClass('sticky');
		}
	});

$("a[data-rel]").prettyPhoto();

/* ************************************* */
/* ***********	Menu Fix *************** */
/* ************************************* */
	// ------------- Menu Start ----------------------
	$('#showmenu').on( "click",function() {
			$('.main-nav').slideToggle("fast","linear");
		});
	//add and remove class
	var $window = $(window),
	     $ul = $('ul.main-nav');

	if ($window.width() < 768) {
	   $ul.removeClass('slideRight'); 
	};



/* ************************************* */
/* ********** Carousel Setup ********** */
/* ************************************* */
	$(window).on('resize', function () {
	    if ($window.width() < 768) {
	       $ul.removeClass('slideRight');
	   	}else{
		    $ul.addClass('slideRight')
		}
	 });
    // Video Carosuel
    var owlrtl = false;
    if( jQuery("html").attr("dir") == 'rtl' ){
        owlrtl = true;
    }
    
	//setup owl-carousel for partners
	$('.popular-ideas').owlCarousel({
		items: 3,
		// itemsCustom: [[0,1], [768,3], [992,3]],
		dots: false,
		nav: false,
		rtl: owlrtl,
		responsive: {
			0: {
				items: 1,
				margin: 30
			},
			992: {
				items: 3,
				margin: 30
			}
		}
	});
	// scroll animation initialize
	new WOW().init();


	//Window-size div
	$(window).resize(function() {
		$('#comming-soon').height($(window).height());
	});

	$(window).trigger('resize');


   	$(window).resize(function() {
		$('#error-page').height($(window).height());
	}); 
    $(window).trigger('resize');


	$(".youtube a[data-rel^='prettyPhoto']").prettyPhoto();
	$(".vimeo a[data-rel^='prettyPhoto']").prettyPhoto();


	/* --------------------------------------
	*		Shortcode hover color effect 
	*  -------------------------------------- */
	var clr = '';
	var clr_bg = '';
	var clr_border = '';
	$(".thm-color").on({
	    mouseenter: function () {
	     	clr = $(this).css('color');
			clr_bg = $(this).css('backgroundColor');
			clr_border = $(this).css('border-color');
			$(this).css("color", $(this).data("hover-color"));
			$(this).css("background-color", $(this).data("hover-bg-color"));
			$(this).css("border-color", $(this).data("hover-border-color"));
	    },
	    mouseleave: function () {
	        $(this).css("color", clr );
			$(this).css("background-color", clr_bg );
			$(this).css("border-color", clr_border );
	    }
	});



	$(".wpneo_donate_amount_field").on('keypress',function (e) {
	     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	        return false;
	    }
	});




	
});
