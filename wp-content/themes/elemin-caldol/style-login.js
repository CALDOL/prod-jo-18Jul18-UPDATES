var $j_caldol = jQuery.noConflict();

 $j_caldol(function() { // when the DOM is ready...


$j_caldol('#more-banner-terms-trigger').click(function() {

$j_caldol('#more-banner-terms').toggle();
if( $j_caldol('#more-banner-terms').is(':visible')){
$j_caldol('#more-banner-terms-trigger').text("close...");
}
else{
$j_caldol('#more-banner-terms-trigger').text("more...");
}


});

$j_caldol('body.login div#login #loginform #wp-submit').click(function(e) {

         if (!$j_caldol('body.login div#login input#terms_acceptance_field').is(':checked')) {

         alert("You MUST agree to the terms of use by checking the 'I agree to the terms above' box in order to login.");
         e.preventDefault();
         return;
     }

     });

});
