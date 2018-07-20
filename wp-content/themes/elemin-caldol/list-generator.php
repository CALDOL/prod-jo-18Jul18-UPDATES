<?php

/* Template Name: CSV Generator */

if($_SERVER['REQUEST_METHOD'] == "POST") {

    $content ="";

	if ( isset( $_POST['members'] ) ) {

	$fileName = date( "d-m-y" ) . '-jo-members.csv';



	$content = ""; // content added below

	// Title of the CSV
	//$content = "";

	$content .= get_jo_member_list();
	//echo "CONTENT:\n";
	//echo var_dump($content);
	//echo "DONE CONTENT";

	// Data in the CSV
	//$content .= "\"John Doe\",\"New York, USA\",15,65465464 \n";

	// Create csv and force download
	header( 'Content-Type: application/csv' );
	header( "Content-length: " . strlen( $content ) );
	header( 'Content-Disposition: attachment; filename="' . $fileName . '"' );
	echo $content;
}
else{
	// Create csv and force download
	$fileName = date( "d-m-y" ) . '-jo-custom.csv';
	$fields[] = array();

	$branch = isset($_POST['branch']) && !empty($_POST['branch'])?sanitize_text_field($_POST['branch']):"";
	$fields['branch'] = $branch;

	$yeargroup = isset($_POST['yeargroup']) && !empty($_POST['yeargroup'])?sanitize_text_field($_POST['yeargroup']):"";
	$fields['yeargroup'] = $yeargroup;

	$currentpost = isset($_POST['currentpost']) && !empty($_POST['currentpost'])?sanitize_text_field($_POST['currentpost']):"";
	$fields['currentpost'] = $currentpost;

	/*if(!empty($branch)) {
		$fields['branch'] = $branch;
            }

	if(!empty($yeargroup)) {
		$fields['yeargroup'] = $yeargroup;
	}

		if(!empty($currentpost)) {
			$fields['currentpost'] = $currentpost;
		}*/

	//  CONVERT INPUTS TO AN ARRAY SO THE CODE DOESN'T NEED TO CHANGE

	$content .= get_custom_jo_member_list($branch, $yeargroup, $currentpost,$fields);


	if(!empty($content)) {
		header( 'Content-Type: application/csv' );
		header( "Content-length: " . strlen( $content ) );
		header( 'Content-Disposition: attachment; filename="' . $fileName
		        . '"' );

		//echo print_r($fields);
		echo $content;
	}
	else{
	    echo "No results for the combination were found";
    }
}
	exit;
}

?>

<?php get_header(); ?>

	<div id="sidebar-1">
		<?php get_sidebar(); ?>
	</div> <!-- End div#sidebar -->

	<div id="primarycontent">
		<?php get_template_part( '/loop', 'default' ); ?>

		<h2>Export</h2>

		<form action="" method="post">
			<input name="submit" type="submit" value="Export">
			<input type="hidden" name="submit" />
		</form>

	</div> <!-- End div#primarycontent -->

<?php get_footer(); ?>
