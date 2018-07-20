var $j_caldol = jQuery.noConflict();




 $j_caldol(function() { // when the DOM is ready...

 $j_caldol('#show-member-search-help').click(function() {
         $j_caldol('#member-search-help').toggle();
         if ($j_caldol('#member-search-help').is(':visible')) {

         $j_caldol('#show-member-search-help > .button').text("Hide Search Help");
         }
         else{
             $j_caldol('#show-member-search-help > .button').text("Show Search Help");
		 }
     });



$j_caldol('#tools-open-help').click(function(){

$j_caldol('#tuesday-tools-help').toggle();
});



$j_caldol('label[for="signup_username"]').append("<br/><span style='font-weight: normal;'>(This is the name that will show on all posts.   We discourage the use of your rank within your username as the forum norm is that we don't use rank.  Members will be able to see your real name by clicking on it.)</span>");

$j_caldol('label[for="signup_email"]').append("<br/><span style='font-weight: normal;'>(This is the email address to which all direct messages, email notifications, and newsletters will be sent.)</span>");


$j_caldol('label[for="field_6"], label[for="field_16"], label[for="field_26"], label[for="field_21"]' ).append("<span class='why-span'>&nbsp;&nbsp;&nbsp;</span><span class='why-text' style='font-weight: normal;'><br/>(This information allows members to find people matching these characteristics.  Think of how powerful that can be!)</span>");

       $j_caldol('.why-span').click(function() {

        $j_caldol(this).next('.why-text').toggle();

});

       $j_caldol('#show-gmail-warning-text a').click(function() {

	$j_caldol('.gmail-warning-text').toggle();
	if($j_caldol('.gmail-warning-text').is(':visible')){
	 $j_caldol('#show-gmail-warning-text a').text('Close Notice');

}
else{
	 $j_caldol('#show-gmail-warning-text a').text('Read Notice');

}
	

});

/* SHOW FILTERED MEMBERS LIST HELP */

     $j_caldol('.fml-toggle').click(function(e) {
         $j_caldol('#fml-help').toggle();

         if($j_caldol('#fml-help').is(':visible')){
             $j_caldol('.fml-toggle').text('Close Help');

         }
         else{
             $j_caldol('.fml-toggle').text('Show Help');

         }
         return;

     });


        $j_caldol('.showDescription').click(function() {

        $targetDescription = $j_caldol(this).next();
        $targetDescription.toggle();

            if( $j_caldol($targetDescription).is(':visible') ){
              $j_caldol(this).html("&nbsp;&nbsp;&nbsp;...less");
              $targetDescription.css("display", "inline-block");
            }
            else{
            $j_caldol(this).html("&nbsp;&nbsp;&nbsp;...more");
            }
        });

	$j_caldol('.cclpd-inner-form .wpcf7-form-control.wpcf7-submit').click(function(){

	    $j_caldol('.cclpd-inner-form').hide();
	    $j_caldol('.cclpd-request-button').text('Open request form');

	});


	$j_caldol('.cclpd-request-button').click(function(event){

	    event.preventDefault();
	    $j_caldol('.wpcf7-response-output').hide();

	      targetForm = $j_caldol(this).parent().find('.cclpd-inner-form');
	      $j_caldol(targetForm).toggle();
	      if( targetForm.is(':visible') ){
              $j_caldol(this).text("Close form");
              //$targetForm.css("display", "none");
            }
            else{
            $j_caldol(this).html("Open request form");
            }/*
	      if(this.text == ('Open request form')){
		$j_caldol(this).text('Close form');
		$j_caldol(targetForm).show();
                if(!$j_caldol.browser.msie){
		//alert($j_caldol.browser);
		}
	     }
	    else{

	      $j_caldol(this).text('Open request form');
	      $j_caldol(targetForm).fadeOut("slow");

	    }
	    */
	    //$j_caldol(this).scrollTop(5);
	});
	 
			    	
	 $j_caldol("H3#reply-title").click(function(){
		 
		 $j_caldol("form#commentform").toggle();
	 }
	 );
	 
	 /****** CHECK IF USER LIKED THIS ITEM, IF SO, CHANGE THE BUTTON TEXT   *****/
	 
	 /**********   LIKE FEATURE  ****************/
	 /* THIS FUNCTION ALLOWS THE USER TO 'LIKE' A POST */

		// alert($j_caldol("#targetPostID").val());
		/* var hasLikedData = {
	 
			    action: 'has_liked_the_post',
			    postID: $j_caldol("#targetPostID").val() THIS METHOD IN IS CUSTOM-FUNCTIONS.PHP ajax_like_post()
			};

			$j_caldol.post(ajaxurl, hasLikedData, function(response) {
			    if(response =='yes') {
					//alert("has liked check:  logged in, " + response + ":");
			        // user is logged in, show the PL Tracker on the front page
			    	//$j_caldol(".likeCount").html(response);
			    	//$j_caldol(".likeButton").hide();
			    	$j_caldol(".hasLiked").show();
			    } else {
			    	$j_caldol(".likeButton").show();
				//alert("has liked check: not logged in: " + "data: " + hasLikedData.action + ", " + hasLikedData.postID + ", " + response );
			        // user is not logged in, don't show PL Tracker here
			    	//$j_caldol(".likeCount").html('no');
			    	//$j_caldol(".activity-content").remove();
			    }
			});*/
	    

/**********   LIKE FEATURE  ****************/
	 /* THIS FUNCTION ALLOWS THE USER TO 'LIKE' A POST */
	
	 $j_caldol(".likeButton").click(function(){
		// alert($j_caldol("#targetPostID").val());
		 
		 $currTargetID = $j_caldol(this).val();
         $j_caldol(this).fadeOut(800);

		 var data = {
	 
			    action: 'like_the_post',
			    postID: $j_caldol(this).val() /*THIS METHOD IN IS CUSTOM-FUNCTIONS.PHP ajax_like_post()*/
			};

		// alert("Post ID: " + $currTargetID);
		 //alert("action: " + data.action + ", postID: " + data.postID);
			$j_caldol.post(ajaxurl, data, function(response) {
			    if(response) {
					//alert($j_caldol("#likeCount_" + data.postID).text());
			        // user is logged in, show the PL Tracker on the front page
			    	$j_caldol("#likeCount_"+data.postID).html(response);
			    	//$j_caldol(".likeButton_"+data.postID).slideUp();
			    	$j_caldol("#hasLiked_" + data.postID).removeClass('hideLiked').toggle().fadeIn( 1500 );
			    } else {
				//alert("not logged in");
			        // user is not logged in, don't show PL Tracker here
			    	$j_caldol(".likeCount").html('error');
			    	//$j_caldol(".activity-content").remove();
			    }
			});
	    
	 });
	 
	 
	 
	 /*******   END LIKE FEATURE   **************/
	 
	 
	 /**********   P.I.E. FEATURE  ****************/
	 /* THIS FUNCTION ALLOWS THE USER TO 'P.I.E.' A POST */
	
	 $j_caldol(".pieButton").click(function(){
		// alert($j_caldol("#targetPostID").val());
		 
		 $currTargetID = $j_caldol(this).val();
         $j_caldol(this).fadeOut(800);
		 var data = {
	 
			    action: 'send_some_pie',
			    postID: $j_caldol(this).val() /*THIS METHOD IN IS CUSTOM-FUNCTIONS.PHP ajax_send_pie()*/
			};

		// alert("Post ID: " + $currTargetID);
		 //alert("action: " + data.action + ", postID: " + data.postID);
			$j_caldol.post(ajaxurl, data, function(response) {
			    if(response) {
					//alert($j_caldol("#likeCount_" + data.postID).text());
			        // user is logged in, show the PL Tracker on the front page
					//console.log(response);
                    $j_caldol("#pieCount_"+data.postID).html(response);

			    	$j_caldol("#hasPied_" + data.postID).removeClass('hidePied').toggle().fadeIn( 1500 );
			    } else {
				//alert("not logged in");
			        // user is not logged in, don't show PL Tracker here
			    	//$j_caldol(".likeCount").html('error');
			    	//$j_caldol(".activity-content").remove();
			    }
			});
	    
	 });
	 
	 
	 /*******   END LIKE FEATURE   **************/
	 
	 /**********   LIKE FEATURE  ****************/
	 /* THIS FUNCTION ALLOWS THE USER TO 'LIKE' A POST */
	
	 $j_caldol(".likeButtonn").click(function(){
		// alert($j_caldol("#targetPostID").val());
		 
		 $currTargetID = $j_caldol(this).val();
		 
		 var data = {
	 
			    action: 'like_the_post',
			    postID: $j_caldol(this).val() /*THIS METHOD IN IS CUSTOM-FUNCTIONS.PHP ajax_like_post()*/
			};

		// alert("Pfost ID: " + $currTargetID);
		 //alert("action: " + data.action + ", postID: " + data.postID);
			$j_caldol.post(ajaxurl, data, function(response) {
			    if(response) {
					//alert($j_caldol("#likeCount_" + data.postID).text());
			        // user is logged in, show the PL Tracker on the front page
			    	$j_caldol("#likeCount_"+data.postID).html(response);
			    	$j_caldol(".likeButton_"+data.postID).hide();
			    	$j_caldol("#hasLiked_" + data.postID).removeClass('hideLiked');
			    } else {
				//alert("not logged in");
			        // user is not logged in, don't show PL Tracker here
			    	$j_caldol(".likeCount").html('error');
			    	//$j_caldol(".activity-content").remove();
			    }
			});
	    
	 });
	 
	 
	 
	 /*******   END LIKE FEATURE   **************/
	 
	 
	 /**********   P.I.E. FEATURE  ****************/
	 /* THIS FUNCTION ALLOWS THE USER TO 'LIKE' A POST */
	
	 $j_caldol(".pieButtonn").click(function(){
		// alert($j_caldol("#targetPostID").val());
		 
		 $currTargetID = $j_caldol(this).val();
		 
		 var data = {
	 
			    action: 'send_some_pie',
			    postID: $j_caldol(this).val() /*THIS METHOD IN IS CUSTOM-FUNCTIONS.PHP ajax_like_post()*/
			};

		// alert("Post ID: " + $currTargetID);
		 //alert("action: " + data.action + ", postID: " + data.postID);
			$j_caldol.post(ajaxurl, data, function(response) {
			    if(response) {
					//alert($j_caldol("#likeCount_" + data.postID).text());
			        // user is logged in, show the PL Tracker on the front page
			    	
			    	$j_caldol(".pieButton_"+data.postID).hide();
			    	$j_caldol("#hasPied_" + data.postID).removeClass('hidePied');
			    } else {
				//alert("not logged in");
			        // user is not logged in, don't show PL Tracker here
			    	//$j_caldol(".likeCount").html('error');
			    	//$j_caldol(".activity-content").remove();
			    }
			});
	    
	 });
	 
	 
	 /*******   END LIKE FEATURE   **************/
	 
	 /* THIS FUNCTION CHECKS VIA AN AJAX CALL TO SEE IF THE USER IS LOGGED IN
	    IF THEY ARE NOT LOGGED IN, THE PL TRACKER IS INITIALLY LOADED BUT ONCE
            IT HAS BEEN DETERMINED THAT THE USER IS NOT LOGGED IN, THE TRACKER IS REMOVED
	*/

       
	 var data = {
			    action: 'caldol_is_user_logged_in' /*THIS METHOD IN IS CUSTOM-FUNCTIONS.PHP ajax_check_user_logged_in()*/
			};

			$j_caldol.post(ajaxurl, data, function(response) {
				//alert(response);
			    if(response == 1) {
			//		alert("logged in");  
			        // user is logged in, show the PL Tracker on the front page
			//$j_caldol("#tab-0-0-0-821.main-pl-tracker").show();
			$j_caldol(".home-tracker").show();
			    	//$j_caldol("#themify_builder_content-821").show();
			    } else {
			//alert("not logged in - " + response + ":");
			        // user is not logged in, don't show PL Tracker here
			// $j_caldol("#themify_builder_content-46").remove();
			 $j_caldol(".home-tracker").remove();
			    	//$j_caldol(".activity-content").remove();
			    }
			});
	    
			
			$j_caldol(".module_row .discussions-top-level #createNewDiscussionDiv").show();
			$j_caldol(".module_row .discussions-top-level #newDiscussionToggle").html(" (-)");
		   
		  // THIS FUNCTION ALLOWS THE TOGGLING OF THE NEW DISCUSSION INPUT AREA
		  
		    $j_caldol( "#openNewDiscussion" ).click(function() {
		    	targetDiv = $j_caldol('#createNewDiscussionDiv');
		    	$j_caldol(targetDiv).toggle();
		    	if(targetDiv.is(":visible")){
		    		$j_caldol("#newDiscussionToggle").html(" (-)");
		    	}
		    	else{
		    		$j_caldol("#newDiscussionToggle").html(" (+)");
		    		
		    	}
		    	});
		    
			   
			  // THIS FUNCTION ALLOWS THE TOGGLING OF THE NEW DISCUSSION INPUT AREA
			  
			    $j_caldol( "#openNewReply" ).click(function() {
			    	targetDiv = $j_caldol('#createNewReplyDiv');
			    	$j_caldol(targetDiv).toggle();
			    	if(targetDiv.is(":visible")){
			    		$j_caldol("#newReplyToggle").html(" (-)");
			    	}
			    	else{
			    		$j_caldol("#newReplyToggle").html(" (+)");
			    		
			    	}
			    	});

     var $targetYear = $j_caldol('p.YearMarker');
     $j_caldol($targetYear).next('div').hide();
        $j_caldol('.YearMarker').css('background-image', 'url(/pubs/armymagazine/images/open.gif)');

     $j_caldol('p.articleTitle').next('p').addClass('indented');
     

     $j_caldol('p.YearMarker:first').next('div').show();

     $j_caldol('p.YearMarker:first').css('background-image', 'url(/pubs/armymagazine/images/close.gif)');

     var $showAll = $j_caldol('#showAll');
     var $hideAll = $j_caldol('#hideAll');
     $showAll.click(function(e){

         $j_caldol('div.YearListing').show();

         $j_caldol('.YearMarker').css('background-image', 'url(/pubs/armymagazine/images/close.gif)');
         $j_caldol(this).addClass('selected');
         $j_caldol('#hideAll').removeClass('selected');
     });



     $hideAll.click(function(e){

         $j_caldol('div.YearListing').hide();

         $j_caldol('.YearMarker').css('background-image', 'url(/pubs/armymagazine/images/open.gif)');
         $j_caldol(this).addClass('selected');
         $j_caldol('#showAll').removeClass('selected');

     });


     $targetYear.click(function(e) {
         e.preventDefault();

         if( $j_caldol('#showAll').hasClass('selected')){
             $j_caldol(this).next('div').toggle();
             if ($j_caldol(this).next('div').is(':visible'))
                 $j_caldol(this).css('background-image', 'url(/pubs/armymagazine/images/close.gif)');
             else
                 $j_caldol(this).css('background-image', 'url(/pubs/armymagazine/images/open.gif)');


             return;
         }

         var $targetYear = $j_caldol(this);
         $j_caldol('div.YearListing:visible').not($targetYear.next('div')).toggle();

         $j_caldol('div.YearListing:hidden').prev('p.YearMarker').css('background-image', 'url(/pubs/armymagazine/images/open.gif)');

         var $p = $targetYear.next('div');

         $p.toggle();

         if( $targetYear.next('div').is(':visible')){
             $targetYear.css('background-image', 'url(/pubs/armymagazine/images/close.gif)');
         }
         else{
             $targetYear.css('background-image', 'url(/pubs/armymagazine/images/open.gif)');


         }

     });


});

