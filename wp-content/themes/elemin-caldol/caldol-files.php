<?php
/*
Template Name: CALDOL-FILES
*/
?>
   
<?php get_header(); ?>
<!-- CALDOL-FILES -->
<?php 
/** Themify Default Variables
 *  @var object */
global $themify; ?>
<hq>caldol-files.php</h1>
<!-- layout child/caldol-files -->
<div id="layout" class="clearfix pagewidth">

<?php // CALDOL -- only show the nav-bar to logged in users ?>
<?php if( is_user_logged_in() ){?>
<aside id="caldol-nav-sidebar">
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('caldol-nav-sidebar') ) ?>
</aside>
<?php }?>

<a name="contentTop" id="contentTop"></a>
	<?php themify_content_before(); // hook ?>
	<!-- content -->
	<div id="content" class="clearfix">
		    	<?php themify_content_start(); // hook ?>

		    	
		<?php 
		/////////////////////////////////////////////
		// 404							
		/////////////////////////////////////////////
		if(is_404()): ?>
			<h1 class="page-title"><?php // _e('404','themify'); ?>	
		<!-- <p><?php _e( 'Page not found.', 'themify' ); ?></p>	 -->	
		<?php endif; ?>

		<?php 
		/////////////////////////////////////////////
		// PAGE							
		/////////////////////////////////////////////
		
if(isset($wp_query->query_vars['targetCat'])) {
$Cat = urldecode($wp_query->query_vars['targetCat']);
}
		
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 5;
		
		$args=array(
				'category_name' => 'cat-tool'
		);
		
		/*
		$my_query = null;
		$my_query = new WP_Query($args);
		if( $my_query->have_posts() ) {
			echo 'List of Posts';
		  while ($my_query->have_posts()) : $my_query->the_post(); ?>
		    <p><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></p>
		    <?php
		  endwhile;
		}
		wp_reset_query();  // Restore global post data stomped by the_post().

		*/
		//query_posts($args);
		
		?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
			<?php if($themify->page_title != "yes"): ?>
				<!-- page-title --> 
				<h1 class="page-title"><?php the_title(); ?></h1>
				
				<!-- /page-title -->
			<?php endif; ?>
			
			<div class="page-content">

		<?php 	//CALDOL CHANGE - ONLY SHOW CONTENT TO LOGGED IN USERS BUT SHOW THE FRONT, REGISTER, AND ABOUT PL PAGE TO ALL
			
		//$targetPath = $_SERVER['REQUEST_URI'];
		//$isRegisterPage = strpos($targetPath, 'register/') + strlen('register/') === strlen($targetPath);
		
		global $post;
		$slug = get_post( $post )->post_name;
		
		if('' == $themify->query_category && (is_user_logged_in() || is_front_page() || $slug == 'register' || $slug == 'about-pl') ): ?>
			
			<!--  before content -->
				<?php echo "<h1>excerpt</h1>"; the_excerpt(); //the_content(); ?>
			<!--  after content -->
				<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages:','themify').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

				<?php edit_post_link(__('Edit','themify'), '[', ']'); ?>

				<!-- comments -->
				<?php if(!themify_check('setting-comments_pages') && $themify->query_category == ""): ?>
					<?php comments_template(); ?>
				<?php endif; ?>
				<!-- /comments -->
		<?php endif; //is_user_logged_in?>	
			</div>
			<!-- /.page-content -->
		<?php 

?>
		<?php endwhile; endif; ?>
	
		<?php 
		/////////////////////////////////////////////
		// Query Category							
		/////////////////////////////////////////////

		
		if('' != $themify->query_category && is_user_logged_in() ): ?>
		
			<?php
			
			if(isset($wp_query->query_vars['targetCat'])) {
				$Cat = urldecode($wp_query->query_vars['targetCat']);
			}
			// Categories for Query Posts or Portfolios
			$categories = '0' == $themify->query_category? $themify->theme->get_all_terms_ids($themify->query_taxonomy) : explode(',', str_replace(' ', '', $themify->query_category));
			$qpargs = array(
				'post_type' => $themify->query_post_type,
				'tax_query' => array(
					array(
						'taxonomy' => $themify->query_taxonomy,
						'field' => 'id',
						'terms' => $categories
					)
				),
				'posts_per_page' => $themify->posts_per_page, 
				'paged' => $themify->paged,
				'order' => $themify->order,
				'orderby' => $themify->orderby
			);
			$qpargs['cat'] = $Cat;
			?>

			<?php
			query_posts(apply_filters('themify_query_posts_page_args', $qpargs)); var_dump($qpargs);?>
				<?php if(!have_posts())
				{
						echo "No items found in that category";
						
					echo "<h5>Would you like to search for something in particular?</h5>";
					get_search_form();
					}
					?>
				
			<?php if(have_posts()): ?>

			<?php if($Cat != ''){
				echo "<h4> from Category: " . get_cat_name( $Cat )  . "</h4>";
			}
			?>
				<!-- loops-wrapper -->
				<div id="loops-wrapper" class="loops-wrapper">
	

				<?php //REMOVE THE CATEGORIES: ARTICLE (49), INTERVIEW (44), STORY (45)?>
		  		<?php //query_posts('cat=-49,-44,-45'); ?>       
		
					<?php while(have_posts()) : the_post(); ?>
					<?php get_template_part('includes/loop', 'caldol-files');//$themify->query_post_type); ?>
					
					<?php endwhile; ?>
		

				</div>
				<!-- /loops-wrapper -->
					<?php if ($themify->page_navigation != "yes"): ?>
						<?php //get_template_part( 'includes/pagination'); ?>
					<?php endif; ?>
					
									
				<?php if(themify_is_query_page() && 'section' != $themify->query_post_type) { ?>
					<?php if ($themify->page_navigation != "yes"): ?>
						<?php get_template_part( 'includes/pagination'); ?>
					<?php endif; ?>
				<?php } ?>
						
			<?php else : ?>	
			
			<?php endif; ?>

		<?php endif; ?>
        
		<?php themify_content_end(); // hook ?>

	</div>
	<!-- /content -->
    <?php themify_content_after(); // hook ?>

	<?php 
	/////////////////////////////////////////////
	// Sidebar							
	/////////////////////////////////////////////
	if ($themify->layout != "sidebar-none" && !is_search()): get_sidebar();  endif; ?>

</div>
<!-- /layout-container -->
	
<?php get_footer(); ?>
