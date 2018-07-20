<?php

/*
 Plugin Name: CALDOL Custom CSV Generator
Plugin URI: https://jo.army.mil
Description: Used to produce custom csv files from the JO WordPress DB
Version: 1.0
Author: Thomas O. Morel
Author URI: https://jo.army.mil
*/


/* 
 
 USED TO CLONE THE AUTHOR ROLE TO A NEW ROLE
   NAMED Author-no-email SO THAT MEMBERS THAT
   DON'T WANT TO BE EMAILED CAN OPT OUT.

  THIS METHOD SHOULD ONLY BE RUN ONCE!

*/

/*
add_action('init', 'CreatecloneRoleAuthor');
function CreatecloneRoleAuthor()
{
    global $wp_roles;
    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();

    $atr = $wp_roles->get_role('Author');
    $wp_roles->add_role('author-no-email', 'Author-NO-EMAIL', $atr->capabilities);
}
*/

// Create the shortcode
add_shortcode( 'display-csv', 'caldol_generate_csv_shortcode' );
add_shortcode( 'display-branch', 'get_branch_dropdown' );
add_shortcode( 'display-yeargroup', 'get_yeargroup_dropdown' );
add_shortcode( 'display-currentpost', 'get_current_post_dropdown' );

function caldol_generate_csv_shortcode( $atts ) {

	get_jo_member_list();

	return;

	$search_name = $atts['name'];
	$args        = array(
		'search'         => "*" . $search_name . "*",
		'search_columns' => array( 'user_login' )
	);


	$user_query = new WP_User_Query( $args );

	// Original Attributes, for filters
	$original_atts = $atts;

	echo "User count found: " . $user_query->get_total();
	echo var_dump( $original_atts );

	//echo var_dump($user_query);

	// Get the results
	$members = $user_query->get_results();

// Check for results
	if ( ! empty( $members ) ) {
		echo '<ul>';
		// loop through each member
		foreach ( $members as $member ) {
			// get all the user's data
			$member_info = get_userdata( $member->ID );
			echo '<li>' . $member_info->first_name . ' '
			     . $member_info->last_name . '</li>';
		}
		echo '</ul>';
	} else {
		echo 'No authors found';
	}


	return;

}



function _get_results_count( $field_id ) {
	global $wpdb;

	$result_count_Query
		                = "select count(*) AS result_count, value AS result_key from jo_wp_prod_bp_xprofile_data a join jo_wp_prod_usermeta um on a.user_id=um.user_id 
  where um.meta_key='pw_user_status' and um.meta_value = 'approved' AND field_id = " . $field_id ." group by value";
	$result_count_list  = $wpdb->get_results( $result_count_Query );
	$result_count_array = array();
	foreach ( $result_count_list as $result_item ) {

		$result_count_array[ $result_item->result_key ]
			= $result_item->result_count;

	}

	return $result_count_array;
}


function get_branch_dropdown() {

	global $wpdb;
	$returnHTML = '';
	$branch_Query
	            = "SELECT name from jo_wp_prod_bp_xprofile_fields WHERE parent_id=6 ORDER BY name";
	$branch_list = $wpdb->get_results( $branch_Query );

	$branch_count_array = _get_results_count( 6 );
	$count_value ='';

	$returnHTML .= "<select name=\"branch\"'>";
	$returnHTML .= "<option value=''>Select a branch ...</option>";
	foreach ( $branch_list as $branch ) {

		if(key_exists($branch->name, $branch_count_array)){
			$count_value = $branch_count_array[$branch->name];
		}
		else{
			$count_value = 0;
		}
		$returnHTML .= "<option value=\"" . $branch->name . "\">"
		               . $branch->name . " (" . $count_value . ")</option>";
	}
	$returnHTML .= "</select>";

	return $returnHTML;
}
function get_yeargroup_dropdown() {

	global $wpdb;
	$returnHTML = '';
	$yeargroup_Query
	            = "SELECT name from jo_wp_prod_bp_xprofile_fields WHERE parent_id=21 ORDER BY name";

	$yeargroup_list = $wpdb->get_results( $yeargroup_Query );

	$yeargroup_count_array = _get_results_count( 21 );
	$count_value ='';


	$returnHTML .= "<select name=\"yeargroup\"'>";
	$returnHTML .= "<option value=''>Select a year group ...</option>";
	foreach ( $yeargroup_list as $yeargroup ) {
		if(key_exists($yeargroup->name, $yeargroup_count_array)){
			$count_value = $yeargroup_count_array[$yeargroup->name];
		}
		else{
			$count_value = 0;
		}
		$returnHTML .= "<option value=\"" . $yeargroup->name . "\">"
		               . $yeargroup->name . " (" . $count_value . ") </option>";
	}
	$returnHTML .= "</select>";

	return $returnHTML;
}

