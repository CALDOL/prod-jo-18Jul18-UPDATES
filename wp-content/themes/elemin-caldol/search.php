<?php if(!is_user_logged_in()):


//echo TEMPLATEPATH . '/404.php' . "<br/>";
//include(get_stylesheet_directory() . "/my404.php");
include(get_stylesheet_directory() . "/404.php");
else: {
global $query_string;
	
//$query_string = $query_string . "&orderby=date&post_type=topic";
	echo "<!-- child search.php -->";

	get_template_part( 'index','search');
}
	
endif;

	
