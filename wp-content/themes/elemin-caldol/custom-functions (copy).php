<?php

/* LOAD THE PARENT THEME */

function theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
   // wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css',array( $parent_style ));
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );


/*  ADD THE FORUM DESCRIPTION TO THE TOP OF THE FORUM PAGE */
//filter to add description after forums titles on forum index
function rw_singleforum_description() {
  echo '<div class="bbp-forum-content">';
 // echo bbp_forum_content();
  echo "<p>Listed below are the topic-specific areas in which you can initiate and/or participate in a discussion.  Click on a topic and you will see the list of discussions in that area.  You can start a new discussion at the bottom of that page.</p>";
  echo '</div>';
}
add_action( 'bbp_template_before_single_forum' , 'rw_singleforum_description');


add_action( 'phpmailer_init', 'caldol_phpmailer_init' );
function caldol_phpmailer_init( PHPMailer $phpmailer ) {
    $phpmailer->Host = 'smtp.1and1.com';
    $phpmailer->Port = 25; // could be different
    $phpmailer->Username = 'contact@platoonleader.net'; // if required
    $phpmailer->Password = 'C@lD0l!!'; // if required
    $phpmailer->SMTPAuth = true; // if required
    // $phpmailer->SMTPSecure = 'ssl'; // enable if required, 'tls' is another possible value

    $phpmailer->IsSMTP();
}


/* send all contact form submissions to 8caldol as well */

//add_action('wpcf7_before_send_mail', 'include_8caldol_contact_us');

function include_8caldol_contact_us($cf7){


$submission = WPCF7_Submission::get_instance();
if($submission){
$posted_data = $submission->get_posted_data(); 

}
if($cf7->id == 867){
$message = "START OF MESSAGE<br/>";

$message .= $cf7->title . "<br/>";
$message .= $posted_data["your-name"] . "<br/>";
$message .= $posted_data["your-email"] . "<br/>";
//$message .= $posted_data["isAMember"] . "<br/>";
$message .= $posted_data["your-topic"] . "<br/>";
$message .= $posted_data["your-subject"] . "<br/>";
$message .= $posted_data["your-message"] . "<br/>";


$headers = array('Content-Type: text/plain; charset=\"UTF-8\"');

wp_mail('leromt@gmail.com', '**PL** Contact Us', $message, $headers);
}

//THIS IS FOR FUTURE USE
if(false && $cf7->id != 867){
$message = "START OF MESSAGE<br/>";

$message .= $cf7->title . "<br/>";
$message .= $posted_data["your-name"] . "<br/>";
$message .= $posted_data["your-DEE-email"] . "<br/>";
$message .= $posted_data["your-unit"] . "<br/>";
$message .= $posted_data["your-source"] . "<br/>";

$sub_text = print_r($submission, true);
$post_text = print_r($posted_data, true);
$mail2 = $cf7_data->mail['body'];

$message .= "Submission data: <br/> " . $sub_text . "<br/>********************<br/>";
$message .= "Posted data: <br/>" . $post_text . "<br/>********************<br/><br/>";
$message .= "mail_2:<br/>" . $mail2;

$headers = array('Content-Type: text/plain; charset=\"UTF-8\"');

wp_mail('leromt@gmail.com', 'cf7 test', $message, $headers);

}
}

// Add custom validation for CF7 form fields
function is_mil_email($email){ // Check against list of common public email providers & return true if the email provided *doesn't* match one of them

	if(
	
	preg_match('/.+\@.*\.mil/i', $email)
	/*

	||
	preg_match('/@hotmail.com/i', $email) ||
	preg_match('/@live.com/i', $email) ||
	preg_match('/@msn.com/i', $email) ||
	preg_match('/@aol.com/i', $email) ||
	preg_match('/@yahoo.com/i', $email) ||
	preg_match('/@inbox.com/i', $email) ||
	preg_match('/@gmx.com/i', $email) ||
	preg_match('/@me.com/i', $email)
	*/
	){
		return true; // It's a publicly available email address
	}else{
		return false; // It's probably a company email address
	}
}
function DEE_validation_filter_func($result,$tag){

	$type = $tag['type'];
	$name = $tag['name'];
	$confirmDEEEmail = $_POST['confirm-DEE-email'];
        $yourDEEEmail = $_POST['your-DEE-email'];
;
	
	if('your-DEE-email' == $name){ // Only apply to fields with the form field name of "company-email"
		$the_value = $_POST[$name];
		if(!is_mil_email($the_value)){ // Isn't a company email address (it matched the list of free email providers)
			$result['valid'] = false;
                         //$errorText = "Your email address MUST be a .mil address";

			$result['reason'][$name] = 'Your email address MUST be a .mil address. ';
		}
	}	
	if($name == 'confirm-DEE-email' && ($confirmDEEEmail != $yourDEEEmail) ){
			$result['valid'] = false;
                        //$errorText = "The email addresses don't match.";
			$result['reason'][$name] = 'The email addresses don\'t match';
			}


        //$result['reason'][$name] = $errorText;
	return $result;
}
add_filter( 'wpcf7_validate_email', 'DEE_validation_filter_func', 10, 2 ); // Email field or contact number field
add_filter( 'wpcf7_validate_email*', 'DEE_validation_filter_func', 10, 2 ); // Req. Email field or contact number



function validate_first_last_name(){

    global $bp;

    $firstName = $_POST['field_11'];
    $lastName = $_POST['field_12'];

    if ( (strlen($firstName) <= 2) && (strlen($lastName) <= 2) ){

        //$tld_index = strrpos($email,'.');
        //$tld = substr($email,$tld_index);

        //if ($tld != '.edu'){
	

            $bp->signup->errors['field_11'] = $firstName . ', ' . $lastName . ': Sorry, your first and last names cannot both be 2 characters or less';
        //}

    }

}

add_action('bp_signup_validate','validate_first_last_name');



/************ GROUP EMAIL HELPERS ****************/


function get_custom_email_list($user_id_list = null, $isTest = false){
	global $wpdb;
	if($user_id_list == null)
		return "";
	$id_list =  implode(",", $user_id_list);
	if($isTest){
	$emailArray = $wpdb->get_col("SELECT 'leromt@gmail.com' FROM {$wpdb->prefix}users WHERE ID IN ($id_list)");
	}
	else{
	$emailArray = $wpdb->get_col("SELECT user_email FROM {$wpdb->prefix}users WHERE ID IN ($id_list)");
	}
	//print_r($emailArray);
	

	return $emailArray;
	
}

function render_year_group_dropdown(){
	
	$ygStart = microtime(true);
	
	global $wpdb;
	$ygArray = $wpdb->get_results( 
	"
	SELECT count(A.field_id) as ygCount, value 
			FROM caldol_pl_bp_xprofile_data A 
			JOIN caldol_pl_usermeta B on A.user_id=B.user_id 
			WHERE (B.meta_key='pw_user_status' AND B.meta_value='approved') 
			AND (A.field_id = 28 AND A.value not like '%choose%') 
			GROUP BY value 
			ORDER BY value
	"
);
	ob_start();
	echo "<select name='yg-dropdown' id='yg-dropdown'>";
	echo "<option value='false'>Choose a year group</option>";
	foreach($ygArray as $ygRow){
		
		echo "<option value='" . $ygRow->value . "'>" . $ygRow->value . " (" . $ygRow->ygCount . ") </option>";
		
	}
	echo "</select>";
	//echo "time used: " . (microtime(true) - $ygStart);
	return ob_get_clean();
}

function render_branch_dropdown(){
	
	$brStart = microtime(true);
	
	global $wpdb;
	$branchArray = $wpdb->get_results(
			"
	SELECT count(A.field_id) as branchCount, value 
			FROM caldol_pl_bp_xprofile_data A 
			JOIN caldol_pl_usermeta B on A.user_id=B.user_id 
			WHERE (B.meta_key='pw_user_status' AND B.meta_value='approved') 
			AND (A.field_id = 2 AND A.value not like '%choose%') 
			GROUP BY value 
			ORDER BY value
	"
	);

	ob_start();
	echo "<select name='br-dropdown' id='br-dropdown'>";
	echo "<option value='false'>Choose a branch</option>";
	foreach($branchArray as $branchRow){

		echo "<option value='" . $branchRow->value . "'>" . $branchRow->value . " (" . $branchRow->branchCount . ") </option>";

	}
	echo "</select>";
	//echo "time used: " . (microtime(true) - $brStart);
	return ob_get_clean();
}

