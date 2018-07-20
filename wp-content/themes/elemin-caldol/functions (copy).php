<?php
  
/* LOAD THE PARENT THEMEs */

function theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
 wp_enqueue_style( 'armymag-style', get_stylesheet_directory_uri() . '/armymag.css' );
	  // wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css',array( $parent_style ));
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

/****  CHANGE LOGIN PAGE CSS  ***/
function caldol_login_stylesheet() {

wp_enqueue_script("jquery");
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/style-login.css' );
    wp_enqueue_script( 'custom-login', get_stylesheet_directory_uri() . '/style-login.js', array( 'jquery' ) );
}
add_action( 'login_enqueue_scripts', 'caldol_login_stylesheet' );


// ADD OUR CUSTOM JS FUNCTIONS IN THE FILE caldol-js-functions.js FILE
// AND INCLUDE THE JS FILE IN THE HEADER
// THE FUNCTION get_stylesheet_directory_uri() WILL RETRIEVE THE DIRECTORY
// FOR THE CHILD THEME.

add_action('wp_enqueue_scripts', 'caldol_add_js_functions');


/*  ONLY ALLOW AUTHENTICATED REST API CALLS   */

add_filter( 'rest_authentication_errors', function( $result ) {
    if ( ! empty( $result ) ) {
        return $result;
    }
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => 401 ) );
    }
    return $result;
});


define('WP_TEMP_DIR', dirname(__FILE__) . '/wp-content/temp/');


/* ADMIN AREA CSS STYLES */

add_action('admin_head', 'jo_custom_admin_css');

function jo_custom_admin_css() {
  echo '<style>

    tr.user-rich-editing-wrap, 
    tr.user-admin-color-wrap, 
    tr.user-comment-shortcuts-wrap,
    tr.show-admin-bar user-admin-bar-front-wrap,
    tr.user-url-wrap,
    tr.user-description-wrap,
    #themify-microdata{
 
    display: none; 
}

#wpadminbar .quicklinks li#wp-admin-bar-bp-notifications #ab-pending-notifications {
    background: #ddd;
    color: #333;
    margin: 0;
}

    #wpadminbar .quicklinks li#wp-admin-bar-bp-notifications #ab-pending-notifications.alert{
    background-color: #ff0000;
}

#wpadminbar .quicklinks li#wp-admin-bar-bp-notifications #ab-pending-notifications.pending-count{
    background-color: #ff0000;
}

 
#wpadminbar .quicklinks li#wp-admin-bar-my-account a span.count, 
#wpadminbar .quicklinks li#wp-admin-bar-my-account-with-avatar a span.count {
  background-color: #ff0000;
}


  </style>';
}

/* REMOVE ORGANIZATION FROM ADMIN PROFILE */
add_filter('themify_metabox/user/fields', 'remove_themify_meta', 99, 1);

function remove_themify_meta($params){

    $params = null;
    return $params;
}


add_filter('login_errors','login_error_message');

function login_error_message($error){
	//check if that's the error you are looking for
	$invalidUser = strpos($error, 'Invalid');
	if (is_int($invalidUser)) {
		//its the right error so you can overwrite it
		$error = "Those credentials are invalid";
	}
	$invalidPwd = strpos($error, 'incorrect');
	if (is_int($invalidPwd)) {
		//its the right error so you can overwrite it
		$error = "Those credentials are invalid";
	}
	return $error;
}


// add arrows to menu parent 
function jo_add_menu_parent_class( $items ) {
 
 $parents = array();
 foreach ( $items as $item ) {
 if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
 $parents[] = $item->menu_item_parent;
 }
 }
 
 foreach ( $items as $item ) {
 if ( in_array( $item->ID, $parents ) ) {
 $item->classes[] = 'has-children';
 }
 }
 
 return $items;
}
add_filter( 'wp_nav_menu_objects', 'jo_add_menu_parent_class' );

/*

add_filter( 'comments_clauses', function ( $pieces, $query ) {
    //if ( empty( $query->query_vars['wpse_no_password'] ) ) return $pieces;
   if(is_main_query){
global $post;
if(($post->post_password) != ''){
 global $wpdb;
    $pieces[ 'where' ] .= $wpdb->prepare( ' AND ' . $wpdb->posts . '.post_password = %s', '' );
 if(get_current_user_id() == 2){
	echo "GOT HERE";
print_r($post);
}
}
}

    return $pieces;
}, 10, 2 );






//add_filter( 'widget_posts_args', 'jo_widget_comments_args');
add_filter( 'widget_comments_args', 'jo_widget_comments_args');
function jo_widget_comments_args($args) {
 
  if(get_current_user_id() == 2){
	$args['post_password'] = '';

	print_r($args);
	global $post;

print_r( $GLOBALS['wp_registered_widgets']['recent-comments-2'] );
	//print_r($post);
  }
			return $args;
}

*/

/**
* Removes or edits the 'Protected:' part from posts titles
*/
 
add_filter( 'protected_title_format', 'jo_remove_protected_text' );
function jo_remove_protected_text() {
return __('%s');
}

function jo_password_post_filter( $where = '' ) {
  

    if (!is_page(506) && !is_single() && !is_admin()) {
        $where .= " AND post_password = ''";
    }
    return $where;
}
add_filter( 'posts_where', 'jo_password_post_filter' );


// TUESDAY TOOLS SHORTCODE
function caldol_tuesday_tools_shortcode() {

    $returnString = "";
  
  // get date and remove 4 hours from UTC
  $DateTime = new DateTime();
 $DateTime->modify('-4 hours');

    if($DateTime->format('w') == 2){
	   //it's Tuesday
	    $returnString .=  '<div id="tuesday-tools-div">';
	    $returnString .=  '<img id="tuesday-tools-pic" src="/wp-content/uploads/2017/07/soldier-coffee-today-is-tuesday-tools.jpg"/>';
	    $returnString .= '<span id="tools-open-help"><img src="' . get_stylesheet_directory_uri() . '/images/question_blue.png" alt="" width="" height="" /></span>';
	    $returnString .=  "<div id='tuesday-tools-help' style='display: none;'>Every
Tuesday we will ask members to contribute one of the most helpful tools you
use in your current role, be it a spreadsheet, database, document,
checklist, or whatever it is you use to help you stay on top of your game.
We here at the JO TOC will be uploading a tool every Tuesday as well! You
can share your tools by visiting 
<a href='/submit-file-interview/'>this link</a>; please add the tag
\"Tuesday-Tools\" so it's more easily searchable.  You can find Tuesday Tools under Resources->Tools->Tuesday Tools or <a href='/tag/tuesday-tools'>click here</a>.<br/> Join us for \"Tuesday Tools\"
and contribute to the profession!
</div>";
	    $returnString .=  '</div>';

    }
    

    return $returnString;

}