jQuery(function($){
	$('#filter').submit(function(e){
		e.preventDefault();
		var filter = $(this);
		$.ajax({
			url:filter.attr('action'),
			data:filter.serialize(), // form data
			type:filter.attr('method'), // POST
			beforeSend:function(xhr){
				filter.find('button').text('Processing...'); // changing the button label
			},
			success:function(data){
				filter.find('button').text('Apply filter'); // changing the button label back
				$('#response').html(data); // insert data
			}
		});
		return false;
	});
});
 
 
	/*
	 * 
	 	$j_caldol("#mainEditor").addClass("mceEditor");
		if ( typeof( tinyMCE ) == "object" &&
			 typeof( tinyMCE.execCommand ) == "function" ) {
			tinyMCE.execCommand("mceAddControl", false, "mainEditor");
		} 
		
		*/
	 
	 /*  FUNCTION TO USE POPUP FOR FILESIZE*/

	 /*
	  * 
	  $j_caldol('#postAttachment').on('change', function() {
	       // console.log('This file size is: ' + (this.files[0].size/1024/1024).toFixed(2) + " MB");
	        alert('This file size is: ' + (this.files[0].size/1024/1024).toFixed(2) + " MB");
	      });
	 */
	 
	 
 //  Move the window's scrollTop to the offset position of #now
	 /*
	  * 	
	//function scrollPastHeader(){
	    //$j_caldol(window).scrollTop(jQuery('#main-nav').offset().top - 50);	
	    if(location.pathname != '/'){	
	    $j_caldol('html, body').animate({scrollTop: $j_caldol('#main-nav').offset().top-50}, 0);
	   // alert(location.pathname);
	}
	

setTimeout(function() {
scrollPastHeader();
}, 100);
}

*/
	//$j_caldol("#themify_builder_content-47").hide(); 