function render_current_post_dropdown(){
	
	$cpStart = microtime(true);
	global $wpdb;
	$currentPostArray = $wpdb->get_results(
			"
	SELECT count(A.field_id) as currentPostCount, value 
			FROM caldol_pl_bp_xprofile_data A 
			JOIN caldol_pl_usermeta B on A.user_id=B.user_id 
			WHERE (B.meta_key='pw_user_status' AND B.meta_value='approved') 
			AND (A.field_id = 199 AND A.value not like '%choose%') 
			GROUP BY value 
			ORDER BY value

	"
	);

	ob_start();
	echo "<select name='cp-dropdown' id='cp-dropdown'>";
	echo "<option value='false'>Choose a post</option>";
	foreach($currentPostArray as $currentPostRow){

		echo "<option value='" . $currentPostRow->value . "'>" . $currentPostRow->value . " (" . $currentPostRow->currentPostCount . ") </option>";

	}
	echo "</select>";
	//echo "time used: " . (microtime(true) - $cpStart);
	return ob_get_clean();
}




/************ END GROUP EMAIL HELPERS ****************/

/* CALDOL get list of member ids based on Group email field selection */

 function get_custom_ids($fieldID, $fieldValue) {
	global $wpdb;

	// collection based on an xprofile field
	//$custom_ids = $wpdb->get_col("SELECT user_id FROM {$wpdb->prefix}bp_xprofile_data A JOIN {$wpdb->prefix}users B ON A.user_id=B.ID WHERE A.field_id = $fieldID AND A.value = '$fieldValue'");
	$custom_ids = $wpdb->get_col("SELECT B.user_id FROM {$wpdb->prefix}bp_xprofile_data A JOIN {$wpdb->prefix}usermeta B ON A.user_id=B.user_id WHERE (B.meta_key = 'pw_user_status' AND B.meta_value = 'approved') AND (A.field_id = $fieldID AND A.value = '$fieldValue')");
	
	return $custom_ids;
}


function add_honeypot() {
    echo '<div style="display: none;"><input type="text" name="hpval" /></div>';
}
add_action('bp_after_signup_profile_fields','add_honeypot');
function check_honeypot() {
    if (!empty($_POST['hpval'])) {
        global $bp;
       wp_redirect('http://google.com');
        exit;
    }
}
add_filter('bp_core_validate_user_signup','check_honeypot');



add_shortcode( 'caldol-login-form', 'caldol_login_form_shortcode' );
/**
* Displays a login form.
*
* @since 0.1.0
* @uses wp_login_form() Displays the login form.
*/
function caldol_login_form_shortcode( $atts, $content = null ) {

$defaults = array( "redirect" => site_url( $_SERVER['REQUEST_URI'] )
);

extract(shortcode_atts($defaults, $atts));
if (!is_user_logged_in()) {

$content = "<div id='welcomeLogin'>";
$content .= wp_login_form( array( 'echo' => false ) );
$content .= "</div>";

return $content;
}

}


function caldol_force_ssl(){
    return false;
}

add_filter('force_ssl_admin', 'caldol_force_ssl', 10, 3);


function ajax_like_post($data) {

	//global $post;

	//$postID = get_post( $post )->ID;



	$postID = $_POST['postID'];

	//echo "here is the " . $postID . "ID";



	if(setPostLikes($postID)){



		echo getPostLikes($postID);

	}

	else {

		echo "error";

	}

	die();

}

add_action('wp_ajax_like_the_post', 'ajax_like_post');

//add_action('wp_ajax_nopriv_like_the_post', 'ajax_like_post');






/* function ajax_liked_post($data) {

	//global $post;

	//$postID = get_post( $post )->ID;



	$postID = $_POST['postID'];

	$current_user = wp_get_current_user();

	if(hasLiked($postID, $current_user->ID)){

		//echo 'pid: ' . $postID . ', cid: ' . $current_user->ID;

		echo 'yes';

	}

	else {

		echo 'no';

	}

	die();

}

add_action('wp_ajax_has_liked_the_post', 'ajax_liked_post'); */

//add_action('wp_ajax_nopriv_has_liked_the_post', 'ajax_liked_post');









function ajax_send_pie($data) {

	//global $post;

	//$postID = get_post( $post )->ID;



	$postID = $_POST['postID'];

	//echo "here is the " . $postID . "ID";



	if(setPostPie($postID)){



		echo getPostPie($postID);

	}

	else {

		echo "error";

	}

	die();

}

add_action('wp_ajax_send_some_pie', 'ajax_send_pie');

//add_action('wp_ajax_nopriv_like_the_post', 'ajax_like_post');



function getPostPie($postID){

	$current_user = wp_get_current_user();



	$count_key = '_post_pie_count';

	$pie_key = '_post_pie_id';



	$count = get_post_meta($postID, $count_key, true);

	/* if($count==''){

		delete_post_meta($postID, $count_key);

	add_post_meta($postID, $count_key, '0');

	return "0 Likes";

	}

	*/



	if($count==""){

		return "0 PIE notices";

	}



	if($count == 1){

		return $count.' PIE notice ';

	}

	else{

		return $count.' PIE notices';

	}

  die();

}

function setPostPie($postID) {



	$current_user = wp_get_current_user();

	$count_key = '_post_pie_count';

	$pie_key = '_post_pie_id';



	//var_dump($postID);

	$count = get_post_meta($postID, $count_key, true);





	if(!hasPied($postID, $current_user->ID)){

		if($count==''){

			$count = 1;

			delete_post_meta($postID, $count_key);

			delete_post_meta($postID, $pie_key);



			add_post_meta($postID, $count_key, $count);

			add_post_meta($postID, $pie_key, $current_user->ID, true);

		}

		else{

			$count++;

			update_post_meta($postID, $count_key, $count);



			add_post_meta($postID, $pie_key, ($current_user->ID));



		}

	}

	if($count){
		
		$currPost = get_post($postID);
		$authorID = $currPost->post_author;
		 $authorEmail = get_the_author_meta('user_email', $authorID );
		 $authorFirstName = get_the_author_meta('first_name', $authorID );

		 	
		 /* set some headers */
		 // Always set content-type when sending HTML email



		 

		 // More headers

		 //$headers .= 'From: PlatoonLeader_no_reply@platoonleader.net' . "\r\n";
		 
		 add_filter ("wp_mail_content_type", "caldol_mail_send_plaintext");
		
		 $pieSubject = "PIE from PlatoonLeader.net";
		 $userUserName = $current_user->user_login;
		 $userLink = bp_core_get_user_domain( $current_user->ID );
		 $currPostTitle = html_entity_decode($currPost->post_title, ENT_QUOTES );
		 $currPostLink = get_permalink($postID);
		 
			 if(wp_mail($authorEmail,'PIE from PlatoonLeader.net', "$authorFirstName,\r\n\r\nPL member $userUserName ($userLink), found your contribution of \"$currPostTitle\" ($currPostLink) to be Positive, Inspiring, and Energizing.  \r\n\r\nThank you for serving up PIE in the Platoon Leader forum.  Contributors like you make a positive impact on our profession. \r\n\r\n-- The Platoon Leader Forum Team", $headers))
			 
			 	
		   {
		     echo "mail sent";
		   }
		   else
		   {
		     echo "mail failed";
		   }
		   remove_filter("wp_mail_content_type", "caldol_mail_send_plaintext");

		return true;
	}

	else{

		return false;
	}

    die();
}



function hasPied($postID, $userID){



	//var_dump($userID);

	//return false;



	$pie_key = '_post_pie_id';



	$pie_ers = get_post_meta($postID, $pie_key, false);



	if(in_array($userID, $pie_ers)){

		//echo "true";

		return true;

	}

	else{

		//echo "false";

		return false;

	}



}

function getPostPies($postID){

	$current_user = wp_get_current_user();



	$count_key = '_post_pie_count';

	$pie_ers_key = '_post_pie_id';



	$count = get_post_meta($postID, $count_key, true);

	/* if($count==''){

		delete_post_meta($postID, $count_key);

	add_post_meta($postID, $count_key, '0');

	return "0 Likes";

	}

	*/



	if($count==""){

		return "0 PIEs";

	}



	if($count == 1){

		return $count.' PIE ';

	}

	else{

		return $count.' PIEs';

	}

}











function getPostLikes($postID){
	$current_user = wp_get_current_user();
	

	$count_key = '_post_like_count';
	$likers_key = '_post_like_id';

	

	$count = get_post_meta($postID, $count_key, true);

	/* if($count==''){

		delete_post_meta($postID, $count_key);

		add_post_meta($postID, $count_key, '0');

		return "0 Likes";

	}
	 */
	
	if($count==""){
		return "0 Likes";
	}


	if($count == 1){

		return $count.' Like ';

	}

	else{

		return $count.' Likes';

	}

}


function setPostLikes($postID) {
	
	$current_user = wp_get_current_user();

	$count_key = '_post_like_count';
	$likers_key = '_post_like_id';
	
	//var_dump($postID);

	$count = get_post_meta($postID, $count_key, true);


	if(!hasLiked($postID, $current_user->ID)){
	if($count==''){

		$count = 1;

		delete_post_meta($postID, $count_key);
		delete_post_meta($postID, $likers_key);
		

		add_post_meta($postID, $count_key, $count);
		add_post_meta($postID, $likers_key, $current_user->ID, true);

	}	
		else{

		$count++;

		update_post_meta($postID, $count_key, $count);
		
		add_post_meta($postID, $likers_key, ($current_user->ID));


		}
	}
	if($count)
		return true;
	else 
		return false;

}