// Hook into action

add_shortcode( 'tuesday-tools', 'caldol_tuesday_tools_shortcode' );




add_action('login_message', "banner_added_login");
function banner_added_login(){
    
    echo "<div id='usg-banner'><h3 id='usg-banner-leadline'>This is a community run by, for, and through Army professionals in service to the profession. It functions consistent with Army standards, which require the following message:</h3><p>YOU ARE ACCESSING A U.S. GOVERNMENT (USG) INFORMATION SYSTEM (IS) THAT IS PROVIDED FOR USG-AUTHORIZED USE ONLY.</p>

<p>By using this IS (which includes any device attached to this IS), you consent to the following conditions:<br/><span id='more-banner-terms-trigger'>more...</span></p>
<div id='more-banner-terms'>
<ul>
    <li>The USG routinely intercepts and monitors communications on this IS for purposes including, but not limited to, penetration testing, COMSEC monitoring, network operations and defense, personnel misconduct (PM), law enforcement (LE), and counterintelligence (CI) investigations.</li>
    <li>At any time, the USG may inspect and seize data stored on this IS.</li>
    <li>Communications using, or data stored on, this IS are not private, are subject to routine monitoring, interception, and search, and may be disclosed or used for any USG-authorized purpose.</li>
    <li>This IS includes security measures (e.g., authentication and access controls) to protect USG interests--not for your personal benefit or privacy</li>
    <li>Notwithstanding the above, using this IS does not constitute consent to PM, LE or CI investigative searching or monitoring of the content of privileged communications, or work product, related to personal representation or services by attorneys, psychotherapists, or clergy, and their assistants. Such communications and work product are private and confidential. See User Agreement for details.</li>
</ul></div></div>";
}



add_action('login_form','caldol_terms_acceptance_login_field');
function caldol_terms_acceptance_login_field(){

echo '<p>
         <input style="width: inherit; margin:0 3px 0 0;" type="checkbox" value="1" class="input" id="terms_acceptance_field" name="terms_acceptance_field_name"/>       <label for="my_extra_field">I agree to the terms above.</label>
    </p>';


}

function check_terms_acceptance_checkbox($user, $password)
{
    if( !isset($_POST['terms_acceptance_field_name']) )
     {
        remove_action('authenticate', 'wp_authenticate_username_password', 20);
        $user = new WP_Error( 'denied', __("<strong>ERROR</strong>: Please agree to our terms.") );
    }

    return $user;
}
add_filter( 'wp_authenticate_user', 'check_terms_acceptance_checkbox', 10, 3 );





function caldol_add_js_functions(){
	
	wp_enqueue_script('caldol-js-functions', get_stylesheet_directory_uri() . '/js/caldol-js-functions.js', array('jquery'), '1.0', false); 
}




function caldol_home_page_warning(){

if(is_page('register') || is_home() || is_front_page()){
echo "<div id='gmail-warning'>";
  echo "<div><p class='gmail-warning-header'>IMPORTANT NOTICE ABOUT EMAILS FROM THE JO FORUM <span id='show-gmail-warning-text'><a href='#'>Read Notice</a></span></p>";
echo "<p class='gmail-warning-text'>Emails from the JO Forum are currently being sent via Gmail (8CALDOL@gmail.com)<br/>
We will eventually send traffic from a military domain, but for now, you should expect all communications coming from the JO Forum to be from the Gmail address. 
The 8CALDOL@gmail.com address is solely controlled and monitored by Army personnel.<br/>Thanks for understanding as we work through our growing pains!</p>";
echo "</div></div";
}


}
add_action('themify_content_before', 'caldol_home_page_warning');

function caldol_before_post_title_after_registration(){
if(is_page('cclpd-public') && isset($_GET['registered']) && $_GET['registered'] == 1){

echo "<div id='post-registration-message'><h2>Thanks for registering!</h2>";
echo "<p>While awaiting approval of your request for membership, we thought you'd like to view this page for some additional resources.</p></div>";
}
}
add_action('themify_content_start', 'caldol_before_post_title_after_registration');


function caldol_wp_nav_menu_args($args = ''){

if( !is_user_logged_in() ){
$args['menu'] = "logged-out";
}
}
//add_filter('wp_nav_menu_args', 'caldol_wp_nav_menu_args');

// CHECK TO SEE IF USER REQUESTS THE CCLPD PAGE
// IF THEY ARE NOT LOGGED IN, THEY ARE REDIRECTED
// TO THE "PUBLIC" VERSION OF THE PAGE

add_action('template_redirect', 'caldol_public_cclpd_redirect');
function caldol_public_cclpd_redirect(){
    //I load just before selecting and rendering the template to screen
  

  if(is_page('cclpd') && !is_user_logged_in()){
	wp_redirect( '/cclpd-public' );
exit;
  }
}


/*
function caldol_display_topic_index_query($args){

print_r($args);
return $args;
}

add_filter( 'bbp_before_has_topics_parse_args', 'caldol_display_topic_index_query');

*/

/***  WHEN USING THE ACTIVITY FEED ON TABS, IT CAUSES THE PAGING TO */
/*    ALWAYS GO TO THE FRONT PAGE.  THIS FUNCTION PREVENTS THAT BY */
/*    INSERTING THE PAGE SLUG INTO THE PAGING LINKS */

function caldol_tab_forum_pagination_links($pageLinks){

 global $post;
    $post_slug=$post->post_name;

//if(is_home() || is_front_page() || is_bbpress()){
if(is_home() || is_front_page()){
//echo $post_slug;
  return $pageLinks;
}
else{

if(get_current_user_id() == 2){
  //echo $post_slug;
 // $pageLinks = str_replace("href=\"", "href=a\"b", $pageLinks);
//echo "new: " . $pageLinks . " done";

}

$pageLinks = str_replace("/page/", "/".$post_slug."/page/", $pageLinks);
//if($post_slug != 'home' && $pageLinks){

//}
}
return $pageLinks;


}


