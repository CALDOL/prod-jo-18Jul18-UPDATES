<?php /* Template Name: UserSubmitPost */


global $current_user;

$postTitleError      = '';
$postTypeListError   = '';
$postCommunityListError   = '';
$postCommunityList   = '';

$postAttachmentError = '';
$postContentError    = '';
$postTopicList       = array();
$postTarget          = '';
$postTypeList        = null;
$tagList             = null;
$hasError            = false;
$targetID            = '';

wp_get_current_user();
if ( is_user_logged_in() ) {


//set mce settings
	$settings = array(
		'wpautop'       => true,
		// use wpautop?
		'media_buttons' => false,
		// show insert/upload button(s)
		'textarea_name' => 'postContent',
		// set the textarea name to something different, square brackets [] can be used here
		'textarea_rows' => get_option( 'default_post_edit_rows', 10 ),
		// rows="..."
		'tabindex'      => '',
		'editor_css'    => '',
		// intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
		'editor_class'  => '',
		// add extra class(es) to the editor textarea
		'teeny'         => false,
		// output the minimal editor config used in Press This
		'dfw'           => true,
		// replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
		'tinymce'       => true,
		// load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
		'quicktags'     => true
		// load Quicktags, can be used to pass settings directly to Quicktags using an array()
	);

	/*
	*
	echo 'user is logged in and can either edit other posts or is the author of the current post';
	$caps = $current_user->allcaps;
	echo "<div style='color:white;'>";
	foreach ($caps as $key => $value) {
	echo "Key: $key; Value: $value<br />\n";
	}
	echo "</div>";
	*/

	$postTitleError = '';

	if ( isset( $_POST['submitted'] ) && isset( $_POST['post_nonce_field'] )
	     && wp_verify_nonce( $_POST['post_nonce_field'], 'post_nonce' )
	) {

		$postTopicList = isset( $_POST['postTopicList'] )
			? $_POST['postTopicList'] : array();

//echo "<h1> " . print_r($postTopicList) . "</h1>";

		/*********   REMOVE EXTRANEOUS WHITESPACE FROM THE TAGS **************/

		$tagList = trim( preg_replace( '/\s+/', ' ', $_POST['tagList'] ) );


		/***********   FORM VALIDATION  ****************************/

// validate that a TYPE has been entered
		if ( isset( $_POST['postTypeList'] )
		     && trim( $_POST['postTypeList'] ) === "-1"
		) {
			$postTypeListError = 'Please Choose a Type.';
			$hasError          = true;
		} else {
			$postTypeList = trim( $_POST['postTypeList'] );
		}

// validate that a Community has been entered
//	if(isset($_POST['postCommunityList']) && ($_POST['postCommunityList']) === "-1") {
		if ( !isset($_POST['postCommunityList']) ) {
			$postCommunityListError = 'Please Choose a Community.';
			$hasError               = true;
		} else {
			$postCommunityList = ( $_POST['postCommunityList'] );
		}

// validate that a TITLE has been entered
		if ( isset( $_POST['postTitle'] )
		     && trim( $_POST['postTitle'] ) === ''
		) {
			$postTitleError = 'Please enter a title for the file.';
			$hasError       = true;
		} else {
			$postTitle = trim( $_POST['postTitle'] );
		}

// validate that something has been entered in the DESCRIPTION field
		if ( isset( $_POST['postContent'] )
		     && trim( $_POST['postContent'] ) === ''
		) {
			$postContentError = 'Please enter a description of the file.';
			$hasError         = true;
		} else {
			$postContent = trim( $_POST['postContent'] );
		}


		/***************   VALIDATE THE ATTACHMENTS FOR SIZE AND FILETYPE  ****************/

		if ( isset( $_FILES['postAttachment'] )
		     && $_FILES['postAttachment']['error'] != 4
		) {

// check file extension and size

			$allowedExts = array(
				"gif",
				"GIF",
				"jpeg",
				"JPEG",
				"jpg",
				"JPG",
				"png",
				"PNG",
				"pdf",
				"PDF",
				"doc",
				"DOC",
				"docx",
				"DOCX",
				"ppt",
				"PPT",
				"pptx",
				"PPTX",
				"pps",
				"PPS",
				"ppsx",
				"PPSX",
				"xls",
				"XLS",
				"xlsx",
				"XLSX",
				"mdb",
				"MDB",
				"zip",
				"ZIP"
			);
			$temp        = explode( ".", $_FILES["postAttachment"]["name"] );
			$extension   = end( $temp );
			if ( ( ($_FILES["postAttachment"]["type"] == "image/gif" )
			       || ( $_FILES["postAttachment"]["type"] == "image/jpeg" )
			       || ( $_FILES["postAttachment"]["type"] == "image/jpg" )
			       || ( $_FILES["postAttachment"]["type"] == "image/pjpeg" )
			       || ( $_FILES["postAttachment"]["type"] == "image/x-png" )
			       || ( $_FILES["postAttachment"]["type"] == "image/png" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/msword" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/vnd.openxmlformats-officedocument.wordprocessingml.template" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/vnd.ms-excel" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/vnd.openxmlformats-officedocument.spreadsheetml.template" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/vnd.openxmlformats-officedocument.wordprocessingml.template" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/vnd.ms-powerpoint" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/vnd.openxmlformats-officedocument.presentationml.presentation" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/vnd.openxmlformats-officedocument.presentationml.template" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/vnd.openxmlformats-officedocument.presentationml.slideshow" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/x-msaccess" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/msaccess" )
			       || ( $_FILES["postAttachment"]["type"] == "application/pdf" )
			       || ( $_FILES["postAttachment"]["type"] == "application/zip" )
			       || ( $_FILES["postAttachment"]["type"] == "application/x-zip-compressed" )
			       || ( $_FILES["postAttachment"]["type"]
			            == "application/octet-stream" )
			     )
			     && in_array( $extension, $allowedExts )
			) {
				/*********  CHECK IF ANY GENERAL FILE ERRORS  ***********/
				if ( $_FILES["postAttachment"]["error"] > 0 ) {
					$postAttachmentError = "Error: "
					                       . $_FILES["postAttachment"]["error"]
					                       . "<br>";
					$hasError            = true;
				} /*************   CHECK IF THE FILE IS TOO BIG   ****************/
                elseif ( $_FILES['postAttachment']['size']
				         > 990000000
				) { //10 MB (size is also in bytes)
// File too big
					$postAttachmentError = 'The file is too big';
					$hasError            = true;
				} else {

//  FILE HAD NO PRELIMINARY VALIDATION ERRORS
				}
			} /********  END OF VALIDATION FOR FILE TYPE  ************/
			else {
				$postAttachmentError
					      = "Invalid file type.<br/>File types allowed are: doc, docx, gif, jpeg, jpg, mdb, pdf, png, pps, ppsx, ppt, pptx, xls, xlsx, zip (" . $_FILES["postAttachment"]["type"] . ")";
				$hasError = true;
			}


		}

		/*********   GET THE LIST OF TOPICS THE USER CHECKED *******************/
		$postTypeCommunityList = array_merge( (array) $postTypeList,
			(array) $postCommunityList );
		$postTopicTypeList     = array_merge( (array) $postTopicList,
			(array) $postTypeCommunityList );

		/*********   IF THERE ARE NO VALIDATION ERRORS, SAVE THE NEW FILE -- BASIC DATA FIRST, THEN ATTACHMENTS **************/

		if ( ! $hasError ) {
			$post_information = array(
				'post_title'    => esc_attr( strip_tags( $postTitle ) ),
				'post_content'  => esc_attr( ( $postContent ) ),
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_category' => $postTopicTypeList,
			);


//var_dump($tomTest2);
			$post_id = wp_insert_post( $post_information );
			wp_set_post_tags( $targetID, $tagList, true );

			if ( $post_id ) {


				$hasAttachment = true;


// check for attachment
				if ( $_FILES['postAttachment']['error'] != 4
				     && $_FILES['postAttachment']['name'] != ""
				) {

					if ( ! function_exists( 'wp_handle_upload' ) ) {
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
					}
					$uploadedfile     = $_FILES['postAttachment'];
					$upload_overrides = array( 'test_form' => false );
					$movefile         = wp_handle_upload( $uploadedfile,
						$upload_overrides );
					if ( $movefile ) {


//file is uploaded successfully. do next steps here.
						$wp_filetype   = $movefile['type'];
						$filename      = $movefile['file'];
						$wp_upload_dir = wp_upload_dir();
						$attachment    = array(
							'guid'           => $wp_upload_dir['url'] . '/'
							                    . basename( $filename ),
							'post_mime_type' => $wp_filetype,
							'post_title'     => preg_replace( '/\.[^.]+$/', '',
								basename( $filename ) ),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);
						$attach_id     = wp_insert_attachment( $attachment,
							$filename, $post_id );
						require_once( ABSPATH . 'wp-admin/includes/image.php' );
						$attach_data
							= wp_generate_attachment_metadata( $attach_id,
							$filename );
						wp_update_attachment_metadata( $attach_id,
							$attach_data );


						/* $newContent = $postContent . "<p><a href='" . wp_get_attachment_url( $attach_id ) . "' target='_blank'>" .  basename($filename) . "</a></p>";

						$updatedPost = array('ID' => $post_id, 'post_content' => $newContent);
						wp_update_post($updatedPost);
						*/

					}// end if movefile
					else {

						echo $postAttachmentError;
						die;

					}
				}

				$linkit = get_permalink( $post_id );
				wp_redirect( $linkit );
				exit;


// end isset postAttachment
//if($hasAttachment)

//$linkit = get_permalink($post_id);
//wp_redirect($linkit);


			}//end if post id
		}


	} // end if submitted


	?>


	<?php if ( is_user_logged_in() ) { ?>


		<?php get_header(); ?>

		<?php
		/** Themify Default Variables
		 *
		 * @var object
		 */
		global $themify; ?>
        <!-- USER-INSERT-POSTS -->
        <!-- child/index -->
        <!-- layout -->
        <div id="layout" class="clearfix pagewidth">

			<?php themify_content_before(); // hook ?>

            <!-- content -->
            <div id="content" class="clearfix">
				<?php themify_content_start(); //hook ?>



				<?php if ( $postCommunityListError != ''
				           || $postTypeListError != ''
				           || $postAttachmentError != ''
				           || $postTitleError != ''
				           || $postContentError != ''
				) : ?>
                    <div id="updateFileErrors"
                         style="background-color: cornsilk;border:2px solid maroon; padding:10px;margin-bottom:20px;">
                        <p>There was a problem. Any changes you just made to the
                            Title, Description, or Category have been retained
                            but not yet saved. If you
                            checked any attachments for deletion, they have been
                            unchecked. </p>
                        <ul>

							<?php if ( $postTypeListError != '' ) { ?>
                                <li>
                                    <span class="toolError"> <?php echo $postTypeListError; ?></span>
                                <//li>

							<?php } ?>

	                        <?php if ( $postCommunityListError != '' ) { ?>
                                <li>
                                    <span class="toolError"> <?php echo $postCommunityListError; ?></span>
                                <//li>

	                        <?php } ?>

							<?php if ( $postTitleError != '' ) { ?>
                                <li>
                                    <span class="toolError"> <?php echo $postTitleError; ?></span>
                                </li>

							<?php } ?>

							<?php if ( $postContentError != '' ) { ?>
                                <li>
                                    <span class="toolError"> <?php echo $postContentError; ?></span>
                                </li>

							<?php } ?>

	                        <?php if ( $postAttachmentError != '' ) { ?>
                                <li>
                                    <span class="toolError"> <?php echo $postAttachmentError; ?></span>
                                <//li>

	                        <?php } ?>

                        </ul>
                    </div>
                    <div class="clearfix"></div>
				<?php endif; ?>
                <form action="" id="primaryPostForm" method="POST"
                      enctype="multipart/form-data">

                    <fieldset class="bbp-form">
                        <legend><h2 id="submitNewFile">Submit File /
                                Interview</h2></legend>
                        <fieldset class="toolFieldSet">

                            <label for="postTypeList"><?php _e( 'Type:',
									'framework' ) ?></label><br/>
                            <div style="margin-left: 15px;">

								<?php $parentTypeID
									= ( get_category_by_slug( 'cat-type' ) )->term_id; ?>

								<?php wp_dropdown_categories( array(
									'hide_empty'       => 0,
									'name'             => 'postTypeList',
									'orderby'          => 'NAME',
									'show_option_none' => 'No Type',
									'child_of'         => $parentTypeID,
									'selected'         => $postTypeList
								) ); ?>
                            </div>
                        </fieldset>


						<?php /**********  DROP DOWN TO CHOOSE "COMMUNITY"  **********/ ?>

						<?php $checked = ''; ?>

                        <fieldset class="toolFieldSet">

                            <label for="postCommunityList"><?php _e( 'Community:',
									'framework' ) ?></label><br/>
                            <div style="margin-left: 15px;">
								<?php $args = array(
									'descendants_and_self' => 0,
									'selected_cats'        => false,
									'popular_cats'         => false,
									'walker'               => null,
									'taxonomy'             => 'category',
									'checked_ontop'        => true
								);


								$loop = 1;
								//wp_terms_checklist( $post_id, $args );?>

								<?php $parentTopicID
									= ( get_category_by_slug( 'cat-community' ) )->term_id; ?>

								<?php $communityCategories
									= get_terms( 'category', array(
									'orderby'    => 'name',
									'hide_empty' => 0,
									'parent'     => $parentTopicID,

								) );


								foreach ( $communityCategories as $term ) {

									if ( bbp_is_topic_edit() ) {
										if ( in_array( $term->name,
											$currentTags ) ) {

											$checked = "checked='checked'";
										} else {
											$checked = '';
										}
									} else {//might be postback

										if ( isset( $_POST['postCommunityList'] ) ) {
											if ( in_array( $term->term_id,
												$_POST['postCommunityList'] ) ) {
												$checked = "checked='checked'";

											} else {
												$checked = '';
											}
										}
									}
									echo '<input type="checkbox"' . $checked
									     . ' name="postCommunityList[]" value="'
									     . $term->term_id . '">&nbsp;'
									     . $term->name
									     . '&nbsp;&nbsp;&nbsp;&nbsp;';
									if ( $loop ++ % 3 == 0 ) {
										//echo "<br/>";
									}
								}
								?>

                            </div>
                        </fieldset>


                        <fieldset class="toolFieldSet">

                            <label for="postTopicList"><?php _e( 'Topic:',
									'framework' ) ?></label><br/>
                            <div style="margin-left: 15px;">
								<?php $args = array(
									'descendants_and_self' => 0,
									'selected_cats'        => false,
									'popular_cats'         => false,
									'walker'               => null,
									'taxonomy'             => 'category',
									'checked_ontop'        => true
								);


								$loop = 1;
								//wp_terms_checklist( $post_id, $args );?>

								<?php $parentTopicID
									= ( get_category_by_slug( 'cat-topic' ) )->term_id; ?>

								<?php $topicCategories = get_terms( 'category',
									array(
										'orderby'    => 'name',
										'hide_empty' => 0,
										'parent'     => $parentTopicID,

									) );

								foreach ( $topicCategories as $term ) {

									if ( $postTopicList != null
									     && in_array( $term->term_id,
											$postTopicList )
									) {
										$checked = "checked='checked'";
									} else {
										$checked = '';
									}
									echo '<input type="checkbox"' . $checked
									     . ' name="postTopicList[]" value="'
									     . $term->term_id . '">' . $term->name
									     . '&nbsp;&nbsp;';
									if ( $loop ++ % 3 == 0 ) {
										echo "<br/>";
									}
								}
								?>

								<?php //wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'postTopicList', 'orderby' => 'NAME', 'show_option_none' => 'No Topic', 'child_of' => 17, 'selected' => $category->parent)); ?>
                            </div>
                        </fieldset>

                        <fieldset class="toolFieldSet">

                            <div class="tagLinks">
                                <label for="tagList"><?php _e( 'Add tags: (separated with commas)',
										'framework' ) ?> </label><br/>
                                <input style="margin-left: 15px;" type="text"
                                       id="tagList" name="tagList"
                                       value="<?php echo $tagList ?>"/>
                            </div>
                        </fieldset>


                        <fieldset class="toolFieldSet">

                            <label for="postTitle"><?php _e( 'Title:',
									'framework' ) ?></label><br/>

                            <input style="margin-left: 15px;" type="text"
                                   name="postTitle" id="postTitle"
                                   value="<?php if ( isset( $_POST['postTitle'] ) ) {
								       echo $_POST['postTitle'];
							       } ?>" class="required"/>

                        </fieldset>

                        <fieldset class="toolFieldSet">

                            <label for="postContent"><?php _e( 'Description:',
									'framework' ) ?></label>
                            <!--
							<span><b>NOTE: </b> The Visual editor is not showing the icons for the toolbar.  The toolbar functions but you will need to hover over
							 each button to determine what it does. Please use the "Text" tab if that makes it easier.  We are working to resolve this issue ASAP.</span>
							-->
							<?php wp_editor( isset( $_POST['postContent'] )
								? $_POST['postContent'] : '', 'mainEditor',
								$settings ); ?>

							<?php /*
<textarea style="margin-left: 15px;" class="required" name="postContent" id="mainEditor"  rows="8" cols="30"><?php if(isset($_POST['postContent'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['postContent']); } else { echo $_POST['postContent']; } } ?></textarea>
*/
							?>
                        </fieldset>

                        <fieldset class="toolFieldSet">

                            <label for="postAttachment">Add attachment:</label>
                            <input type="file" name="postAttachment"
                                   id="postAttachment">

                        </fieldset>


                        <fieldset class="toolFieldSet submit">

							<?php wp_nonce_field( 'post_nonce',
								'post_nonce_field' ); ?>

                            <input type="hidden" name="submitted" id="submitted"
                                   value="true"/>
                            <button type="submit"><?php _e( 'Submit File',
									'framework' ) ?></button>
                            <span style="margin-left: 45px;">
				<?php

				//  CHECK TO SEE WHERE WE CAME FROM -- IF WE CAME BACK HERE BECAUSE
				// OF AN EDIT ERROR AND THE USER WANTS TO QUIT, WE WANT TO SEND
				// THEM TO THE ORIGINAL PIECE OF CONTENT, OTHERWISE WE 
				// SEND THEM BACK TO THE PAGE FROM WHENCE THEY CAME


				echo '<p style="text-align: right; padding: 15px 0"><button style="background-color:red;color:black;" onClick="window.history.back();" href="">Cancel</button></p>';


				?>
</span>
                        </fieldset>

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
			if ( $themify->layout != "sidebar-none" ): get_sidebar(); endif; ?>

        </div>
        <!-- /#layout -->

		<?php get_footer(); ?>


	<?php }//end caldol_can_edit_file()

	else {
		?>

		<?php get_header();

		echo "<div style='padding:50px 0; width:100%; background-color: #000000'>";
		echo "<div style='margin:50px;background-color: cornsilk; padding: 10px; border: 2px solid maroon; width:50%;text-align:center;margin: 0 auto;' id='primary'>";
		echo "<h2 style='color:maroon;'>You are not authorized to update this File</h2>";
		echo "<a href='/files/'>return to Files page</a>";
		?>

		<?php themify_content_end(); //hook
		?>
        </div>
		<?php themify_content_after(); //hook
		?>
        <!-- /#content -->

		<?php
/////////////////////////////////////////////
// Sidebar
/////////////////////////////////////////////
		if ( $themify->layout != "sidebar-none" ): get_sidebar(); endif; ?>

        </div>
        <!-- /#layout -->;

		<?php get_footer(); ?>

	<?php } //end else?>

<?php }//end userlogged on and can edit posts?>