function get_current_post_dropdown() {

	global $wpdb;
	$returnHTML = '';
	$currentpost_Query
	            = "SELECT name from jo_wp_prod_bp_xprofile_fields WHERE parent_id=77 ORDER BY name";

	$currentpost_list = $wpdb->get_results( $currentpost_Query );

	$currentpost_count_array = _get_results_count( 77 );
	$count_value ='';


	$returnHTML .= "<select name=\"currentpost\">";
	$returnHTML .= "<option value=''>Select a post ...</option>";
	foreach ( $currentpost_list as $currentpost ) {

		if(key_exists($currentpost->name, $currentpost_count_array)){
			$count_value = $currentpost_count_array[$currentpost->name];
		}
		else{
			$count_value = 0;
		}
		$returnHTML .= "<option value=\"" . $currentpost->name . "\">"
		               . $currentpost->name . " (" . $count_value . ")</option>";
	}
	$returnHTML .= "</select>";

	return $returnHTML;
}


function get_jo_member_list() {

global $wpdb;

	$returnList  = "";
	$member_list = $wpdb->get_results(
		"
	SELECT
  ID, user_email, user_nicename
FROM jo_wp_prod_users u join jo_wp_prod_usermeta um on user_id=u.ID
  where um.meta_key='pw_user_status' and um.meta_value = 'approved' 
ORDER BY user_email
	"
	);

    $validatedMembers = $totalMembers = $wpdb->num_rows;
    foreach ( $member_list as $member ) {
        $user_meta=get_userdata($member->ID);
        $user_roles=$user_meta->roles;
        if (in_array("author-no-email", $user_roles)){
            $validatedMembers--;

        }
        else {
            $returnList .= $member->user_nicename . "," . $member->user_email . "\n";
        }
    }
	$noEmailMemberTotal = $totalMembers - $validatedMembers;
     $summary = "\nThere are " . $totalMembers . " approved members in the JO Forum.\n\n";
    $summary .= "This ALL MEMBERS REPORT has a total of " . $validatedMembers . " members on it.\n\n";
if($noEmailMemberTotal > 0) {
	$summary .= "Note that " . ($noEmailMemberTotal == 1?'1 member is ':$noEmailMemberTotal . ' members are ' ) . "on the NO EMAIL list and NOT included in this report.\n\n";
} 
	else{
        	$summary .= "There are no members on the NO EMAIL list for this report.\n\n";
		}

	$summary .= $returnList;



	//echo $wpdb->num_rows;
	return $summary;


}


