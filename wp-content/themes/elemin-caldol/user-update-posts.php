<?php /* Template Name: UserUpdatePost */

$jo_parent_type_category = (get_category_by_slug('cat-type'))->term_id;
$jo_parent_community_category = (get_category_by_slug('cat-community'))->term_id;
$jo_parent_topic_category = (get_category_by_slug('cat-topic'))->term_id;


global $current_user;

wp_get_current_user();

$isFilePost = false;
$postAttachmentError = '';
$postTitleError = '';
$postContentError = '';
$postTypeError = '';
$postCommunityError = '';
$postCommunityList = '';
$postCommunities = '';
$postType = '';
$postTypeList = '';
$tagList = '';

$communityList = '';


//print_r($_POST);

if  (is_user_logged_in() && current_user_can('edit_posts')) {


$postTitleError = '';
$hasError = false;
$hasAttachmentsToRemove = false;
$victimList = null;



//set mce settings 
$settings = array(
		'wpautop' => true, // use wpautop?
		'media_buttons' => true, // show insert/upload button(s)
		'textarea_name' => 'postContent', // set the textarea name to something different, square brackets [] can be used here
		'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
		'tabindex' => '',
		'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
		'editor_class' => '', // add extra class(es) to the editor textarea
		'teeny' => false, // output the minimal editor config used in Press This
		'dfw' => true, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
		'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
		'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
);




if(isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

	$targetID = (isset( $_POST['targetFile'] ) && is_numeric( $_POST['targetFile'] ) ) ? intval( $_POST['targetFile'] ) : 0;
	$tagList = trim(preg_replace( '/\s+/', ' ', $_POST['tagList']));
	
	// get the current categories so they can be merge with the
	// category that is selected on the update page, if it's changed.



	if($targetID != 0){
		//$postTarget = get_post($targetID);
		$currentCategories = wp_get_post_terms($targetID, 'category');
		$categoryList = array();
		foreach($currentCategories as $currCat){
			$categoryList[] = $currCat->term_id;
		}
		
		
		$newCats[] = $_POST['postCategoryList'];

		// print_r(array_merge($newCats, $categoryList));
		//print_r(array_merge($categoryList, $newCats));
		
		// CONFIRM A TYPE HAS BEEN SELECTED 
		if($_POST['postTypeList'] == -1) {
			$postTypeError = 'Please select a Type.';
			$hasError = true;
			$errorList[] = "Please select a Type";
		} else {
			$postType = $_POST['postTypeList'];
		}
		
		// CONFIRM A COMMUNITY HAS BEEN SELECTED 
		if(!isset($_POST['postCommunityList']) ) {
			$postCommunityError = 'Please select a Community.';
			$hasError = true;
			$errorList[] = "Please select a Community";
		} else {
			$postCommunityList = $_POST['postCommunityList'];
		}
		
	}
	//echo "<h1>Top: " . $targetID . "</h1>";
	// validate that a title has been entered
	if(trim($_POST['postTitle']) === '') {
		$postTitleError = 'Please enter a title.';
		$hasError = true;
		$errorList[] = "Please enter a Title.";
	} else {
		$postTitle = trim($_POST['postTitle']);
	}

	// validate that something has been entered in the content field
	if(trim($_POST['postContent']) === '') {
		$postContentError = 'Please enter a description for the file.';
		$hasError = true;
		$errorList[] = "Please enter a Description for the file.";
		} else {
		$postContent = trim($_POST['postContent']);
	}

	// validate that the file isn't too big
	/*
	 *
	if(trim($_POST['postContent']) === '') {
	$postContentError = 'Please enter a description of the file.';
	$hasError = true;
	} else {
	$postContent = trim($_POST['postContent']);
	}
	*/



	/**********   CHECK FOR ATTACHMENT REMOVAL   **************/

	if(isset($_POST['removeAttachment']))
	{
		$victimList = $_POST['removeAttachment'];
		$hasAttachmentsToRemove = true;

		//echo "<h1>victim list to remove is: " . ($victimList[0]) . " end list </h1>";

	}


	/**********   IF FORM HAS NEW ATTACHMENTS CHECK TO MAKE SURE THEY ARE THE RIGHT TYPE AND SIZE *********************/


	if(isset($_FILES['postAttachment']) && $_FILES['postAttachment']['error'] != 4) {

		// check file extension and size

		$allowedExts = array("gif", "GIF", "jpeg", "JPEG", "jpg", "JPG", "png", "PNG", "pdf", "PDF", "doc", "DOC", "docx", "DOCX", "ppt", "PPT", "pptx", "PPTX", "pps", "PPS", "ppsx", "PPSX", "xls", "XLS", "xlsx", "XLSX", "mdb", "MDB", "zip", "ZIP");
		$temp = explode(".", $_FILES["postAttachment"]["name"]);
		$extension = end($temp);
		if ((($_FILES["postAttachment"]["type"] == "image/gif")
				|| ($_FILES["postAttachment"]["type"] == "image/jpeg")
				|| ($_FILES["postAttachment"]["type"] == "image/jpg")
				|| ($_FILES["postAttachment"]["type"] == "image/pjpeg")
				|| ($_FILES["postAttachment"]["type"] == "image/x-png")
				|| ($_FILES["postAttachment"]["type"] == "image/png")
				|| ($_FILES["postAttachment"]["type"] == "application/msword")
				|| ($_FILES["postAttachment"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
				|| ($_FILES["postAttachment"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.template")
				|| ($_FILES["postAttachment"]["type"] == "application/vnd.ms-excel")
				|| ($_FILES["postAttachment"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
				|| ($_FILES["postAttachment"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.template")
				|| ($_FILES["postAttachment"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.template")
				|| ($_FILES["postAttachment"]["type"] == "application/vnd.ms-powerpoint")
				|| ($_FILES["postAttachment"]["type"] == "application/vnd.openxmlformats-officedocument.presentationml.presentation")
				|| ($_FILES["postAttachment"]["type"] == "application/vnd.openxmlformats-officedocument.presentationml.template")
				|| ($_FILES["postAttachment"]["type"] == "application/vnd.openxmlformats-officedocument.presentationml.slideshow")
				|| ($_FILES["postAttachment"]["type"] == "application/x-msaccess")
				|| ($_FILES["postAttachment"]["type"] == "application/msaccess")
				|| ($_FILES["postAttachment"]["type"] == "application/pdf")
                || ($_FILES["postAttachment"]["type"] == "application/zip")
		        || ($_FILES["postAttachment"]["type"] == "application/octet-stream")
            ) && in_array($extension, $allowedExts))
		{
			if ($_FILES["postAttachment"]["error"] > 0)
			{
				$postAttachmentError =  "Error: " . $_FILES["postAttachment"]["error"] . "<br>";
		$hasError = true;
		$errorList[] = "Attachment Error: " . $_FILES["postAttachment"]["error"];
							}
			elseif($_FILES['postAttachment']['size'] > 250000000) { //10 MB (size is also in bytes)
				// File too big
				$postAttachmentError = 'The file is too big';
				$hasError = true;
				$errorList[] = "The attachment you are trying to upload is too big (max size is 25MB).";
							}
			else{

				//file was successful
			}
		}
		else
		{
			$postAttachmentError =  "Invalid file type.<br/>File types allowed are: doc, docx, gif, jpeg, jpg, mdb, pdf, png, pps, ppsx, ppt, pptx, txt, xls, xlsx, zip";
		$hasError = true;
		$errorList[] = "Invalid file type.<br/>File types allowed are: doc, docx, gif, jpeg, jpg, mdb, pdf, png, pps, ppsx, ppt, pptx, txt, xls, xlsx, zip";
					}



	}//  /*************    END OF CHECKING ATTACHEMENTS



	$attachmentRemovalError = false;
	
	// /**********   CHECK FOR ATTACHMENTS TO REMOVE AND REMOVE THEM AS NECESSARY   **************/
	if($hasAttachmentsToRemove){
	
		if(!caldol_remove_attachments($victimList)){
			echo "attachment removal problem<br/>";
			echo ($removeFeedback);
			echo "<br/>space<br/>";
			var_dump($victimList);
			echo "<br/>end";
			//$hasError = true;
			$attachmentRemovalError = true;
			$errorList[] = "Attachment Removal Error";
				
				
		}
	}

	
		//*****************   IF THERE WERE NO ERRORS, SAVE THE UPDATE ******************/
	
	if(!$hasError){
		
		


		$newCatList = isset($_POST['postCategoryList'])?$_POST['postCategoryList']:array();
		$newTypeList = $_POST['postTypeList'];
		$newCommunityList = $_POST['postCommunityList'];

		$post_information = array(
				'ID' => $targetID,
				'post_title' => (strip_tags($postTitle)),
				'post_content' => (($postContent)),
				'post_category' => array_merge( array($newTypeList),$newCatList, $newCommunityList));

		$targetID = wp_update_post($post_information);
		wp_set_post_tags($targetID, trim(preg_replace( '/\s+/', ' ', $_POST['tagList'])), true);

		/**********************  IF THE UPDATE WAS SUCCESSFUL ADD THE ATTACHMENTS  *********************/
		if($targetID)
		{
				
				
				
				
			$hasAttachment = false;
				
				
			// check for attachment
			if( $_FILES['postAttachment']['error'] != 4 && $_FILES['postAttachment']['name']!=""){
				//echo "inside postattachment name, post id is: " . $post_id;
				if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
				$uploadedfile = $_FILES['postAttachment'];
				$upload_overrides = array( 'test_form' => false );
				$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
				
				if ( $movefile ) {
						
						
					//file is uploaded successfully. do next steps here.
					$wp_filetype = $movefile['type'];
					$filename = $movefile['file'];
					$wp_upload_dir = wp_upload_dir();
					$attachment = array(
							'guid' => $wp_upload_dir['url'] . '/' . basename( $filename ),
							'post_mime_type' => $wp_filetype,
							'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
							'post_content' => '',
							'post_status' => 'inherit'
					);
					$attach_id = wp_insert_attachment( $attachment, $filename, $targetID);
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
					wp_update_attachment_metadata( $attach_id, $attach_data );
						
						
					/* 					$newContent = $postContent . "<p><a href='" . wp_get_attachment_url( $attach_id ) . "' target='_blank'>" .  basename($filename) . "</a></p>";

					$updatedPost = array('ID' => $post_id, 'post_content' => $newContent);
					wp_update_post($updatedPost);
					*/				


				}// end if movefile
				else{
						
					echo "attachment error";//echo $postAttachmentError;


				}
			}  /**********    END ADDING A NEW/UPDATED ATTACHMENT  **************/
			
		  /**********   NO ERRORS SO TAKE THE USER TO THE FILE PAGE *********/
				
			$linkit = get_permalink($targetID);
			//echo $linkit;
			wp_redirect($linkit);
			exit();

		} //end target id



	echo "<h1>no target id</h1>";
}// if no errors

	
	// IF THERE WAS AN ERROR, REPOPULATE THE FORM AND SHOW THE ERRORS
	
	else{ /***************    THERE WAS AN ERROR  ******************************/
		
		//print_r($_GET['fileID']);
		
		//$targetID = ( isset( $_GET['fileID'] ) && is_numeric( $_GET['fileID'] ) ) ? intval( $_GET['fileID'] ) : 0;
		//if($targetID == 0){
		//	$targetID = (isset( $_POST['targetFile'] ) && is_numeric( $_POST['targetFile'] ) ) ? intval( $_POST['targetFile'] ) : 0;
		//}
		
		$victimList = null;
		
		$isFilePost = false;
		
		if($targetID != 0){
			$postTarget = get_post($targetID);
			//get any updated field information
			$postTarget->post_title = trim($_POST['postTitle']);
			$postTarget->post_content = trim($_POST['postContent']);
			$postCategories = array_merge( (array)$_POST['postCategoryList']);
			$postCommunities = array_merge( (array)$postCommunityList);
			$postType = $_POST['postTypeList'];
		
			
		}
		else{
			echo "<h1>Invalid targetID, notify administrator please";
		}
		
		
	}
	


} /****************   END IF SUBMITTED ***********************/

else{  /************   PAGE IS HIT FOR THE FIRST TIME ****************/
	
	//print_r($_GET['fileID']);
	$_POST['removeAttachment'] = null;

	$targetID = ( isset( $_GET['fileID'] ) && is_numeric( $_GET['fileID'] ) ) ? intval( $_GET['fileID'] ) : 0;


	//echo "targetID is: " . $targetID;
	if($targetID == 0){
	$targetID = (isset( $_POST['targetFile'] ) && is_numeric( $_POST['targetFile'] ) ) ? intval( $_POST['targetFile'] ) : 0;
	}
	$isFilePost = false;
	
	if($targetID != 0){
		$postTarget = get_post($targetID);

		
		if(post_is_in_descendant_category($jo_parent_type_category)){
				
			$isFilePost = true;
				
		}
		

		$currPostCategories = get_the_category($postTarget->ID);

		 		if($currPostCategories){
			foreach($currPostCategories as $postCategoryTemp){
							if($postCategoryTemp->parent == $jo_parent_topic_category){
				$postCategories[] = $postCategoryTemp->cat_ID;
				}
				
				if($postCategoryTemp->parent == $jo_parent_type_category){
					$postType = $postCategoryTemp->cat_ID;
				}

				if($postCategoryTemp->parent == $jo_parent_community_category){
					$postCommunities[] = $postCategoryTemp->cat_ID;
				}
				
				
			}
		 }
		
		/*  CALDOL CHANGE -- NOT SURE WHY THIS IS HERE */
		if(FALSE && !$isFilePost){

				echo "<script>alert('The document you requested is not of type file'); location.href=document.referrer;</script>";
				//header("Location: " . $_SERVER["HTTP_REFERER"]);
			}
	}
	else{
		echo "<script>alert('invalid file id'); location.href=document.referrer;</script>";
		//header("Location: " . $_SERVER["HTTP_REFERER"]);
	}
	
	
} // /**************   END ELSE FOR HITTING THE PAGE FOR THE FIRST TIME   ****************/

?>
<?php /********************************   END CUSTOM CODE ********************************/?>

<?php if(caldol_can_edit_file($postTarget->post_author)){?>
<?php get_header(); ?>

<?php 
/** Themify Default Variables
 *  @var object */
global $themify; ?>
		
<!-- child/index -->
<!-- layout -->
<div id="layout" class="clearfix pagewidth">
<aside id="caldol-nav-sidebar">
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('caldol-nav-sidebar') ) ?>
</aside>
    <?php themify_content_before(); // hook ?>
    
    <!-- content -->
	<div id="content" class="clearfix">
    	<?php themify_content_start(); //hook ?>
		

		<div class="toolHeader">
			<h2 style="text-align: center;" class="submitToolHeader">Update File / Interview</h2>
			<h2>
				You are updating: <span style="color: maroon;"><?php echo $postTarget->post_title ?>
				</span>
			</h2>
		</div>


		<?php if($postCommunityError != '' || $postTypeError != '' || $postAttachmentError != '' || $postTitleError != '' || $postContentError != '') :?>
		<div id="updateFileErrors">
			<p>There was a problem. Any changes you just made to the Title,
				Description, or Category have been retained but not yet saved. If
				you checked any attachments for deletion, they have been unchecked.
			</p>
			<ul>
				<?php if($postTypeError != '') { ?>
				<li><span class="toolError"><?php echo $postTypeError; ?> </span>
				<//li>

				<?php } ?>

				<?php if($postCommunityError != '') { ?>
				<li><span class="toolError"><?php echo $postCommunityError; ?> </span>
				<//li>

				<?php } ?>





				<?php if($postTitleError != '') { ?>
				<li><span class="toolError"><?php echo $postTitleError; ?> </span></li>

				<?php } ?>

				<?php if($postContentError != '') { ?>
				<li><span class="toolError"><?php echo $postContentError; ?> </span>
				</li>

				<?php } ?>

				<?php if($postAttachmentError != '') { ?>
				<li><span class="toolError"><?php echo $postAttachmentError; ?> </span>
				<//li>

				<?php } ?>
			</ul>
		</div>
	
	<?php 	//<div class="clearfix"></div> ?>
		<?php endif;?>

		<form action="" id="primaryPostForm" method="POST"
			enctype="multipart/form-data">
			<input type="hidden" id="targetFile" value='<?php echo $targetID; ?>'
				name="targetFile" />
				
		<fieldset class="toolFieldSet">
		
<?php $parentTypeID =  (get_category_by_slug('cat-type'))-> term_id; ?>

		<label for="postTypeList"><?php _e('Type:', 'framework') ?></label>
		
		
	
		
		
		<?php wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'postTypeList', 'orderby' => 'NAME', 'show_count' => true, 'show_option_none' => 'No Type', 'child_of' => $parentTypeID, 'heirarchical' => false, 'selected' => $postType)); ?>
		
		</fieldset>	


 <?php /**********  DROP DOWN TO CHOOSE "COMMUNITY"  **********/ ?>

			<fieldset class="toolFieldSet">

				<label for="postCommunityList"><?php _e('Community:', 'framework') ?>
				</label>
				<br/>
				<div style="margin-left: 20px;">
				<?php  $communityCategories = get_terms( 'category', array(
						'orderby'    => 'name',
						'hide_empty' => 0,
						'parent' 	=> $jo_parent_community_category,

				) );
				$loop = 1;
					//print_r($postCommunities);
				foreach($communityCategories as $term){
					//echo "in outer foreach loop " . var_dump($currentCategories);
					foreach($postCommunities as $currentCat){

						if ($term->term_id == $currentCat){
							$communityList[] = $term->term_id;
						}

					}
				}

				foreach($communityCategories as $term){
					
					$checked = in_array($term->term_id, (array)$communityList)?" checked='checked' ":"";

					echo '<input type="checkbox"' . $checked . 'name="postCommunityList[]" value="' . $term->term_id . '">'. $term->name . '&nbsp;&nbsp;';
				    if($loop++ % 3 == 0)
				       echo "<br/>";
				
				}
				?>
				</div>
				<?php //wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'postCategoryList', 'orderby' => 'NAME', 'show_option_none' => 'No Category', 'child_of' => 17, 'selected' => $postCategory)); ?>

			</fieldset>

			

				
			<fieldset class="toolFieldSet">

				<label for="postCategoryList"><?php _e('Topic:', 'framework') ?>
				</label>
				<br/>
				<div style="margin-left: 20px;">
				<?php  $topicCategories = get_terms( 'category', array(
						'orderby'    => 'name',
						'hide_empty' => 0,
						'parent' 	=> $jo_parent_topic_category,

				) );
				$loop = 1;
				//	print_r($postCategories);
				foreach($topicCategories as $term){
					//echo "in outer foreach loop " . var_dump($currentCategories);
					foreach($postCategories as $currentCat){

						if ($term->term_id == $currentCat){
							$catList[] = $term->term_id;
						}

					}
				}

				foreach($topicCategories as $term){
					
					$checked = in_array($term->term_id, $catList)?" checked='checked' ":"";

					echo '<input type="checkbox"' . $checked . 'name="postCategoryList[]" value="' . $term->term_id . '">'. $term->name . '&nbsp;&nbsp;';
				    if($loop++ % 3 == 0)
				       echo "<br/>";
				
				}
				?>
				</div>
				<?php //wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'postCategoryList', 'orderby' => 'NAME', 'show_option_none' => 'No Category', 'child_of' => 17, 'selected' => $postCategory)); ?>

			</fieldset>




			<fieldset class="toolFieldSet">
			<?php
			global $post;
			 $post = $postTarget; ?>
			<div class="tagLinks"><?php echo get_the_tag_list('<label for="tagList">Current Tags: </label">',', ',''); ?><br/>
			<label for="tagList"><?php _e('Add new tags: <span style="font-weight:normal;">(separated with commas)</span>', 'framework') ?></label><br/>
			<input type="text" id="tagList" name="tagList" value="<?php echo $tagList; ?>"/>		
			</div>	
			</fieldset>
			
			
			<?php //print_r($catList);	?>
			<fieldset class="toolFieldSet">

				<label for="postTitle"><?php _e('Title:', 'framework') ?> </label>
				<input type="text" name="postTitle" id="postTitle"
					value="<?php echo $postTarget->post_title; ?>" class="required" />

			</fieldset>


			<fieldset class="toolFieldSet">

			<label for="postContent"><?php _e('Description:', 'framework') ?>
				</label>
<!--
<span><b>NOTE: </b> The "Visual" tab is not showing the icons for the toolbar.  The toolbar functions but you will need to hover over
 each button to determine what it does. Please use the "Text" tab if that makes it easier.  We are working to resolve this issue ASAP.</span>
-->
				
				<?php wp_editor($postTarget->post_content, 'postContent' );	?>

<?php /*
				<textarea name="postContent" id="mainEditor" class="required" rows="8" cols="30"><?php echo $postTarget->post_content; ?>
				</textarea>
*/?>
			</fieldset>


			<fieldset class="toolFieldSet">
				<label for="attachmentList">Attachments:</label>
				<ul id="attachmentList" name="attachmentList">
					<?php echo caldol_get_attachments_link_list($postTarget->ID, true); ?>
				</ul>
				</p>
			</fieldset>

			<fieldset class="toolFieldSet">
				<label for="postAttachment">Add Attachment:</label> <input
					type="file" name="postAttachment" id="postAttachment">

			</fieldset>

			<fieldset class="toolFieldSet submit">

				<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>

				<input type="hidden" name="submitted" id="submitted" value="true" />
				<button type="submit">
					<?php _e('Update File', 'framework') ?>
				</button>
				<?php
				
				//  CHECK TO SEE WHERE WE CAME FROM -- IF WE CAME BACK HERE BECAUSE
				// OF AN EDIT ERROR AND THE USER WANTS TO QUIT, WE WANT TO SEND
				// THEM TO THE ORIGINAL PIECE OF CONTENT, OTHERWISE WE 
				// SEND THEM BACK TO THE PAGE FROM WHENCE THEY CAME
				
				 
					echo '<p style="padding: 15px 0"><button style="background-color:red;color:black;" onClick="window.history.back();" href="">Cancel</button></p>';
					
				
				?>
			</fieldset>


		</form>


	<!-- #content END -->

    	
		
	<?php themify_content_end(); //hook ?>
	</div>
    <?php themify_content_after(); //hook ?>
	<!-- /#content -->

	<?php 
	/////////////////////////////////////////////
	// Sidebar							
	/////////////////////////////////////////////
	if ($themify->layout != "sidebar-none"): get_sidebar(); endif; ?>

</div>
<!-- /#layout -->

<?php get_footer(); ?>


	<?php }//end caldol_can_edit_file() 

	else{?>
	
	<?php get_header(); 
	
		echo "<div style='padding:50px 0; width:100%; background-color: #000000'>";
	echo "<div style='margin:50px;background-color: cornsilk; padding: 10px; border: 2px solid maroon; width:50%;text-align:center;margin: 0 auto;' id='primary'>";
echo "<h2 style='color:maroon;'>You are not authorized to update this File</h2>";
echo "<a href='/files/'>return to Files page</a>";
?>

	<?php themify_content_end(); //hook ?>
	</div>
    <?php themify_content_after(); //hook ?>
	<!-- /#content -->

	<?php 
	/////////////////////////////////////////////
	// Sidebar							
	/////////////////////////////////////////////
	if ($themify->layout != "sidebar-none"): get_sidebar(); endif; ?>

</div>
<!-- /#layout -->;

<?php get_footer(); ?>
	
	<?php } //end else?>

<?php }//end userlogged on and can edit posts?>
