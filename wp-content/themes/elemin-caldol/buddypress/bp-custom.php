<?php



add_filter( 'bbp_is_site_public', 'caldol_enable_bbp_activity', 10, 2);

function caldol_enable_bbp_activity( $public, $site_id ) {
	return true;
}




// remove the buddypress admin option from the admin menu
add_action( 'admin_bar_menu', 'wp_admin_bar_my_custom_account_menu', 11 );
function admin_bar_remove_this(){
	global $wp_admin_bar;
	$wp_admin_bar->remove_node('bp_adminbar_account_menu');

}

function block_wpmu_email() {

	if ( is_admin() && $_GET['e'] )

		return true;


    //return true while debugging.
	return false;

}


/* Prevent logged out users from accessing bp activity page */
//add_filter('get_header','nonreg_visitor_redirect',1);
function nonreg_visitor_redirect() {
	global $bp;
	if ( bp_is_activity_component() || bp_is_groups_component() || bp_is_group_forum() || bp_is_page( BP_MEMBERS_SLUG ) ) {
		if(!is_user_logged_in()) { //just a visitor and not logged in
			//wp_redirect( get_option('siteurl') . '/404.php' );
			//include('my404.php');

			//how do i
			//return new WP_Error( 'http_404', trim( wp_remote_retrieve_response_message( $response ) ) );
		}
	}
}


/* Rehook custom function */

add_filter( 'wpmu_welcome_user_notification', 'block_wpmu_email' );



add_filter( 'the_title', 'ta_modified_post_title');
function ta_modified_post_title ($title, $content) {
	//if ( in_the_loop() && is_page() && !is_user_logged_in() ) {
	if ( (is_search() || in_the_loop() ) && (!is_user_logged_in()  )  ) {
		
		if(false && bp_is_current_component( $bp->activity->slug )){
			$title = '<span style="color:red;">Misfire! Misfire!</span><br/>You must be a member and logged in to view this page';//$title." (modified title)";

		}
	}
	return $title;
}

/**
 * Disable Gravatar throughout BP
 */
add_filter( 'bp_core_fetch_avatar_no_grav', '__return_true' );

/**
 * Provide a global user avatar default
 */
function my_default_avatar_url() {
	return 'http://localhost/~leromt/caldol/wordpress/wp-content/uploads/avatars/1/a68825fe2b2e1fc7467a0c2274c50834-bpthumb.jpg';
}
add_filter( 'bp_core_default_avatar_user', 'my_default_avatar_url' );



if( !function_exists('add_member_custom_extended_profile') ):
///////////////////////////////////////////////////////////////////////////////
// add profile meta to Buddypress members directory
//////////////////////////////////////////////////////////////////////////////
function add_member_custom_extended_profile() {
	$data_jobs = bp_get_member_profile_data( 'field=Branch' );
	$data_skills = bp_get_member_profile_data( 'field=Current Status' );
	echo '<div class="item-meta"><span class="profile-extend-meta">';
	if($data_jobs) echo '<strong>Jobs</strong>' . ': ' . $data_jobs . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	if($data_skills) echo '<strong>Skills</strong>' . ': ' . $data_skills . '';
	echo '</span></div>';
}
//add_action('bp_directory_members_item',  'add_member_custom_extended_profile');
endif;

//use avatar from XProfile instead of defaault profile
function display_avatar($avatar, $params) {
	$avatar = bp_get_member_profile_data(array('field' => 'Avatar', 'user_id' => $params['item_id']));
	return $avatar;
}
add_filter('bp_core_fetch_avatar_url', 'display_avatar', 15, 2);

function display_avatar_html($avatar_html, $params) {
	$upload_dir = wp_upload_dir();
	$new_avatar = $upload_dir['baseurl'] . bp_get_member_profile_data(array('field' => 'Avatar', 'user_id' => $params['item_id']));
	$parts = explode('"', $avatar_html);
	for ($i=0; $i<count($parts);$i++) {
		if (strpos($parts[$i], 'src=')) {
			break;
		}
	}
	$i++;
	$prev_avatar = $parts[$i];
	return str_replace($prev_avatar, $new_avatar, $avatar_html);
}
add_filter('bp_core_fetch_avatar', 'display_avatar_html', 15, 2);


//remove the hyperlinks from all the profile fields. (xprofile plugin)
function remove_xprofile_links() {
    remove_filter( 'bp_get_the_profile_field_value', 'xprofile_filter_link_profile_data', 9, 2 );
}
add_action('bp_setup_globals', 'remove_xprofile_links');


?>