function get_custom_jo_member_list( $incomingBranch, $incomingYeargroup, $incomingCurrentpost, $fields) {

	//  GET FIELD ID FOR EACH KEY IN ARRAY
	//
	global $wpdb;

	$branch = $incomingBranch; //$fields['branch'];
	$yeargroup = $incomingYeargroup; //$fields['yeargroup'];
	$currentpost = $incomingCurrentpost;//$fields['currentpost'];

	$returnList = '';

	$yeargroup_where = $yeargroup_join = "";
	$branch_where =$branch_join = "";
	$currentpost_where =$currentpost_join = "";


	$branch_join = "join jo_wp_prod_bp_xprofile_data br ON br.user_id=users.ID";
	$yeargroup_join  = "join jo_wp_prod_bp_xprofile_data yg ON yg.user_id=br.user_id";
	$currentpost_join = "join jo_wp_prod_bp_xprofile_data cp ON cp.user_id=yg.user_id";


	if ( ! empty( $branch ) ) {
		$branch_where = "(br.field_id = 6 and br.value='" . $branch . "')";
	} else {
		$branch_where = "(br.field_id = 6 and br.value !='')";
	}

	if ( ! empty( $yeargroup ) ) {
		$yeargroup_where = "(yg.field_id = 21 and yg.value='" . $yeargroup
		                   . "')";
	} else {
		$yeargroup_where = "(yg.field_id = 21 and yg.value !='')";
	}

	if ( ! empty( $currentpost ) ) {
		$currentpost_where = "(cp.field_id = 77 and cp.value='" . $currentpost
		                     . "')";
	} else {
		$currentpost_where = "(cp.field_id = 77 and cp.value !='')";
	}


			$mainQuery = "select yg.value,
       br.value,
       cp.value,
       users.ID,
       users.user_login,
       users.user_email,
       users.user_nicename
  from jo_wp_prod_users users join jo_wp_prod_usermeta um on um.user_id=users.ID " . $branch_join . " " . $yeargroup_join . " " . $currentpost_join ." 
  where um.meta_key='pw_user_status' and um.meta_value = 'approved' AND " . $branch_where . " AND " . $yeargroup_where . " AND " . $currentpost_where .  "  order by users.user_email desc";

	$member_list = $wpdb->get_results($mainQuery);


	if($member_list) {
		/*$member_list = $wpdb->get_results(
			"select yg.value,
		   br.value,
		   cp.value,
		   users.user_login,
		   users.user_email,
		   users.user_nicename
	  from jo_wp_prod_users users " . $branch_join . " ". $yeargroup_join ." " . $currentpost_join . "
	  where " . $branch_where . " AND " . $yeargroup_where ." AND_" . $currentpost_where . "
	  order by users.user_email desc
		"
		);*/

		/*$member_list = $wpdb->get_results(
			"select yg.value,
		   br.value,
		   cp.value,
		   users.user_login,
		   users.user_email,
		   users.user_nicename
	  from jo_wp_prod_bp_xprofile_data yg
	  join jo_wp_prod_bp_xprofile_data br on br.user_id = yg.user_id
	  join jo_wp_prod_bp_xprofile_data cp on br.user_id = cp.user_id
	  join jo_wp_prod_users users on users.ID=yg.user_id
	where (yg.field_id = 21 and yg.value != 0)
	and (br.field_id = 6 and br.value='". $branch . "')
		and (cp.field_id = 32 and cp.value !='0')
	order by users.user_email desc
		"
		);*/
        $validatedMembers = $totalMembers = $wpdb->num_rows;


        foreach ( $member_list as $member ) {
            $user_meta=get_userdata($member->ID);
            $user_roles=$user_meta->roles;
            if (in_array("author-no-email", $user_roles)) {
                $validatedMembers--;

            }
            else {
                $returnList .= $member->user_nicename . "," . $member->user_email . "\n";
                //var_dump($member);
                //die("died");
            }
        }
        $noEmailMemberTotal = $totalMembers - $validatedMembers;

$summary = "\nThis CUSTOM MEMBERS REPORT has a total of " . $validatedMembers . " members on it.\n";
        $summary .= "\nThere are " . $totalMembers . " approved members in the JO Forum that meet your parameters of:";
        $summary .= "\nYG: ";
        $summary .= empty( $yeargroup ) ? 'ALL' : $yeargroup;
        $summary .= "\nBR: ";
        $summary .= empty( $branch ) ? 'ALL' : $branch;
        $summary .= "\nCurrent Post: ";
        $summary .= empty( $currentpost ) ? 'ALL' : $currentpost;
        $summary .= "\n\n";
        
        if($noEmailMemberTotal > 0) {

            $summary .= "Note that " . ($noEmailMemberTotal == 1 ? '1 member is ' : $noEmailMemberTotal . ' members are ') . "on the NO EMAIL list and NOT included in this report.\n\n";
        }
		else{
        	$summary .= "There are no members on the NO EMAIL list for this report.\n\n";
		}
  
		
		$summary .= $returnList;
		//$returnList .= $summary;

		/* foreach ( $member_list as $member ) {
            $user_meta=get_userdata($member->ID);
            $user_roles=$user_meta->roles;
            if (in_array("author-no-email", $user_roles)){
            	continue;

			}
			else {
                $returnList .= $member->user_nicename . "," . $member->user_email . "\n";
                //var_dump($member);
                //die("died");
            }
		}
		*/



	}

	//$returnList .= $wpdb->last_query;

	//$returnList .= $wpdb->last_query;


	//return $returnList;
	return $summary;

}

?>
