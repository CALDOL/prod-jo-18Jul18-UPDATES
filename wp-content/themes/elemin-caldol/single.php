<?php get_header(); ?>
<!-- single.php -->
<?php 
/** Themify Default Variables
 *  @var object */
global $themify; ?>


<?php if( have_posts() ) while ( have_posts() ) : the_post(); ?>

<!-- layout-container -->
<div id="layout" class="pagewidth clearfix">
<?php // CALDOL -- only show the nav-bar to logged in users ?>


<?php if( false && is_user_logged_in() ){?>
	 <aside id="caldol-nav-sidebar">
   <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('caldol-nav-sidebar') ) ?>
    </aside> 
<?php }?>

	<?php themify_content_before(); // hook ?>
	
	<!-- content -->
	<div id="content" class="list-post">
	<?php // CALDOL -- only show the nav-bar to logged in users ?>

	  <?php

	global $post;

	$slug = get_post( $post )->post_name;
?>

<?php if( is_user_logged_in() || $slug == "latest-leaders-huddle-episode"){?>

    	<?php themify_content_start(); // hook ?>
    	
    	<?php 
		  get_template_part('includes/loop', 'caldol-single');
    	//get_template_part( 'includes/loop' , 'single'); ?> 
    	
    	<?php wp_link_pages(array('before' => '<p><strong>' . __('Pages:', 'themify') . ' </strong>', 'after' => '</p>', 'next_or_number' => 'number')); ?>
    	
    	<?php get_template_part( 'includes/author-box', 'single'); ?>
    	
		<?php if(!themify_check('setting-comments_posts')): ?>
			<?php comments_template(); ?>
		<?php endif; ?>
		
    	<?php get_template_part( 'includes/post-nav'); ?>

		
		<?php themify_content_end(); // hook ?>	
<?php } else{?>

<h1><span style="color:red;">Misfire! Misfire!</span><br/>You must be registered and logged in as a member in order to access this page.  <br/>
		<span style="font-size:.8em;">You may register/login by clicking the register or log in link at the top-left of the page.</span></h1>
<?php }?>
</div>
	<!-- /content -->
	
<?php themify_content_after(); // hook ?>

		<?php endwhile; ?>

<?php 
/////////////////////////////////////////////
// Sidebar							
/////////////////////////////////////////////
if ($themify->layout != "sidebar-none"): get_sidebar(); endif; ?>

</div>
<!-- /layout-container -->

<?php get_footer(); ?>