add_filter( 'bbp_get_forum_pagination_links', 'caldol_tab_forum_pagination_links' );


add_filter( 'bbp_is_site_public', 'yourownprefix_enable_bbp_activity', 10, 2);

function yourownprefix_enable_bbp_activity( $public, $site_id ) {
	return true;
}

/*  ADD THE FORUM DESCRIPTION TO THE TOP OF THE FORUM PAGE */
//filter to add description after forums titles on forum index
function rw_singleforum_description() {
  echo '<div class="bbp-forum-content">';
 // echo bbp_forum_content();
  echo "<p>Listed below are the topic-specific areas in which you can initiate and/or participate in a discussion.  Click on a topic and you will see the list of discussions in that area.  You can start a new discussion at the bottom of that page.</p>";
  echo '</div>';
}
//add_action( 'bbp_template_before_single_forum' , 'rw_singleforum_description');


//add_action( 'phpmailer_init', 'caldol_phpmailer_init' );
function caldol_phpmailer_init( PHPMailer $phpmailer ) {
    $phpmailer->Host = 'smtp.1and1.com';
    $phpmailer->Port = 25; // could be different
    $phpmailer->Username = 'contact@platoonleader.net'; // if required
    $phpmailer->Password = 'C@lD0l!!'; // if required
    $phpmailer->SMTPAuth = true; // if required
    // $phpmailer->SMTPSecure = 'ssl'; // enable if required, 'tls' is another possible value

    $phpmailer->IsSMTP();
}



function caldol_topic_community_validation($forumID){

//see if the user checked any communities
 if(!isset($_POST['postCommunityList'])){


		bbp_add_error( 'caldol_no_community', __( '<strong>ERROR</strong>: You must identify at least one community (CC, PL, etc.).', 'bbpress' ) );
}
}
add_action('bbp_new_topic_pre_extras', 'caldol_topic_community_validation');


function caldol_new_topic_pre_insert($topic_data){



$postCommunityList = array();
$postTopicList = array();
$postTagList = array();
  
  //print_r( $_POST['postCommunityList']);
  
  //echo "<br/>data:<br/>";
  //print_r($topic_data);

// get current taglist

if(isset($_POST['bbp_topic_tags'])){

$postTagList = preg_split ('/[\s*,\s*]*,+[\s*,\s*]*/', $_POST['bbp_topic_tags']);

}

	//$tagList = isset($_POST['bbp_topic_tags'])?array($_POST['bbp_topic_tags']):array();

 
//see if the user checked any communities
 if(isset($_POST['postCommunityList'])){

	$postCommunityList = $_POST['postCommunityList'];
}

//see if the user checked any topics (warfighting, supply,, etc.)
 if(isset($_POST['postTopicList'])){

	$postTopicList = $_POST['postTopicList'];
}


	
	$topic_data['tax_input']['topic-tag'] = array_merge(  $postTagList, $postCommunityList, $postTopicList );

  // echo "<br/>new data:<br/>";
  // print_r($topic_data);
  // echo "<br/>post: </br>";
  // print_r($topic_data);
  // die();

  return $topic_data;
  
}
add_filter('bbp_new_topic_pre_insert', 'caldol_new_topic_pre_insert');




function caldol_edit_topic_pre_insert($topic_data){
  
  	$tagList = isset($_POST['bbp_topic_tags'])?$_POST['bbp_topic_tags']:'';
  	$tempTagListArray = preg_split ('/[\s*,\s*]*,+[\s*,\s*]*/', $tagList); //explode(", ", $tagList);
 	$commList = isset($_POST['postCommunityList'])?$_POST['postCommunityList']:array();
 	$topicList = isset($_POST['postTopicList'])?$_POST['postTopicList']:array();
 
  // get parent community names
  
  $parentTopicID =  (get_category_by_slug('cat-community'))-> term_id;

$communityCategories = get_terms( 'category', array(
'orderby'    => 'name',
'hide_empty' => 0,
'parent' => $parentTopicID,

) );
  
  //remove community names from current tag list
// echo "Chosen on webpage: <br/>";
//print_r($commList);

// echo "<br/>taglist from page:  " . $tagList . "<br/>";
//  print_r($tempTagListArray);
// echo "<br/>";

  	foreach($communityCategories as $term){
	  
	  if(in_array($term->name, $tempTagListArray)){
		//echo "<h1>found: " . $term->name . " -- ";
		
	 // unset($tempTagListArray[$term]);
	$key = array_search($term->name, $tempTagListArray);
	 //echo "key: $key </h1>";
unset($tempTagListArray[$key]);

}

}

// GET TOPICS (Additional duties, fitness, etc)
  // get parent community names
  
  $parentTopicID =  (get_category_by_slug('cat-topic'))-> term_id;

$communityCategories = get_terms( 'category', array(
'orderby'    => 'name',
'hide_empty' => 0,
'parent' => $parentTopicID,

) );
  
  //remove topic names from current tag list
	//$tagList = isset($_POST['bbp_topic_tags'])?$_POST['bbp_topic_tags']:'';
  	//$tempTagListArray = preg_split ('/[\s*,\s*]*,+[\s*,\s*]*/', $tagList); //explode(", ", $tagList);
 	//$commList = $_POST['postCommunityList'];
 // echo "Chosen on webpage: <br/>";
//print_r($commList);

// echo "<br/>taglist from page:  " . $tagList . "<br/>";
//  print_r($tempTagListArray);
// echo "<br/>";

  	foreach($communityCategories as $term){
	  
	  if(in_array($term->name, $tempTagListArray)){
		//echo "<h1>found: " . $term->name . " -- ";
		
	 // unset($tempTagListArray[$term]);
	$key = array_search($term->name, $tempTagListArray);
	 //echo "key: $key </h1>";
unset($tempTagListArray[$key]);

}

}


/*
  echo "<br/><br/>taglist after all communities removed: <br/>";
  print_r($tempTagListArray);
    echo "<br/>new list of communities chosen on web page: <br/>";
  print_r( $_POST['postCommunityList']);
  echo "<br/>tag list from page:<br/>";
  print_r( $_POST['bbp_topic_tags']);

  */


	$newTagList = array_merge($tempTagListArray, $commList, $topicList);
	//echo "<br/>new tag list final<br/>";
	//print_r($newTagList);
	//echo "<br/>end new tag list final<br/>";

	
  
  // echo "<br/>new data:<br/>";
  // print_r($topic_data);
 //  echo "<br/>net taglistarray: </br>";
//  print_r($tagListArray);
  
//  echo "reverse diff<br/>";
//	$newdiff = array_intersect($tagListArray, $commList);
//  print_r($tagListArray);
//  echo "<br/>";
//  print_r($newdiff);
		  
  // die();

$topic_data['tax_input']['topic-tag'] = $newTagList;

//echo "<br/>FINAL \$topic_data: <br/>";
//print_r($topic_data);
//die();
  return $topic_data;
  
}
add_filter('bbp_edit_topic_pre_insert', 'caldol_edit_topic_pre_insert');