function hasLiked($postID, $userID){
	
	//var_dump($userID);
	//return false;
	
	$likers_key = '_post_like_id';



	$likers = get_post_meta($postID, $likers_key, false);
	
	if(in_array($userID, $likers)){
		//echo "true";
		return true;
	}
	else{
		//echo "false";
		return false;
	}

}





function getPostViews($postID){
    $count_key = '_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 Views";
    }
    if($count == 1){
    return $count.' View';
    }
    else{
    return $count.' Views';
    }
}
function setPostViews($postID) {
    $count_key = '_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);




function caldol_hook_bbp_get_replies_per_page($default=0){
	
	$retval = 1;
	return $retval;
}

//add_filter( 'bbp_get_replies_per_page', 'caldol_hook_bbp_get_replies_per_page', 10,1);

// THIS FILTERS THE TYPE OF POSTS THAT WILL SHOW UP IN THE 
// "LINK TO CONTENT" AREA WHEN A USER CREATES A LINK
// IN THEIR NEW DISCUSSIONS OR REPLIES


function caldol_hook_wp_link_query_args($query = array()){
	
	$currentArgs = $query['post_type'];
	
	$query['post_type'] = array('forum','post', 'topic');
	$query['orderby'] = 'name';
	$query['order'] = 'ASC';
	return $query;
}

add_filter('wp_link_query_args', 'caldol_hook_wp_link_query_args');


add_filter( 'posts_request' , 'modify_request' );

function modify_request( $query) {

	global $wpdb;

	if(strstr($query,"post_type IN ('forum', 'topic', 'post')")){

		$where = str_replace("ORDER BY {$wpdb->posts}.post_date","ORDER BY {$wpdb->posts}.post_type",$query);

	}

	return $query;

}

function caldol_hook_searchfilter($query) {
	global $current_user;

	if (is_search()) {
		if($current_user->ID == 1){
			$query->query_vars['numberposts'] = '3';
		  echo "<h1 style='background-color: white; color: black;'>" . ($query->query_vars['numberposts']) . "</h1>";
		}

	

	}

	return $query;

}



//add_filter('pre_get_posts','caldol_hook_searchfilter', 10 , 1);





function hookme($defaults){
	
	$defaults['logged_in_as'] = "tom";
	$defaults['title_reply'] = "tom";
	//print_r($defaults);

return $defaults;
}
//add_filter('comment_form_defaults', 'hookme', 1, 1);  



// CHANGE THE SIZE OF THE TAG CLOUD FONT

function caldol_hook_widget_tag_cloud_args($args = array()){


$newArgs = array(
'smallest' => 8,
'largest'  => 15,
'number'   => 20,

);
return array_merge($args, $newArgs);
}

add_filter('widget_tag_cloud_args', 'caldol_hook_widget_tag_cloud_args', 10, 1);
add_filter('caldol_discussion_tags_args', 'caldol_hook_widget_tag_cloud_args', 10, 1);




// Replaces the excerpt "more" text by a link

function new_excerpt_more($more) {

	global $post;

	return '...&nbsp; <a class="moretag" href="'. get_permalink($post->ID) . '"><br/>read&nbsp;more</a>';

}

add_filter('excerpt_more', 'new_excerpt_more');


// HOOK FOR FORUM SUBSCRIPTION

function caldol_hook_bbp_theme_before_forum_sub_forums(){


		

		//echo "count: " . bbp_get_forum_subforum_count() . " --";

		

		

		$subForumList = bbp_forum_get_subforums();

		

		if(sizeof($subForumList) > 1){

			echo "<ul style='margin-left: 20px;'>";

		foreach($subForumList as $currForum){

			//print_r($currForum);

			// No link

			$retval = false;

				

			// Parse the arguments

			$r = bbp_parse_args( $args, array(

					'forum_id'    => $currForum->ID,

					'user_id'     => 0,

					'before'      => '',

					'after'       => '',

					'subscribe'   => __( 'Subscribe',   'bbpress' ),

					'unsubscribe' => __( 'x', 'bbpress' )

			), 'get_forum_subscribe_link' );

				

			

			$isSubscribed = bbp_get_forum_subscription_link( $r);

			

				

			if(strpos($isSubscribed, 'is-subscribed') != 0){

			

			echo "<li class='bbp-topic-title'><a href='" . bbp_get_forum_permalink($currForum->ID) . "'>" . $currForum->post_title . "</a><span class='bbp-topic-action'>&nbsp;&nbsp; " . $isSubscribed . " </span></li>";

			}

			//print_r($currForum);

		}

			echo "</ul>";

		} // end > 1

		

		

}

add_action('bbp_theme_before_forum_sub_forums', 'caldol_hook_bbp_theme_before_forum_sub_forums');






function caldol_print_filters(){



echo '<ul style="color:white;">';

/* Each [tag] */

foreach ( $GLOBALS['wp_filter'] as $tag => $priority_sets ) {

	//echo '<li><strong>HOOK NAME: ' . $tag . '</strong><ul>';



	/* Each [priority] */

	foreach ( $priority_sets as $priority => $idxs ) {

		//echo '<li>PRIORITY: ' . $priority . '    (' . $tag . ')<ul>';



		/* Each [callback] */

		foreach ( $idxs as $idx => $callback ) {

			if ( gettype($callback['function']) == 'object' ) $function = '{ closure }';

			else if ( is_array( $callback['function'] ) ) {

				$function = print_r( $callback['function'][0], true );

				$function .= ':: '.print_r( $callback['function'][1], true );

			}

			else $function = $callback['function'];
			if( strpos($function, 'caldol') === 0  ){

			echo '<li><span style="font-size:24px; color: white;">' . $tag . ' -- Priority: ' . $priority . '<br/>&nbsp;&nbsp;&nbsp;' . $function . '<i>(' . $callback["accepted_args"] . ' arguments)</i></span></li>';

		
			}
		}

		echo '</ul></li>';

	}

	echo '</ul></li>';

}

echo '</ul>';



}







/*
 * 
 add_action('wp_head', 'fbfixhead');
function fbfixhead() { 
    if ( !is_home() ) { // If not the homepage
    
    // If there is a post image...
    if (has_post_thumbnail()) {
    // Set '$featuredimg' variable for the featured image.
    $featuredimg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), "Full");
    $ftf_description = get_the_excerpt($post->ID);
    $ftf_head = '
    <!--/ Facebook Thumb Fixer Open Graph /-->
    <meta property="og:title" content="AA' . wp_kses_data(get_the_title($post->ID)) . '" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="' . get_permalink() . '" /> 
    <meta property="og:description" content="' . wp_kses($ftf_description, array ()) . '" />
    <meta property="og:site_name" content="' . wp_kses_data(get_bloginfo('name')) . '" />
    <meta property="og:image" content="AA' . $featuredimg[0] . '" />
    ';
    } else { //...otherwise, if there is no post image.
    $ftf_description = get_the_excerpt($post->ID);
    $ftf_head = '
    <!--/ Facebook Thumb Fixer Open Graph /-->
    <meta property="og:title" content="BB' . wp_kses_data(get_the_title($post->ID)) . '" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="' . get_permalink() . '" />
    <meta property="og:description" content="' . wp_kses($ftf_description, array ()) . '" />
    <meta property="og:site_name" content="' . wp_kses_data(get_bloginfo('name')) . '" />
    <meta property="og:image" content="BB' . get_option('default_fb_thumb') . '" />
    ';
    }
    } else { //...otherwise, it must be the homepage so do this:
    $ftf_name = get_bloginfo('name');
    $ftf_description = get_bloginfo('description');
    $ftf_head = '
    <!--/ Facebook Thumb Fixer Open Graph /-->
    <meta property="og:title" content="' . wp_kses($ftf_name, array ()) . '" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="' . get_option('home') . '" />
    <meta property="og:description" content="' . wp_kses_data($ftf_description, array ()) . '" />
    <meta property="og:site_name" content="' . wp_kses($ftf_name, array ()) . '" />
    <meta property="og:image" content="' . get_option('default_fb_thumb') . '" />
    ';
}
  echo $ftf_head;
  print "\n";
}

*/

add_filter('themify_loop_date', 'caldol_loop_date');

function caldol_loop_date($format){
	
	return "d M y";
}


add_action( 'admin_init', 'caldol_redirect_non_admin_users' );

/**

 * Redirect non-admin users to home page

 *

 * This function is attached to the 'admin_init' action hook.

 */

function caldol_redirect_non_admin_users() {

	//if ( ! current_user_can( 'manage_options' ) && '/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF'] ) {
	if ( ! current_user_can( 'manage_options' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		wp_redirect( home_url() );

		exit;

	}

}

function caldol_get_the_topic_list( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {

	$terms = get_the_terms( $id, $taxonomy );



	if ( is_wp_error( $terms ) )

		return $terms;



	if ( empty( $terms ) )

		return false;



	foreach ( $terms as $term ) {
		if($term->parent == 17){

		$link = get_term_link( $term, $taxonomy );

		if ( is_wp_error( $link ) )

			return $link;

		$term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
		
		}


	}



	$term_links = apply_filters( "term_links-$taxonomy", $term_links );



	return $before . join( $sep, $term_links ) . $after;

}

function caldol_get_the_type_list( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {

	$terms = get_the_terms( $id, $taxonomy );



	if ( is_wp_error( $terms ) )

		return $terms;



	if ( empty( $terms ) )

		return false;



	foreach ( $terms as $term ) {

		if($term->parent == 41){

			$link = get_term_link( $term, $taxonomy );

			if ( is_wp_error( $link ) )

				return $link;

			$term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';



		}



	}



	$term_links = apply_filters( "term_links-$taxonomy", $term_links );



	return $before . join( $sep, $term_links ) . $after;

}







/**

 * Tests if any of a post's assigned categories are descendants of target categories

*

* @param int|array $cats The target categories. Integer ID or array of integer IDs

* @param int|object $_post The post. Omit to test the current post in the Loop or main query

* @return bool True if at least 1 of the post's categories is a descendant of any of the target categories

* @see get_term_by() You can get a category by name or slug, then pass ID to this function

* @uses get_term_children() Passes $cats

* @uses in_category() Passes $_post (can be empty)

* @version 2.7

* @link http://codex.wordpress.org/Function_Reference/in_category#Testing_if_a_post_is_in_a_descendant_category

*/

if ( ! function_exists( 'post_is_in_descendant_category' ) ) {

	function post_is_in_descendant_category( $cats, $_post = null ) {

		foreach ( (array) $cats as $cat ) {

			// get_term_children() accepts integer ID only

			$descendants = get_term_children( (int) $cat, 'category' );

			if ( $descendants && in_category( $descendants, $_post ) )

				return true;

		}

		return false;

	}

}





// FUNCTION TO GET ATTACHMENTS OF A PARTICULAR ITEM


function caldol_can_edit_file($authorID){
	global $current_user;

	
	//echo $current_user->ID. ", " . $post->post_author;
	if($current_user->ID == $authorID || current_user_can('edit_others_posts')){
		return true;
	}
	return false;
}


/**

 * Returns the translated role of the current user. If that user has

 * no role for the current blog, it returns false.

 *

 * @return string The name of the current role

 **/

function get_current_user_role() {

	global $wp_roles;

	$current_user = wp_get_current_user();

	$roles = $current_user->roles;

	$role = array_shift($roles);

	return isset($wp_roles->role_names[$role]) ? translate_user_role($wp_roles->role_names[$role] ) : false;

}

function caldol_attachments_count($postID){
$args = array(

		'post_type' => 'attachment',

		'numberposts' => null,

		'post_status' => null,

		'post_parent' => $postID

);

$attachments = get_posts( $args );

	

// make sure the attachment(s) are image(s). otherwise, ignore them


return count($attachments);
}






function caldol_get_attachments_link_list( $postID, $withRemove=false ) {

	$args = array('post_parent' => $postID,

			'post_type' => 'attachment',

	);



	$attachments = get_children( $args );



	if ( $attachments ) {

		foreach ( $attachments as $attachment ) {
			$currentLink = wp_attachment_is_image($attachment->ID)?"<a href='".wp_get_attachment_url($attachment->ID)."'>" . wp_get_attachment_link($attachment->ID, array('auto',50)) ."</a>": wp_get_attachment_link($attachment->ID);
			
			
			if($withRemove){
			$removeLink = "&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' name='removeAttachment[]' value='". $attachment->ID . "'>Remove</input>";
			}
			else{
				$removeLink = '';
			}
			$attachmentListing .= "<li>" .$currentLink. "" . $removeLink . "</li>";
			

			//$image_attributes = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' )  ? wp_get_attachment_image_src( $attachment->ID, 'thumbnail' ) : wp_get_attachment_image_src( $attachment->ID, 'full' );



			//echo '<img src="' . wp_get_attachment_thumb_url( $attachment->ID ) . '" class="current">';

		}
	return $attachmentListing;

	}
	else{
	return "No attachments found";
	}

}

function caldol_remove_attachments($attachmentList){
	
		$removalError = false;
		
		
	
	foreach($attachmentList as $currentVictim){
		
		//echo "attachment id is: " . $attachmentID . ", end id";

		if(wp_delete_attachment($currentVictim)){
			$removeAttachmentErrorList .= $currentVictim;
			$removalError = true;
		}
		else{
			$removalError = false;
		}
	}
	
	return $removalError;

	
}




// THIS FUNCTION CHECKS TO SEE IF THE USER IS LOGGED IN

// BEFORE PRESENTING CONTENT.  IF THE USER ISN'T LOGGED IN

// THEY WILL BE TAKEN TO THE LOGIN PAGE AND THEN IF THEY

// LOG IN CORRECTLY, THEY WILL BE TAKEN TO THE ORIGINAL

// PAGE THEY WERE TRYING TO ACCESS.

// THIS ALSO EXEMPTS THE FRONT PAGE, REGISTER PAGE, AND THE ABOUT US PAGE





function caldol_protect_content() {
	
	global $post;

	$slug = get_post( $post )->post_name;

	

	if( !is_user_logged_in() &&
            ( ! is_front_page() && 
                $slug != 'register' && 
                $slug != 'about-pl' && 
                $slug != 'contact-us' &&
                $slug != 'cclpd'  &&
		$slug != 'ccdp-registration-form'
			    //&& !(substr( $slug, 0, 5 ) === "cclpd")
	    ) ){

	//if ( !is_user_logged_in() ) {

		auth_redirect();

	}

}



add_action ('template_redirect', 'caldol_protect_content');


// THIS FUNCTION WILL ALLOW YOU TO PUT THE MEDIA-QUERIES.CSS
// IN THE CHILD THEME




function custom_theme_enqueue_scripts() {
	
	wp_enqueue_style( 'themify-media-queries', THEME_URI . '/media-queries.css');

	

	wp_dequeue_style( 'themify-media-queries' );

	wp_enqueue_style( 'themify-media-queries', get_stylesheet_directory_uri() . '/media-queries.css', array());

}

add_action( 'wp_enqueue_scripts', 'custom_theme_enqueue_scripts');



//add_action('init', 'myStartSession', 1);

//add_action('wp_logout', 'myEndSession');

//add_action('wp_login', 'myEndSession');



function myStartSession() {

	if(!session_id() && is_user_logged_in() ) {

		session_start();
	}
	
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {

		// last request was more than 30 minutes ago
		$siteURL = site_url();

		session_unset();     // unset $_SESSION variable for the run-time

		session_destroy();   // destroy session data in storage
		wp_logout();
	
		wp_redirect( "/wp-login.php?sessexp=1" ); 
		exit;

	}
	else{

	$_SESSION['LAST_ACTIVITY'] = time();
	}

}



function myEndSession() {

	session_destroy ();

}

add_filter( 'query_vars', 'caldol_addnew_query_vars', 10, 1 );
function caldol_addnew_query_vars($vars)
{   
    $vars[] = 'sessexp'; // var1 is the name of variable you want to add       
    return $vars;
}

add_filter('login_message', 'caldol_ShowSessionExpirationMessage', 10, 1);

function caldol_ShowSessionExpirationMessage($message=''){
	
	if($_GET['sessexp'] == 1){
	$message = "<div id='sessionExpirationMessage'>For security reasons, your session has expired.  Please log in again to continue.</div>" . $message;
	}
	echo $message;

	
	
}

add_filter('login_message', 'caldol_auth_redirect_message', 10, 1);



function caldol_auth_redirect_message($message=''){

	global $redirect_to;
	$siteURL = site_url();


	if (isset($_GET['redirect_to'])) {

		$message = "<div id='sessionExpirationMessage'>You must be logged in to view the content you are trying to access.  Please log in below and you will be 
		redirected to the content you requested.<br/>If you are not registered on the PL Forum, you may do so by <a href='$siteURL/wp-login.php?action=register'>clicking here</a></div>" . $message;

	}

	echo $message;





}


// ! // Add Recent Topics to BBPress

function caldol_most_replies_bbpress_topics_shortcode() {

 ?>

<!-- html custom-functions -->

<h4 style="margin-top: 15px;">Discussions with the Most Replies</h4>

<?php

if ( bbp_has_topics( array( 'author' => 0, 'show_stickies' => false, 'order' => 'DESC', 'meta_key' => '_bbp_reply_count', 'orderby' => 'meta_value',  'post_parent' => 'any', 'posts_per_page' => 10 ) ) )

bbp_get_template_part( 'bbpress/loop', 'topics' );

?>

<!-- end -->

<?php }

// Hook into action

add_shortcode( 'most-replies', 'caldol_most_replies_bbpress_topics_shortcode');




// ! // Add No-replies to BBPress

function caldol_no_replies_bbpress_topics_shortcode() {

	?>

<!-- html custom-functions -->

<h4 style="margin-top: 15px;">Discussions with No Replies.  Be the first to jump in!</h4>

<?php



if ( bbp_has_topics( array( 'author' => 0, 'show_stickies' => false, 'order' => 'DESC', 'meta_key' => '_bbp_reply_count', 'orderby' => 'post_date', 'meta_value' => '0',  'meta_compare' => '=', 'post_parent' => 'any', 'posts_per_page' => 10 ) ) )

bbp_get_template_part( 'bbpress/loop', 'topics' );

?>

<!-- end -->

<?php }

// Hook into action

add_shortcode( 'no-replies', 'caldol_no_replies_bbpress_topics_shortcode');





// SET A NEW QUERY PARAMETER TO FILTER BY A CATEGORY
// THIS WAS SETUP BEFORE I REALIZED CATEGORY LISTINGS
//  WERE BUILT IN
// USE:  http://caldol.omjcreative.com/files/?targetCat=19
function add_query_vars_filter( $vars ){

	$vars[] = "targetCat";

	return $vars;

}

//add_filter( 'query_vars', 'add_query_vars_filter' );


function ajax_check_user_logged_in() {

	echo is_user_logged_in()?'yes':'no';

	die();

}

add_action('wp_ajax_is_user_logged_in', 'ajax_check_user_logged_in');

add_action('wp_ajax_nopriv_is_user_logged_in', 'ajax_check_user_logged_in');


// SHOW AN ALTERNATE PAGE FOR NON-LOGGED IN USERS BY

// REDIRECTING THEM


/*

function loggedIn_shortcode($atts, $content=null) {
	extract(shortcode_atts(array(
			'status' => "dog"
	), $atts));
	 if ( ( $status == 'true' && is_user_logged_in() ))
		return $content;
	else if( $status == 'false' && !is_user_logged_in() )
		return $content;
	}
add_shortcode( 'loggedIn', 'loggedIn_shortcode');

*/

//allow shortcodes in widgets

add_filter('widget_text', 'do_shortcode');


function loggedIn_shortcode($atts, $content=null) {

	$args = shortcode_atts(array(

			'status' => "true"

	), $atts);

	if ( ( $args['status'] == 'false' && !is_user_logged_in() ))

		return do_shortcode($content);

	else if( $args['status']  == 'true' && is_user_logged_in() )

		return do_shortcode($content);

}

add_shortcode( 'loggedIn', 'loggedIn_shortcode');



//add_shortcode( 'visitor', 'visitor_check_shortcode' );

function visitor_check_shortcode( $atts, $content = null ) {
	 if ( ( !is_user_logged_in() && !is_null( $content ) ) || is_feed() )
		return $content;
	return '';
}



/*
 * 
 add_action( 'init', 'caldol_redirect_visitors' );



function caldol_redirect_visitors() {

	if ( (!is_user_logged_in() ) && is_front_page()) {

		wp_redirect( 'http://caldol.omjcreative.com/notloggedin/');

		exit;

	}

}

*/

/* THESE ARE PRODUCTION-READY FUNCTIONS */

/* Hook to the 'all' action */
//add_action( 'wp_footer', 'backtrace_filters_and_actions');
//add_action( 'all', 'backtrace_filters_and_actions');
function backtrace_filters_and_actions() {
	/* The arguments are not truncated, so we get everything */
	$arguments = func_get_args();
	$tag = array_shift( $arguments ); /* Shift the tag */

	/* Get the hook type by backtracing */
	$backtrace = debug_backtrace();
	$hook_type = $backtrace[3]['function'];

	echo "<h4><pre style='color: red; font-weight: bold;'>";
	echo "<i>$hook_type</i> <b>$tag</b>\n";
	foreach ( $arguments as $argument )
		echo "\t\t" . htmlentities(var_export( $argument, true )) . "\n";

		echo "\n";
		echo "</pre></h4>";
		//return "doggie";
}


// REVERSE THE ORDER IN WHICH REPLIES APPEAR SO THE LATEST ONE SHOWS FIRST

//add_fiLter('bbp_has_replies_query','caldol_reverse_reply_order');

function caldol_reverse_reply_order( $query = array() ) {
	$query['order']='DESC';
	return $query;
}




// MAKE THE MAIN DISCUSSION TEXT SHOW AT THE TOP OF THE PAGE
//  BEFORE THE REPLIES TO THAT DISCUSSION ARE SHOWN
//add_filter( 'bbp_show_lead_topic', '__return_true' );
add_filter( 'bbp_show_lead_topic', 'caldol_show_lead_topic' );


function caldol_show_lead_topic(){
	return true;
}



// ADD OUR CUSTOM JS FUNCTIONS IN THE FILE caldol-js-functions.js FILE
// AND INCLUDE THE JS FILE IN THE HEADER
// THE FUNCTION get_stylesheet_directory_uri() WILL RETRIEVE THE DIRECTORY
// FOR THE CHILD THEME.

add_action('wp_enqueue_scripts', 'caldol_add_js_functions');

function caldol_add_js_functions(){
	
	wp_enqueue_script('caldol-js-functions', get_stylesheet_directory_uri() . '/js/caldol-js-functions.js', array('jquery'), '1.0', false); 
}

// ADD TEXT TO THE REGISTRATION PAGE TO INDICATE WHAT TO DO ON 
// THE REGISTRATION PAGE
add_action('bp_before_account_details_fields', 'caldol_registration_intro_message');

function caldol_registration_intro_message(){
	echo '<p>Once you complete the fields below and submit the registration request, it will be reviewed within 24-48 hours.  You will be notified via email when your
	request has been approved or disapproved.</p>';
}





//bypass the resetting of the password when approved
//this allows the user to use the password they created when they registered
//this is located in  new-user-approve.php

function caldol_bypass_password_reset(){
	return true;
}



add_filter( 'new_user_approve_bypass_password_reset', 'caldol_bypass_password_reset' );




// THE PAGE DEFAULT IS TO HAVE THE SIDE WIDGETS SHOW BUT
// WE DON'T WANT THOSE WIDGETS TO APPEAR ON THE FRONT PAGE
//add_filter( 'sidebars_widgets', 'caldol_disable_all_widgets' );

function caldol_disable_all_widgets( $sidebars_widgets ) {
	if ( is_home() || is_front_page() )
		$sidebars_widgets = array( false );
	return $sidebars_widgets;
}


// ADD A SIDEBAR TO THE LEFT


//add_action( 'widgets_init', 'caldol_register_nav_sidebar' );



function caldol_register_nav_sidebar(){

	register_sidebar(array(

			'name' => 'CALDOL Nav Sidebar',

			'id' => 'caldol-nav-sidebar',

			'before_widget' => '<div id="%1$s" class="widget %2$s">',

			'after_widget' => '</div>',

			'before_title' => '<h4 class="widgettitle">',

			'after_title' => '</h4>',

	));

}










/*
// USE THIS TO CHANGE THE TITLE OF A PARTICULAR FORUM
// THAT APPEARS ON THE PAGE OR ADD TEXT TO A FORUM TITEL
add_filter( 'bbp_get_forum_title', 'caldol_change_forum_title', 10, 2);
*/
function caldol_change_forum_title($title, $forum_id=''){
	
	// ID 56 IS Additional Duties
	if($forum_id == 56)
	$title = "doggie doo doo";
	
	return $title;
}



/*
//ADD ITEMS TO THE DROPDOWN ON THE MEMBERS PAGE (buddypress/members/index.php)
//THIS COULD BE USED TO PROVIDE A NEW FILTER FOR THE VIEW
add_action( 'bp_members_directory_order_options', 'caldol_add_options_members_filter'); 
*/
function caldol_add_options_members_filter(){
	echo '<option value="TomChoice">doggie</option>';
		
}



/*
 * 
// THIS WILL CHANGE THE title="" ATTRIBUTE OF THE LINK
// FOR THE HEADER ON THE wp-login.php PAGE

add_filter('login_headertitle', 'caldol_login_title_on_hover');
 */
 function caldol_login_title_on_hover($login_header_title){
	$login_header_title = "XXXXXXXXXXXXXXXXXXXXXXX";
	return $login_header_title;
}


/*
// THIS PUTS A MESSAGE ON A MEMBER'S PROFILE RIGHT ABOVE THE MENU
// FOR ACTIVITY, PROFILE, MESSAGES, ETC. (buddypress/members/single/member-header.php)
add_action( 'bp_before_member_header_meta', 'caldol_before_member_header_meta' );
*/
function caldol_before_member_header_meta(){
	echo "<h1>got here with myBogusFunction</h1>";
}


/*
 * THIS WILL ADD TEXT/HTML TO THE BOTTOM OF EACH PIECE OF CONTENT
 * WHEN IT IS DISPLAYED.
 */
//add_filter( "the_content", "caldol_add_to_content_end" );
function caldol_add_to_content_end($content){
	$content .= '<p>Leadership Counts!</p>';
	return $content;
}


// REMOVE THEME BUILDER ADMIN BAR OPTION FROM
// NON-ADMINS

add_action( 'admin_bar_menu', 'caldol_remove_non_admin_theme_builder_menu', 11 );

function caldol_remove_non_admin_theme_builder_menu(){

	global $wp_admin_bar;
	if (!current_user_can('administrator')){



	$wp_admin_bar->remove_node('wp-admin-bar-themify_builder');



	}

}



// remove the buddypress admin option from the admin menu

add_action( 'admin_bar_menu', 'wp_admin_bar_my_custom_account_menu', 11 );

function admin_bar_remove_this(){

	global $wp_admin_bar;

	$wp_admin_bar->remove_node('bp_adminbar_account_menu');



}


/*
 * 
 * CHANGE THE TITLE THAT IS PRINTED ON THE PAGE
 * IF A USER IS NOT LOGGED IN
 * HOWEVER, WE WANT THE REGISTER PAGE AND THE ABOUT PL PAGE
 * TO SHOW FOR ALL USERS
 */
add_filter( 'the_title', 'caldol_not_logged_in_modified_post_title', 10 ,2);
function caldol_not_logged_in_modified_post_title ($title, $content) {
	global $bp;
	global $post;

	$slug = get_post( $post )->post_name;

	if ( (in_the_loop()) && ( !is_user_logged_in()  )  ) {

		if($bp->current_component != 'register' &&  
		    $slug !='about-pl' && 
		    $slug !='contact-us' &&
		    $slug !='cclpd' &&
		$slug != 'ccdp-registration-form'
		   // && !(substr( $slug, 0, 5 ) === "cclpd")
		  ){
		$title = '<h1><span style="color:red;">Misfire! Misfire!</span><br/>You must be registered and logged in as a member in order to access this page.  <br/>
		<span style="font-size:.8em;">You may register/login by clicking the register or log in link at the top-left of the page.</span></h1>';//$title." (modified title)";
	
		}
	}
	return $title;
}


/*
 * CHANGE THE BROWSER TITLE (NOT THE ONE THAT IS PRINTED ON THE PAGE)
 */
   add_filter( 'wp_title', 'caldol_change_browser_title', 10, 3 );
    function caldol_change_browser_title( $title, $sep, $seplocation )
    {
    global $page, $paged;
    // Don't affect in feeds.
    if ( is_feed() )
    return $title;
    // Add the blog name
    if ( 'right' == $seplocation )
    $title .= get_bloginfo( 'name' );
    else
    $title = get_bloginfo( 'name' ) . " - " . $title;
    // Add the blog description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
    $title .= " {$sep} {$site_description}";
    // Add a page number if necessary:
    if ( $paged >= 2 || $page >= 2 )
    $title .= " {$sep} " . sprintf( __( 'Page %s', 'dbt' ), max( $paged, $page ) );
    return $title;
    }



/*
 * THIS ENABLES THE TINYMCE VISUAL EDITOR 
 */
//add_filter('wp_default_editor', create_function('', 'return "html"'));


function caldol_bbp_enable_visual_editor( $args = array() ) {
	 $args['tinymce'] = true;
	  $args['html'] = false;
	//$args['ckeditor'] = true;
	return $args;
}
add_filter( 'bbp_after_get_the_content_parse_args', 'caldol_bbp_enable_visual_editor' );




//test to get better editor
function caldol_bbp_enable_visual_editor_two( $args = array() ) {


    $args['tinymce'] = true;
	$args['media_buttons'] = true;
	$args['textarea_rows'] = true;
	$args['dfw'] = false;
	$args['tinymce'] = array( 'theme_advanced_buttons1' =>'bold,italic,underline,strikethrough,bullist,numlist,code,blockquote,link,unlink,outdent,indent,|,undo,redo,fullscreen',
'theme_advanced_buttons2' => '', // 2nd row, if needed
        	'theme_advanced_buttons3' => '', // 3rd row, if needed
        	'theme_advanced_buttons4' => '', ); // 4th row, if needed	
	$args['quicktags'] = array ('buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,close');
    return $args;	
}



//add_filter( 'bbp_after_get_the_content_parse_args', 'caldol_bbp_enable_visual_editor_two' );





/**

 * Modify/change the default allowed tags for bbPress.

*

* The default list (below) is in bbpress/includes/common/formatting.php, L24-66.

* Adjust below as needed. This should go in your theme's functions.php file (or equivilant).

*/

function ja_filter_bbpress_allowed_tags() {

	return array(



	// Links

			'a' => array(

					'href' => array(),

					'title' => array(),

					'rel' => array()

			),



			// Quotes

			'blockquote' => array(

					'cite' => array()

			),



			// Code

			'code' => array(),

			'pre' => array(),



			// Formatting

			'em' => array(),

			'strong' => array(),

			'del' => array(

					'datetime' => true,

			),



			// Lists

			'ul' => array(),

			'ol' => array(

					'start' => true,

			),

			'li' => array(),



			// Images

			'img' => array(

					'src' => true,

					'border' => true,

					'alt' => true,

					'height' => true,

					'width' => true,

			)

	);

}

//add_filter( 'bbp_kses_allowed_tags', 'ja_filter_bbpress_allowed_tags' );




/*
 * 
 THIS REMOVES THE AVATAR FROM IN FRONT OF THE USER NAME ON THE FORUM/TOPIC/REPLY LISTINGS
 */
function caldol_bbp_get_topic_author_avatar($author_avatar, $topic_id, $size ) {
	global $bp;
	$author_avatar = '';
	$size = 30;
	$topic_id = bbp_get_topic_id( $topic_id );
	if ( !empty( $topic_id ) ) {
		if ( !bbp_is_topic_anonymous( $topic_id ) ) {
			$author_avatar = bp_core_fetch_avatar( 'item_id=' . bbp_get_topic_author_id( $topic_id ) ); //bbp_get_topic_author_avatar( bbp_get_topic_author_id( $topic_id ), $size );
		} else {
			$author_avatar = bp_core_fetch_avatar(bbp_get_topic_author_id( $topic_id ) );//bbp_get_topic_author_avatar( bbp_get_topic_author_id( $topic_id ), $size );
			//$author_avatar = get_avatar( get_post_meta( $topic_id, '_bbp_anonymous_email', true ), $size );
		}
	}
	return $author_avatar;

}
add_filter( 'bbp_get_topic_author_avatar', 'caldol_bbp_get_topic_author_avatar', 10, 3);
//add_filter( 'bbp_get_reply_author_avatar', 'caldol_bbp_get_topic_author_avatar', 10, 3);

/*

 *

THIS REMOVES THE AVATAR FROM IN FRONT OF THE USER NAME ON THE FORUM/TOPIC/REPLY LISTINGS

*/

function caldol_bbp_get_reply_author_avatar($reply_id = 0, $size=40 ) {

	global $bp;


		$reply_id = bbp_get_reply_id( $reply_id );
		if ( !empty( $reply_id ) ) {
			// Check for anonymous user
			if ( !bbp_is_reply_anonymous( $reply_id ) ) {
				$author_avatar = bp_core_fetch_avatar( 'item_id=' . bbp_get_reply_author_id( $reply_id ) );
				//$author_avatar = get_avatar( bbp_get_reply_author_id( $reply_id ), $size );
			} else {
				$author_avatar = bp_core_fetch_avatar( 'item_id=' . bbp_get_reply_author_id( $reply_id ) );
				//$author_avatar = get_avatar( get_post_meta( $reply_id, '_bbp_anonymous_email', true ), $size );
			}
		} else {
			$author_avatar = '';
		}

		return $author_avatar;
	}



add_filter( 'bbp_get_reply_author_avatar', 'caldol_bbp_get_reply_author_avatar', 10, 3);






/*
 * 
 function admin_bar_remove_this(){
	global $wp_admin_bar;
	$wp_admin_bar->remove_node(�my-account-forums-favorites�);
}
add_action(�wp_before_admin_bar_render�,'admin_bar_remove_this�);
*/

//add_filter( 'wp_before_admin_bar_render','caldol_wp_admin_bar_my_custom_account_menu' );

/*
 * CHANGE THE "HOWDY" VERBIAGE ON THE ADMIN BAR
 * AT THE TOP OF THE SCREEN
 */
add_action( 'admin_bar_menu', 'caldol_wp_admin_bar_my_custom_account_menu', 10, 1 );
function caldol_wp_admin_bar_my_custom_account_menu( $wp_admin_bar ) {
	global $bp;
	$user_id = get_current_user_id();
	$current_user = wp_get_current_user();
	$profile_url = get_edit_profile_url( $user_id );
	$bp_user = new BP_Core_User( $user_id );
	if ( 0 != $user_id ) {
		/* Add the "My Account" menu */
		$avatar = $bp_user->avatar;//  get_avatar( $user_id, 28 );
		//$avatar = get_avatar( $user_id, 28 );
		$howdy = sprintf( __('Welcome, %1$s'), $current_user->display_name );
		$class = empty( $avatar ) ? '' : 'with-avatar';

		$wp_admin_bar->add_menu( array(
				'id' => 'my-account',
				'parent' => 'top-secondary',
				'title' => $howdy . $avatar,
				'href' => $profile_url,
				'meta' => array(
						'class' => $class,
				),
		) );

	}
}



// allow xprofile fields to be shown on user admin edit
// this allows admins to review applications for membership and see
// all the fields.
// The $targetFields array contains the names of the fields to be shown
// The $textFieldTargetFields are those fields that should appear
// on the profile page as text fields so that all of the text
// can be read


add_action( 'show_user_profile', 'show_my_extra_profile_fields' );
add_action( 'edit_user_profile', 'show_my_extra_profile_fields' );
function show_my_extra_profile_fields( $user ) { ?>
    <h3>Member Dogtag Information (read-only)</h3>
    <table class="form-table">
                
    <?php 

   $targetFields = array(
   		"Branch",
   		"Source of Commissioning",
   		"Year Group",
   		"Component",
   		"Current Status",
   		"Type of Unit",
   		"Units I Lead",
   		"Current Post",
   		"Reason for Joining the PL Forum",
   		"Military Experiences",
   		"Life Experiences",
   		"AKO/Enterprise Mail Email",
   		"Facebook",
   		"Twitter",
   		"Google+"
   );
   
   $textFieldTargetFields = array(
   		"Reason for Joining the PL Forum",
   		"Military Experiences",
   		"Life Experiences"
   		);
    
    foreach($targetFields as $currentTargetField){// => $targetFieldName){ 
    //$targetFieldName = str_replace(' ', '_', $currentTargetField);?>
    <tr>
            <th><label><?php echo $currentTargetField; ?></label></th>
            <td>
                <?php 
                
                if( function_exists( 'xprofile_get_field_data' ) ) {
                    $xprofile_value = xprofile_get_field_data($currentTargetField, $user->ID );
                }
                else {
                    $xprofile_value = 'NOT HERE';
                }
                
                if(!in_array($currentTargetField, $textFieldTargetFields) ){
                ?>
                
                <input type="text" name="<?php echo str_replace(' ', '_', $currentTargetField); ?>" id="<?php echo str_replace(' ', '_', $currentTargetField); ?>" value="<?php echo esc_attr( $xprofile_value ); ?>" class="regular-text" readonly />
                <?php }
                else {?>
                <textarea rows="5" cols="50" name="<?php echo str_replace(' ', '_', $currentTargetField); ?>" id="<?php echo str_replace(' ', '_', $currentTargetField); ?>" class="regular-text" readonly><?php echo esc_attr( $xprofile_value ); ?></textarea>
                <?php }?>
            </td>
        </tr>
		<?php }//end foreach?>
    </table>
<?php 
}
?>
<?php 

// THIS FUNCTION REMOVES THE <p> TAGS AND THE href="mailto:" FROM THE FIELDS THAT ARE
// OF TYPE email ON THE XPROFILE AREA OF THE ADMIN CONSOLE WHEN YOU VIEW A USER
function caldol_bxcft_show_field_value($value_to_return, $type, $id, $value){

    if ($type == 'email') {
        return $value;
    }
  return $value;	
}
add_filter('bxcft_show_field_value', 'caldol_bxcft_show_field_value', 10, 4);
?>
<?php 
//ADD LOGIN/LOGOUT ONTO THE MENU
//THE FUNCTION wp_loginout(redirectPage, echoTrueorFalse) if echoed returns a link that just says logout

/* add_filter('wp_nav_menu_items', 'add_login_logout_link', 10, 2);
function add_login_logout_link($items, $args) {
        ob_start();
        wp_loginout('index.php');
        $loginoutlink = ob_get_contents();
        ob_end_clean();
        $items .= '<li>'. $loginoutlink .'</li>';
    return $items;
}

 */
//add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		/* Disable the Admin Bar. */
add_filter( 'show_admin_bar', '__return_false' );
/* Remove the Admin Bar preference in user profile */
remove_action( 'personal_options', '_admin_bar_preferences' );

	}
}

//THIS FUNCTION WILL ADD STUFF TO TOPIC LISTING BEFORE THE REPLY LIST IS SHOWN

function custom_bbp_template_before_topics_loop(){
	
	//echo '<h1>hello</h1>';
	//wp_loginout('index.php', true);
	
}

//add_action('bbp_template_before_topics_loop', 'custom_bbp_template_before_topics_loop');

// ADJUST THE HEADER ON THE TOP-LEVEL OF FORUMS AND TOPICS

function remove_category_header_bbp_get_single_forum_description($retstr, $r, $args = ''){
	
	// Unhook the 'view all' query var adder
	remove_filter( 'bbp_get_forum_permalink', 'bbp_add_view_all' );
	
	// Get some forum data
	$tc_int      = bbp_get_forum_topic_count( $forum_id, false );
	$rc_int      = bbp_get_forum_reply_count( $forum_id, false );
	$topic_count = bbp_get_forum_topic_count( $forum_id );
	$reply_count = bbp_get_forum_reply_count( $forum_id );
	$subforum_count = bbp_get_forum_subforum_count($forum_id);
	$total_discussions_count = bbp_get_forum_topic_count($forum_id, true, true);
	$last_active = bbp_get_forum_last_active_id( $forum_id );
	
	// Has replies
	if ( !empty( $reply_count ) ) {
		$reply_text = sprintf( _n( '%s reply', '%s replies', $rc_int, 'bbpress' ), $reply_count );
	}
	
	// Forum has active data
	if ( !empty( $last_active ) ) {
		$topic_text      = bbp_get_forum_topics_link( $forum_id );
		$time_since      = bbp_get_forum_freshness_link( $forum_id );
		$last_updated_by = bbp_get_author_link( array( 'post_id' => $last_active, 'size' => $r['size'] ) );
	
		// Forum has no last active data
	} else {
		$topic_text      = sprintf( _n( '%s topic', '%s topics', $tc_int, 'bbpress' ), $topic_count );
	}
	
	// Forum has active data
	if ( !empty( $last_active ) ) {
	
		if ( !empty( $reply_count ) ) {
	
			if ( bbp_is_forum_category( $forum_id ) ) {
				$retstr = sprintf('This forum contains %1$s topics and a total of %2$s discussions', $subforum_count, $total_discussions_count );
			} else {
				$retstr = sprintf( esc_html__( 'This forum contains %1$s and %2$s.',    'bbpress' ), $topic_text, $reply_text );
			}
	
		} else {
	
			if ( bbp_is_forum_category( $forum_id ) ) {
				$retstr = sprintf( esc_html__( 'This category contains %1$s.', 'bbpress' ), $topic_text);
			} else {
				$retstr = sprintf( esc_html__( 'This forum contains %1$s.',    'bbpress' ), $topic_text );
			}
		}
	
		// Forum has no last active data
	} else {
	
		if ( !empty( $reply_count ) ) {
	
			if ( bbp_is_forum_category( $forum_id ) ) {
				$retstr = sprintf( esc_html__( 'This category contains %1$s and %2$s.', 'bbpress' ), $topic_text, $reply_text );
			} else {
				$retstr = sprintf( esc_html__( 'This forum contains %1$s and %2$s.',    'bbpress' ), $topic_text, $reply_text );
			}
	
		} else {
	
			if ( !empty( $topic_count ) ) {
	
				if ( bbp_is_forum_category( $forum_id ) ) {
					$retstr = sprintf( esc_html__( 'This category contains %1$s.', 'bbpress' ), $topic_text );
				} else {
					$retstr = sprintf( esc_html__( 'This forum contains %1$s.',    'bbpress' ), $topic_text );
				}
	
			} else {
				$retstr = esc_html__( 'This topic has no discussions.  Be the first!.', 'bbpress' );
			}
		}
	}
	
	// Add feeds
	//$feed_links = ( !empty( $r['feed'] ) ) ? bbp_get_forum_topics_feed_link ( $forum_id ) . bbp_get_forum_replies_feed_link( $forum_id ) : '';
	
	// Add the 'view all' filter back
	add_filter( 'bbp_get_forum_permalink', 'bbp_add_view_all' );
	
	// Combine the elements together
	echo $retstr = $r['before'] . $retstr . $r['after'];
	

/*
 * 
 * 
 	// Parse arguments against default values
	$r = bbp_parse_args( $args, array(
			'forum_id'  => 0,
			'before'    => '<div class="bbp-template-notice info"><p class="bbp-forum-description">',
			'after'     => '</p></div>',
			'size'      => 14,
			'feed'      => true
	), 'bbp_get_single_forum_description' );
	
	
	
	// Validate forum_id
	$forum_id = bbp_get_forum_id( $r['forum_id'] );
	
	
	if ( bbp_is_forum_category( $forum_id ) ) {
		
		$retstr = "Here are the Topics within " . the_title(null, null, false);
		//$r['before'] = '<div style="display:none;" class="bbp-template-notice info"><h1>hi</h1><p class="bbp-forum-description">';
	echo	$retstr = $r['before'] . $retstr . $r['after'];
	} 
	else{
		//Since I only change the $retstr if the forum is a "category", the text doesn't change for the other forums (Topics in our terms)
		//so just echo it unchanged.
		
	echo	$retstr;
	}
	*/	
}
add_filter('bbp_get_single_forum_description', 'remove_category_header_bbp_get_single_forum_description', 10, 2);


function caldol_modify_author_display_name($author_name, $reply_id){


//$author_name = "by " . $author_name . "";
	$author_name = $author_name . "";
return $author_name;

}

//add_filter( 'bbp_get_topic_author_display_name', 'caldol_modify_author_display_name', 10, 2 );


//putting parameters in the function call means that those values will be passed into my function
//when the filter is applied.  However, the add_filter method must have the number of parameters to be passed set in the call.
//In addition, the parameters in my function call must be in the same order as the original filter
//  add_filter(filterToGet, myfunctionName, priority, numParameters)

function format_bbp_topic_freshness_date( $anchor, $forum_id, $time_since){
	
	
	$forum_id = bbp_get_forum_id( $forum_id );
	$active_id = bbp_get_forum_last_active_id( $forum_id );
	
	$modifiedDate = get_post_field( 'post_date', $active_id );
	$active_author_id = get_post_field( 'post_author', $active_id );
	
	$dateFirstLine = bbp_convert_date($modifiedDate, "d M y");
	$dateSecondLine = bbp_convert_date($modifiedDate, "H:i");
	
	$formattedDate = $dateFirstLine . ", " . $dateSecondLine;
	
	
	if ( empty( $active_id ) ) {
		
		$active_id = bbp_get_forum_last_reply_id( $forum_id );
	}
	if ( empty( $active_id ) ) {
		
		$active_id = bbp_get_forum_last_topic_id( $forum_id );
	}
	
	if (bbp_is_topic( $active_id ) ) {
		//$link_url = bbp_get_forum_last_topic_permalink( $forum_id );
		$link_url = bbp_get_forum_last_reply_url( $forum_id );
		$link_url .= "?isTopic";		
		//$retstring = '';
	} elseif ( bbp_is_reply( $active_id ) ) {
		$link_url = bbp_get_forum_last_topic_permalink( $forum_id );
		$link_url .= "?isReply";		
		//$link_url = bbp_get_forum_last_reply_url( $forum_id );
		//$retstring = $active_author_name .'<br/> <a href="'. $link_url . '">' . $formattedDate . '</a>';
	}

	
	return "<a href='" . $link_url ."'>" . $formattedDate . "</a>";//$active_author_name .'<br/>' . $formattedDate; // $formattedDate;
	
	//return "<a href='http://cnn.com'>" . $time_since . "</a>";
}

//this filter applies to the VIEW of the freshness links, not the freshness link of the TYPE of item it points to

//add_filter('bbp_get_topic_freshness_link', 'format_bbp_topic_freshness_date', 10, 3);


function format_bbp_forum_freshness_date( $anchor, $forum_id, $time_since){


	$forum_id = bbp_get_forum_id( $forum_id );
	$active_id = bbp_get_forum_last_active_id( $forum_id );

	$modifiedDate = get_post_field( 'post_date', $active_id );
	$active_author_id = get_post_field( 'post_author', $active_id );

	$dateFirstLine = bbp_convert_date($modifiedDate, "d M y");
	$dateSecondLine = bbp_convert_date($modifiedDate, "H:i");

	$formattedDate = $dateFirstLine . " <span style='font-size:.8em;'>" . $dateSecondLine . "</span>";
	
	$link_url = '';

	if ( empty( $active_id ) ) {

		$active_id = bbp_get_forum_last_reply_id( $forum_id );
	}
	if ( empty( $active_id ) ) {

		$active_id = bbp_get_forum_last_topic_id( $forum_id );
	}

	if (bbp_is_topic( $active_id ) ) {
		$link_url = bbp_get_forum_last_topic_permalink( $forum_id );
		$link_url .= "?isTopic";
		
		
	} elseif ( bbp_is_reply( $active_id ) ) {
		
		$link_url = bbp_get_forum_last_reply_url( $forum_id );
		$link_url .= "?isReply";
		
	}


	return "<a href='" . $link_url ."'>" . $formattedDate . "</a>";
}





//add_filter('bbp_get_forum_freshness_link', 'format_bbp_forum_freshness_date', 10, 3);
add_filter('bbp_get_topic_freshness_link', 'format_bbp_forum_freshness_date', 10, 3);





/* END OF PRODUCTION FUNCTIONS */












/*   THESE ARE ALL DEV FUNCTIONS, NOT TO BE USED OTHER THAN JUST FOR TESTING */
//CALDOL -- ADD FIELDS TO THE USER PROFILE


// hook into loop-single-forum.php
//add_action('bbp_theme_before_forum_sub_forums', 'myText');


function myText(){
	

	echo '<h1>before subForums</h1>';
	

}

function modify_contact_methods($profile_fields) {

	// Add new fields
	//$profile_fields['twitter'] = 'Twitter Username';
	$profile_fields['facebook'] = 'Facebook URL';
	//$profile_fields['gplus'] = 'Google+ URL';
//unset($profile_fields['facebook']);
	return $profile_fields;
}
//add_filter('user_contactmethods', 'modify_contact_methods');


//add_filter('themify_after_post_title', 'showMeThePage');

function showMeThePage(){
//return apply_filters('themify_after_post_title', '<h1 style="color:blue;background-color:green;">got here</h1>');
//echo "doggie 2";
}

//add_filter('themify_content_before', 'caldol_add_nav_sidebar');

function caldol_add_nav_sidebar(){
	$sideBarContent = "<aaside id=\"caldol-nav-sidebar\"> <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('caldol-nav-sidebar') ) ?></aside>:";
	echo $sideBarContent;
}


//add_action('wp_head', 'parallax_child_add_meta_tag');



//CALDOL -- TEST OF FUNCTION TO PUT DATA INTO <HEAD> SECTION

function parallax_child_add_meta_tag(){

//global $current_user;
  //    get_currentuserinfo();

//$facebookName = get_user_meta($user_ID, 'facebook');

$myCode = "doggie";

//echo '<!-- ' . $current_user->get('facebook') . ', ' . $myCode . ',  this is an html comment -->';

} 


function show_template() {
	global $template;
	print_r($template);
} 

function caldol_xprofile_sync_wp_profile( $user_id = 0 ) {

        $bp = buddypress();

        if ( !empty( $bp->site_options['bp-disable-profile-sync'] ) && (int) $bp->site_options['bp-disable-profile-sync'] )
                return true;

        if ( empty( $user_id ) )
                $user_id = bp_loggedin_user_id();

        if ( empty( $user_id ) )
                return false;

        //field ids are in bp_xprofile_fields and need to be updated on the 
        //wp profile as well as the xprofile
        
        $lastname = xprofile_get_field_data( 12, $user_id );
        $firstname = xprofile_get_field_data( 11, $user_id );

        bp_update_user_meta( $user_id, 'first_name', $firstname );
        bp_update_user_meta( $user_id, 'last_name',  $lastname  );

}
add_action( 'xprofile_updated_profile', 'caldol_xprofile_sync_wp_profile' );
add_action( 'bp_core_signup_user',      'caldol_xprofile_sync_wp_profile' );
add_action( 'bp_core_activated_user',   'caldol_xprofile_sync_wp_profile' );


add_action('edit_user_profile_update', 'update_extra_profile_fields');
add_action('personal_options_update', 'update_extra_profile_fields');

function update_extra_profile_fields($user_id) {
	if ( current_user_can('edit_user',$user_id) ){
		
		
		$bp = buddypress();
		
		$newFirstName = sanitize_text_field($_POST['first_name']); //field_id 11;
		$newLastName = sanitize_text_field($_POST['last_name']); //field id 12;
		$newDisplayName = sanitize_text_field($_POST['display_name']); //field id 1;
		
		$fieldList = array(1 => $newDisplayName, 11 => $newFirstName, 12 => $newLastName);
		
		foreach($fieldList as $ID => $val){
		$field           = new BP_XProfile_ProfileData();
		$field->field_id = $ID;//$fieldID;
		$field->user_id  = $user_id;
		$field->value    = maybe_serialize( $val);
		//if($fieldInfo[0] == 11)
		//var_dump($fieldList);
		$field->save();
		}
	
	}
}