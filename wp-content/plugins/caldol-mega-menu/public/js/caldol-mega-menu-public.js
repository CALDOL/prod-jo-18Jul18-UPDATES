(function( $ ) {
	'use strict';



        /**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

        //var $j_caldol = jQuery.noConflict();




        $(function() {


            $('#jo-mega-menu-trigger-text, #jo-mega-menu-trigger-img').click(function() {
                $('.mega-menu-wrapper').toggle();
                if ($('.mega-menu-wrapper').is(':visible')) {
                   $('.mega-menu-wrapper').css('display','-ms-grid');
                   $('.mega-menu-wrapper').css('display','grid');
                     $('#jo-mega-menu-trigger-img').attr('src', wppb_custom.public_template_url + 'img/up_double_arrow_color.png');

                    $('span#jo-mega-menu-trigger-text').text("Hide Mega Menu");
                }
                else{
                    $('span#jo-mega-menu-trigger-text').text("Show Mega Menu");
                    $('#jo-mega-menu-trigger-img').attr('src',wppb_custom.public_template_url + 'img/down_double_arrow_color.png');
                    $('.mega-menu-wrapper').css('display','none');

                }
            });
        });


})( jQuery );