function caldol_register_custom_views() {
	bbp_register_view( 'CCWF', __( 'CC and Warfighting' ), array( 'topic-tag' => 'CC+warfighting' ) );
}
add_action( 'bbp_register_views', 'caldol_register_custom_views' );


/* send all contact form submissions to 8caldol as well */

//add_action('wpcf7_before_send_mail', 'include_8caldol_contact_us');

function include_8caldol_contact_us($cf7){


$submission = WPCF7_Submission::get_instance();
if($submission){
$posted_data = $submission->get_posted_data(); 

}
if($cf7->id == 281){
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
//add_filter( 'wpcf7_validate_email', 'DEE_validation_filter_func', 10, 2 ); // Email field or contact number field
//add_filter( 'wpcf7_validate_email*', 'DEE_validation_filter_func', 10, 2 ); // Req. Email field or contact number



function caldol_validate_registration_fields(){

    global $bp;

    $lastName = isset($_POST['field_1'])?$_POST['field_1']:'';
    $firstName = isset($_POST['field_3'])?$_POST['field_3']:'';
    $EEmail = isset($_POST['field_5'])?$_POST['field_5']:'';
    $reason = isset($_POST['field_80'])?$_POST['field_80']:'';
    $password = isset($_POST['signup_password'])?$_POST['signup_password']:'';
/* None of the dropdowns need to be checked since bp already checks for empty */


    if ( $firstName != '' && $lastName != '' && (strlen($firstName) <= 2) && (strlen($lastName) <= 2) ){
          $bp->signup->errors['field_1'] = $firstName . ', ' . $lastName . ': Sorry, your first and last names cannot both be 2 characters or less';
}

// check for AKO address
if($EEmail != '')
{
	preg_match('/@mail\.mil|us\.army\.mil/', $EEmail, $matches);

      if(!$matches){
            $bp->signup->errors['field_5'] = "You must provide your EE/AKO address";
}
}

if(strlen($reason) > 0 && strlen($reason) < 10){
           $bp->signup->errors['field_80'] = "You must provide a valide reason for joining";

}


if(strlen($password) > 0 && strlen($password) < 12){
           $bp->signup->errors['signup_password'] = "Your password must be at least 12 characters";

}
//    print_r($_POST);

}

add_action('bp_signup_validate','caldol_validate_registration_fields');


function caldol_remove_vis_edit_on_profiles(){
if(is_page('register')){
return false;
}
else{
return true;
}
}
add_filter('user_can_richedit', 'caldol_remove_vis_edit_on_profiles', 50);


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

// Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');

add_shortcode( 'caldol-whats-new-form', 'caldol_whats_new_form_shortcode' );

function caldol_whats_new_form_shortcode( $atts, $content = null ) {
global $bp;
//$defaults = array( "redirect" => site_url( $_SERVER['REQUEST_URI'] ));

//extract(shortcode_atts($defaults, $atts));
if (is_user_logged_in()) {

$content .= "<div id='mini-whats-new-wrapper'>";
$content .= "<div id='mini-whats-new'>";
  
  ob_start();
  bp_get_template_part( 'activity/post-form');
  $output =  ob_get_clean();
  //$tempPart = bp_get_template_part( 'activity/post-form');
$content .= $output . "</div> ";
  //$content .= "Raw content: <pre>" . $content . "</pre>";

$content .= "<div><span class='current-status'>Current status:</span><br/> " ;
    ob_start();
 bp_activity_latest_update($bp->loggedin_user->id);
  $output2 = ob_get_clean();
  
  $content .= $output2 . "</div>";

  //return '';

}
  return $content;

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

		return "0 PIE";

	}



	if($count == 1){

		return $count.' PIE ';

	}

	else{

		return $count.' PIEs';

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
		 
		// add_filter ("wp_mail_content_type", "caldol_mail_send_plaintext");
		
		 $pieSubject = "PIE from the JO Forum";
		 $userUserName = $current_user->user_login;
		 $userLink = bp_core_get_user_domain( $current_user->ID );
		 $currPostTitle = html_entity_decode($currPost->post_title, ENT_QUOTES );
		 $currPostLink = get_permalink($postID);
		 
			 if(wp_mail($authorEmail,'PIE from the Junior Officer Forum', "$authorFirstName,\r\n\r\nJO member $userUserName ($userLink), found your contribution of \"$currPostTitle\" ($currPostLink) to be Positive, Inspiring, and Energizing.  \r\n\r\nThank you for serving up PIE in the Junior Officer forum.  Contributors like you make a positive impact on our profession. \r\n\r\n-- The Junior Officer Forum Team", $headers))
			 
			 	
		   {
		     //echo "mail sent";
		   }
		   else
		   {
		     echo "mail failed";
		   }
		  // remove_filter("wp_mail_content_type", "caldol_mail_send_plaintext");

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


// ADD LIKE AND PIE TO REPLIES

//add_filter('bbp_get_topic_content', 'test_caldol_like_replies', 10, 2);
//add_filter('bbp_get_reply_content', 'test_caldol_like_replies', 10, 2);

function test_caldol_like_replies($content, $reply_id) {

    //$content .= "<h2>The reply ID is " . $reply_id . "</h2>";
    //return $content;

	global $post;
	$content .=  '<div class="discussion-likes-wrapper">';
	//$content .=  "get_the_ID(): " . get_the_ID() . "</br>";
	//$content .=  "post_id: " . $post->ID . "</br>";
	//$content .=  "reply_id: " . $reply_id . "</br>";


	//$content .=  getPostViews( $reply_id );
	/* see if the user has liked this post  */
	$userLiked = hasLiked( $reply_id, wp_get_current_user()->ID );
	$userPied  = hasPied( $reply_id, wp_get_current_user()->ID );

	$hideLiked = $userLiked?"":"hideLiked";
	$hidePied = $userPied?"":"hidePied";

	$content .=  '<input type="hidden" id="targetPostID" value="' . $reply_id . '"/>';
	$content .=  '<div class="like-div"><span class="likeCount" id="likeCount_' . $reply_id . '">' . getPostLikes($reply_id) . '</span>';

	if(!$userLiked){

		$content .=  '<button value="' . $reply_id . '" class="likeButton showLikeButton likeButton_' . $reply_id . '" >Like</button>';
	}
	else{
		//$content .=  '<span class="hasLiked  id="hasLiked_' . $reply_id . '">You like this&nbsp;&nbsp;&nbsp;</span>';
	}

	$content .=  '<span class="hasLiked ' . $hideLiked . '" id="hasLiked_' . $reply_id . '">You like this</span>';
	$content .= '</div>';

	//$content .= "&nbsp;&nbsp;&nbsp;&nbsp;";

    $content .= '<div class="pie-div"><span class="pieCount" id="pieCount_' . $reply_id . '">'. getPostPies( $reply_id ) . '</span>';
	if(!$userPied){
		$content .=  '<button value="' . $reply_id . '" class="pieButton showPieButton pieButton_' . $reply_id . '" title="Did you find this Positive, Inspiring, or Energizing (PIE)?. If so, click the button to let the author know that.  An email will be sent to the author to let them know.">Send PIE</button>';

	}
	else{
		//$content .= '<span class="hasPied  id="hasPied_' . $reply_id . '">You sent some PIE!&nbsp;&nbsp;&nbsp;</span>';

	}

	$content .= '<span class="hasPied ' . $hidePied . '" id="hasPied_' . $reply_id . '">You sent some PIE!</span>';
    $content .= '</div>';
    $content .=  '</div>';

	return $content;

}


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



/**

 * Redirect non-admin users to home page

 *

 * This function is attached to the 'admin_init' action hook.

 */


add_action( 'admin_init', 'caldol_redirect_non_admin_users' );
function caldol_redirect_non_admin_users() {
    
  /*this is the array containing the topic lead list
   * the KEY is the ID of the community or topic
   * the VALUE is an array that starts with the community/topic slug
   * and then lists the userids that are authorized to edit that particular
   * community or topic page
   */  

$currentUserID = get_current_user_id();

    //$leadList[topic/community ID] = array(pageslug, topic lead ids separated by commas);
    
    $leadList = array(array());
    $leadList[50] = array('cc-toc', 127, 6); 
    $leadList[367] = array('cc-community', 127);
    
    $leadList[60] = array('staff-toc', 1); 
    $leadList[373] = array('staff-community', 1);
    
    $leadList[61] = array('xo-toc', 187); 
    $leadList[377] = array('xo-community', 187); 
    $leadList[62] = array('pl-toc', 57, 235); 
    $leadList[370] = array('pl-community', 57, 235); 
    $leadList[63] = array('bolc-toc', 131); 
    $leadList[342] = array('bolc-community', 131); 
    $leadList[948] = array('arng-usar-toc', 140, 163); 
    $leadList[935] = array('arng-usar-community', 140, 163); 

    $leadList[395] = array('leadership-topic', 67); 
    $leadList[354] = array('soldiers-and-families-topic', 141); 
    $leadList[433] = array('training-topic', 112, 144); 
    $leadList[702] = array('planning-topic', 56); 
    $leadList[390] = array('fitness-topic', 149); 
    $leadList[421] = array('self-development-topic', 231);
    
    $leadList[383] = array('additional-duties-topic', 1); 
    $leadList[400] = array('maintenance-topic', 1); 
    $leadList[405] = array('operations-topic', 1); 
    $leadList[410] = array('personnel-topic', 1); 
    $leadList[426] = array('supply-topic', 1); 

 if( defined( 'DOING_AJAX' ) && DOING_AJAX ){
return;
}


	//if ( ! current_user_can( 'manage_options' ) && '/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF'] ) {
	//if ( ! current_user_can( 'manage_options' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
    
    if(current_user_can('manage_options') || strpos($_SERVER["HTTP_REFERER"], 'action=edit') || strpos($_SERVER["HTTP_REFERER"], 'upload.php') ){
        return;
    }
    else{
	if (  !current_user_can( 'edit_others_pages' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
            //echo "here:";
            //die();
		wp_redirect( home_url() );

		exit;

	}
        else{
            
            
                   //         echo $_SERVER["HTTP_REFERER"];
                //die();
            //echo "here";
             //echo "here2 :";
            //die();
            $targetPage = isset($_GET['post'])?$_GET['post']:-1;
            //echo "target: " . $targetPage . ", array: " . array_keys($leadList) . ", key exists: " ;
            //echo array_key_exists($targetPage, $leadList)?"exists":"doesn't exist";
            //die();
            if( $targetPage != -1 && array_key_exists($targetPage, $leadList)){
                //echo "here 3";
                //sleep(10);
                if(!in_array($currentUserID, $leadList[$targetPage])){
               		wp_redirect($leadList[$targetPage][0] );
                  //echo "here 4";
                  //die();
		exit;

                }
            }  
            else{
                
                wp_redirect(site_url());
                exit;

                
            }
            
        }
    }

}



//  OLD   add_action( 'admin_init', 'caldol_redirect_non_admin_users' );

/**

 * Redirect non-admin users to home page

 *

 * This function is attached to the 'admin_init' action hook.

 */

function OLD_caldol_redirect_non_admin_users() {

 /*
   if($_GET['post'] == 506){
	  
	  
		wp_redirect( home_url() );
	  

		exit;  
	}

  */

	//if ( ! current_user_can( 'manage_options' ) && '/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF'] ) {
  //if ( ! current_user_can( 'manage_options' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
	if ( ! current_user_can( 'edit_pages' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
	  
		wp_redirect( home_url() );
	  

		exit;  
	
	}

}

function caldol_get_the_community_list( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {

	$terms = get_the_terms( $id, $taxonomy );
$term_links = '';


	if ( is_wp_error( $terms ) )

		return $terms;



	if ( empty( $terms ) )

		return false;



	foreach ( $terms as $term ) {
		if($term->parent == 35){

		$link = get_term_link( $term, $taxonomy );

		if ( is_wp_error( $link ) )

			return $link;

		$term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
		
		}


	}



	$term_links = apply_filters( "term_links-$taxonomy", $term_links );



	return $before . join( $sep, (array)$term_links ) . $after;

}

function caldol_get_the_topic_list( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {

	$terms = get_the_terms( $id, $taxonomy );
$term_links = '';


	if ( is_wp_error( $terms ) )

		return $terms;



	if ( empty( $terms ) )

		return false;



	foreach ( $terms as $term ) {
		if($term->parent == 27){

		$link = get_term_link( $term, $taxonomy );

		if ( is_wp_error( $link ) )

			return $link;

		$term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
		
		}


	}



	$term_links = apply_filters( "term_links-$taxonomy", $term_links );



	return $before . join( $sep, (array)$term_links ) . $after;

}

function caldol_get_the_type_list( $id, $taxonomy, $before = '', $sep = '', $after = '' ) {

$term_links = '';

	$terms = get_the_terms( $id, $taxonomy );



	if ( is_wp_error( $terms ) )

		return $terms;



	if ( empty( $terms ) )

		return false;



	foreach ( $terms as $term ) {

		if($term->parent == 25){

			$link = get_term_link( $term, $taxonomy );

			if ( is_wp_error( $link ) )

				return $link;

			$term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';



		}



	}



	$term_links = apply_filters( "term_links-$taxonomy", $term_links );



	return $before . join( $sep, (array)$term_links ) . $after;

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
  
  $attachmentListing = '';

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
	      ! is_home() &&
                !is_page('register') && 
                !is_page('about-jo') && 
                !is_page('contact-us') &&
                !is_page( 'cclpd')  &&
                !is_page('cclpd-public')  &&
		!is_page('ccdp-registration-form') &&
			    (!is_feed("leadershuddle") && !is_category('leadershuddle')) &&
                $slug != "latest-leaders-huddle-episode"
                )			 
			    //&& !(substr( $slug, 0, 5 ) === "cclpd")
	     ){

	//if ( !is_user_logged_in() ) {


auth_redirect();

	}
	
/*
	global $post;

	$slug = get_post( $post )->post_name;

	

	if( !is_user_logged_in() &&
            ( ! is_front_page() && 
                $slug != 'register' && 
                $slug != 'about-pl' && 
                $slug != 'contact-us' &&
                $slug != 'cclpd'  &&
                $slug != 'cclpd-public'  &&
		$slug != 'ccdp-registration-form'
			    //&& !(substr( $slug, 0, 5 ) === "cclpd")
	    ) ){

	//if ( !is_user_logged_in() ) {

		auth_redirect();

	}
*/
}


//add_action('init', 'customRSS');
function customRSS(){
        add_feed('leadershuddle', 'customRSSFunc');

}




function customRSSFunc(){
        get_template_part('rss', 'leadershuddle');
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
	
	//if($_GET['sessexp'] == 1){
	if(isset($_GET['sessexp'])){
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
		redirected to the content you requested.<br/>If you are not registered on the JO Forum, you may do so by <a href='$siteURL/wp-login.php?action=register'>clicking here</a></div>" . $message;

	}

	echo $message;





}


// ! // Add Recent Topics to BBPress

function caldol_most_replies_bbpress_topics_shortcode() {



echo '<!-- html custom-functions -->';

echo '<h4 style="margin-top: 15px;">Discussions with the Most Replies</h4>';


if ( bbp_has_topics( array( 'author' => 0, 'show_stickies' => false, 'order' => 'DESC', 'meta_key' => '_bbp_reply_count', 'orderby' => 'meta_value',  'post_parent' => 'any', 'posts_per_page' => 10 ) ) )

bbp_get_template_part( 'bbpress/loop', 'topics' );

echo '<!-- end -->';

 }

// Hook into action

add_shortcode( 'most-replies', 'caldol_most_replies_bbpress_topics_shortcode');




// ! // Add No-replies to BBPress

function caldol_no_replies_bbpress_topics_shortcode() {

echo '<h4 style="margin-top: 15px;">Discussions with No Replies.  Be the first to jump in!</h4>';


if ( bbp_has_topics( array( 'author' => 0, 'show_stickies' => false, 'order' => 'DESC', 'meta_key' => '_bbp_reply_count', 'orderby' => 'post_date', 'meta_value' => '0',  'meta_compare' => '=', 'post_parent' => 'any', 'posts_per_page' => 10 ) ) )

bbp_get_template_part( 'bbpress/loop', 'topics' );

 }

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



// trying to hide the tab content on the home page
// if the user isn't logged in

function tabtest($content){


if(!is_user_logged_in() && (is_home() || is_front_page())){

$content = 'ss';

}
return $content;

}
//add_filter( 'themify_builder_module_content', 'tabtest' );
// add_action('themify_builder_before_template_content_render', 'tabtest');




function ajax_check_user_logged_in() {

	//echo is_user_logged_in()?'yes':'no';

if(is_user_logged_in()){
   echo 1;
}
else{
echo 0;
}

	die();

}

add_action('wp_ajax_caldol_is_user_logged_in', 'ajax_check_user_logged_in');

add_action('wp_ajax_nopriv_caldol_is_user_logged_in', 'ajax_check_user_logged_in');


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





// ADD TEXT TO THE REGISTRATION PAGE TO INDICATE WHAT TO DO ON 
// THE REGISTRATION PAGE
add_action('bp_before_account_details_fields', 'caldol_registration_intro_message');

function caldol_registration_intro_message(){
	echo '<p>Once you complete the fields below and submit the registration request, our team will review it within 24-48 hours.  You will be notified via email when your
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

//add_action( 'admin_bar_menu', 'wp_admin_bar_my_custom_account_menu', 11 );

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
//add_filter( 'the_title', 'caldol_not_logged_in_modified_post_title', 10 ,2);
function caldol_not_logged_in_modified_post_title ($title, $content) {
	global $bp;
	global $post;

	$slug = get_post( $post )->post_name;

	if ( (in_the_loop()) && ( !is_user_logged_in()  )  ) {

		if($bp->current_component != 'register' &&  
		    $slug !='about-jo' && 
		    $slug !='contact-us' &&
		    $slug !='cclpd' &&
		   $slug !='cclpd-public' &&
	       $slug != 'ccdp-registration-form' &&
	       $slug != 'latest-leaders-huddle-episode' &&
		(!is_feed("leadershuddle") && !is_category('leadershuddle'))
		   // && !(substr( $slug, 0, 5 ) === "cclpd")
		  ){
		  $title = '<h1><span style="color:red;">2598: Misfire! Misfire!</span><br/>You must be registered and logged in as a member in order to access this page.  <br/>
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


/*  SETTINGS FOR TINY MCE USED IN WP_EDITOR  */

function caldol_format_TinyMCE( $in ) {
	$in['remove_linebreaks'] = false;
	$in['gecko_spellcheck'] = false;
	$in['keep_styles'] = true;
	$in['accessibility_focus'] = true;
	$in['tabfocus_elements'] = 'major-publishing-actions';
	$in['media_strict'] = false;
	$in['paste_remove_styles'] = false;
	$in['paste_remove_spans'] = false;
	$in['paste_strip_class_attributes'] = 'none';
	$in['paste_text_use_dialog'] = true;
	$in['wpeditimage_disable_captions'] = true;
	$in['plugins'] = 'tabfocus,paste,media,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpfullscreen';
	$in['content_css'] = get_template_directory_uri() . "/editor-style.css";
	$in['wpautop'] = true;
	$in['apply_source_formatting'] = false;
        $in['block_formats'] = "Paragraph=p; Heading 3=h3; Heading 4=h4";
	$in['toolbar1'] = 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,wp_fullscreen,wp_adv ';
	$in['toolbar2'] = 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help ';
	$in['toolbar3'] = '';
	$in['toolbar4'] = '';
	return $in;
}
//add_filter( 'tiny_mce_before_init', 'caldol_format_TinyMCE' );



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

function remove_howdy( $wp_admin_bar ) {
    $my_account=$wp_admin_bar->get_node('my-account');
    $newtitle = str_replace( 'Howdy,', 'Welcome, ', $my_account->title );
    $wp_admin_bar->add_node( array(
        'id' => 'my-account',
        'title' => $newtitle,
    ) );
}
add_filter( 'admin_bar_menu', 'remove_howdy',25 );
//add_action( 'admin_bar_menu', 'caldol_wp_admin_bar_my_custom_account_menu', 10, 1 );
/*
function caldol_wp_admin_bar_my_custom_account_menu( $wp_admin_bar ) {
	global $bp;
	$user_id = get_current_user_id();
	$current_user = wp_get_current_user();
	$profile_url = get_edit_profile_url( $user_id );
	$bp_user = new BP_Core_User( $user_id );
	if ( 0 != $user_id ) {
		
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
*/




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

    <?php //print_r($GLOBALS['themify_microdata'] ); ?>
    <h3>Member Dogtag Information (read-only)</h3>
    <table class="form-table">
		<?php

		$targetFields = array(
			"Branch",
			"Source of Commissioning",
			"Year Group",
			"Component",
			"Current Status",
			"Unit(s) I\'ve Led",
			"Unit Level(s) Led",
			"Home Station",
			"Reason for Joining the JO Forum",
			"Military/Life Experiences",
			"AKO / Enterprise Email Address"
		);

		$textFieldTargetFields = array(
			"Reason for Joining the JO Forum",
			"Military/Life Experiences",
			"Life Experiences",

                );

    $checkboxFieldTargetFields = array(
	    "Unit(s) I\'ve Led",
	    "Unit Level(s) Led"
    );
    
    foreach ( $targetFields as $currentTargetField ) {// => $targetFieldName){
	    //$targetFieldName = str_replace(' ', '_', $currentTargetField);
	    ?>
        <tr>
            <th><label><?php echo $currentTargetField; ?></label></th>
            <td>
			    <?php

			    if ( function_exists( 'xprofile_get_field_data' ) ) {
				    $xprofile_value = xprofile_get_field_data( $currentTargetField, $user->ID, 'array' );
			    } else {
				    $xprofile_value = 'NOT HERE';
			    }


			    if ( ! in_array( $currentTargetField, $textFieldTargetFields ) ) {
				    ?>
				    <?php //if(true) echo "yes";
				    ?>
				    <?php if ( $currentTargetField == "Unit Level(s) Led" ) {
					    $loopCount = 0;
					    $levelList = "";
					    foreach ( $xprofile_value as $item ) {
						    if ( $loopCount ++ == 0 ) {
							    $levelList .= "$item";
						    } else {
							    $levelList .= ", $item";
						    }
					    } ?>

                        <input type="text" name="<?php echo str_replace( ' ', '_', $currentTargetField ); ?>"
                               id="<?php echo str_replace( ' ', '_', $currentTargetField ); ?>"
                               value="<?php echo esc_attr( $levelList ); ?>" class="regular-text" readonly/>

				    <?php } else { ?>
                        <input type="text" name="<?php echo str_replace( ' ', '_', $currentTargetField ); ?>"
                               id="<?php echo str_replace( ' ', '_', $currentTargetField ); ?>"
                               value="<?php echo esc_attr( $xprofile_value ); ?>" class="regular-text" readonly/>
				    <?php }
			    }

			    else { ?>

                        <textarea rows="5" cols="50" name="<?php echo str_replace( ' ', '_', $currentTargetField ); ?>"
                                  id="<?php echo str_replace( ' ', '_', $currentTargetField ); ?>" class="regular-text"
                                  readonly><?php echo esc_attr( $xprofile_value ); ?></textarea>


				    <?php }//econd else?>
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
  
  $forum_id = bbp_get_forum_id();
	
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


function caldol_bp_signup_pre_validate() {

//validate last name and first name are at least 2 characters
  if ( (isset($_POST['field_1']) && strlen($_POST['field_1']) < 2) || (isset($_POST['field_3']) && strlen($_POST['field_3']) < 2)) {

            $bp->signup->errors['field_5'] =  "Sorry, your first and last names cannot be a single character";
}
}
 

add_action ('bp_signup_pre_validate', 'caldol_bp_signup_pre_validate', 20);

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

//if($user_id == 6 || $user_id == 21){
       $bp = buddypress();

/*
        if ( !empty( $bp->site_options['bp-disable-profile-sync'] ) && (int) $bp->site_options['bp-disable-profile-sync'] )
                return true;
*/
        if ( empty( $user_id ) )
                $user_id = bp_loggedin_user_id();

        if ( empty( $user_id ) )
                return false;

        //field ids are in bp_xprofile_fields and need to be updated on the 
        //wp profile as well as the xprofile
        
        $lastname = xprofile_get_field_data( 1, $user_id );
        $firstname = xprofile_get_field_data( 3, $user_id );
//echo "xpf: " . xprofile_get_field_data( 1, $user_id ) . ":";
//echo "xpl: " . xprofile_get_field_data( 3, $user_id ) . ":";
//die();
       bp_update_user_meta( $user_id, 'first_name', $firstname );
        bp_update_user_meta( $user_id, 'last_name',  $lastname  );
//        bp_update_user_meta( $user_id, 'last_name',  $lastname  );
//        $updated_user_id = wp_update_user( array( 'ID' => $user_id, 'display
//        wp_update_user( array($user_id, '',  $lastname . ", " $firstname  );

//}
}
add_action( 'xprofile_updated_profile', 'caldol_xprofile_sync_wp_profile' );
add_action( 'bp_core_signup_user',      'caldol_xprofile_sync_wp_profile' );
add_action( 'bp_core_activated_user',   'caldol_xprofile_sync_wp_profile' );
add_action('edit_user_profile_update', 'caldol_xprofile_sync_wp_profile');


add_action('edit_user_profile_update', 'update_extra_profile_fields');
add_action('personal_options_update', 'update_extra_profile_fields');

function update_extra_profile_fields($user_id) {
	if ( current_user_can('edit_user',$user_id) ){
		
//if($user_id == 6 || $user_id == 21){
// die("update_extra_profile_line 3351");
		$bp = buddypress();
		
		$newFirstName = sanitize_text_field($_POST['first_name']); //field_id 11;
		$newLastName = sanitize_text_field($_POST['last_name']); //field id 12;
		//$newDisplayName = sanitize_text_field($_POST['display_name']); //field id 1;
		
		//$fieldList = array(1 => $newDisplayName, 3 => $newFirstName, 1 => $newLastName);
		$fieldList = array( 3 => $newFirstName, 1 => $newLastName);
		
		foreach($fieldList as $ID => $val){
		$field           = new BP_XProfile_ProfileData();
		$field->field_id = $ID;//$fieldID;
		$field->user_id  = $user_id;
		$field->value    = maybe_serialize( $val);
		//if($fieldInfo[0] == 11)
		var_dump($fieldList);
//die();
		$field->save();
		}
	
	}
//}
}
/*
function misha_filter_function(){
	$args = array(
		'orderby' => 'date', // we will sort posts by date
		'order'	=> $_POST['date'] // ASC или DESC
	);
 
	// for taxonomies / categories
	if( isset( $_POST['categoryfilter'] ) )
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'topic-tag',
				'field' => 'id',
				'post_type' => array('post', 'topic'),
				'posts_per_page' => 2,
				'terms' => $_POST['categoryfilter']
			)
		);
 
	// create $args['meta_query'] array if one of the following fields is filled
	if( isset( $_POST['price_min'] ) && $_POST['price_min'] || isset( $_POST['price_max'] ) && $_POST['price_max'] || isset( $_POST['featured_image'] ) && $_POST['featured_image'] == 'on' )
		$args['meta_query'] = array( 'relation'=>'AND' ); // AND means that all conditions of meta_query should be true
 
	// if both minimum price and maximum price are specified we will use BETWEEN comparison
	if( isset( $_POST['price_min'] ) && $_POST['price_min'] || isset( $_POST['price_max'] ) && $_POST['price_max'] ) {
		$args['meta_query'][] = array(
			'key' => '_price',
			'value' => array( $_POST['price_min'], $_POST['price_max'] ),
			'type' => 'numeric',
			'compare' => 'between'
		);
	} else {
		// if only min price is set
		if( isset( $_POST['price_min'] ) && $_POST['price_min'] )
			$args['meta_query'][] = array(
				'key' => '_price',
				'value' => $_POST['price_min'],
				'type' => 'numeric',
				'compare' => '>'
			);
 
		// if only max price is set
		if( isset( $_POST['price_max'] ) && $_POST['price_max'] )
			$args['meta_query'][] = array(
				'key' => '_price',
				'value' => $_POST['price_max'],
				'type' => 'numeric',
				'compare' => '<'
			);
	}
 
 
	// if post thumbnail is set
	if( isset( $_POST['featured_image'] ) && $_POST['featured_image'] == 'on' )
		$args['meta_query'][] = array(
			'key' => '_thumbnail_id',
			'compare' => 'EXISTS'
		);
 
	$query = new WP_Query( $args );
 
	if( $query->have_posts() ) :
		while( $query->have_posts() ): $query->the_post();
	   get_template_part('includes/loop', 'caldol-files');//$themify->query_post_type);
		
		endwhile;
		wp_reset_postdata();
	else :
		echo 'No posts found';
	endif;
 
	die();
}
 
 
add_action('wp_ajax_myfilter', 'misha_filter_function'); 
add_action('wp_ajax_nopriv_myfilter', 'misha_filter_function');

*/
add_filter( "the_content_feed", "plugin_function_name" );
function plugin_function_name($content)
{
   $content .= 'Total '.str_word_count($content).' words';
   return $content;
}
