<?php get_header(); ?>

<?php 
/** Themify Default Variables
 *  @var object */
global $themify; ?>
		
<!-- child/index -->
<!-- layout -->
<div id="layout" class="clearfix pagewidth">

<!--
<aside id="caldol-nav-sidebar">
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('caldol-nav-sidebar') ) ?>
</aside>
-->
    <?php themify_content_before(); // hook ?>
    <!-- content -->
	<div id="content" class="clearfix">
    	<?php themify_content_start(); //hook ?>
		
		<?php 
		/////////////////////////////////////////////
		// Author Page	 							
		/////////////////////////////////////////////
		if(is_author()) : ?>
			<?php
			$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
			$author_url = $curauth->user_url;
			?>
			<div class="author-bio clearfix">
				<p class="author-avatar"><?php echo get_avatar( $curauth->user_email, $size = '48' ); ?></p>
				<h2 class="author-name"><?php _e('About','themify'); ?> <?php echo $curauth->first_name; ?> <?php echo $curauth->last_name; ?></h2>
				<?php if($author_url != ''): ?><p class="author-url"><a href="<?php echo $author_url; ?>"><?php echo $author_url; ?></a></p><?php endif; //author url ?>
				<div class="author-description">
					<?php echo $curauth->user_description; ?>
				</div>
				<!-- /.author-description -->
			</div>
			<!-- /.author bio -->
			
			<h2 class="author-posts-by"><?php _e('Posts by','themify'); ?> <?php echo $curauth->first_name; ?> <?php echo $curauth->last_name; ?>:</h2>
		<?php endif; ?>

		<?php 
		/////////////////////////////////////////////
		// Search Title	 							
		/////////////////////////////////////////////
		?>
		<?php if(is_search()): ?>

			<h1 class="page-title"><?php _e('Search Results for:','themify'); ?> <em><?php echo get_search_query(); ?></em></h1>
		<?php endif; ?>
	
		<?php 
		/////////////////////////////////////////////
		// Category Title	 							
		/////////////////////////////////////////////
		?>
		<?php if(is_category() || is_tag() || is_tax() ): ?>
			<h1 class="page-title"><?php single_cat_title(); ?></h1>
			<?php echo $themify->get_category_description(); ?>
		<?php endif; ?>

		<?php 
		/////////////////////////////////////////////
		// Default query categories			
		/////////////////////////////////////////////
		?>
		<?php if( !is_search() ): ?>
			<?php

			query_posts( apply_filters( 'themify_query_posts_args', $query_string.'&order='.$themify->order.'&orderby='.$themify->orderby ) );
			?>
			<?php else:
			
			endif; ?>

		<?php 
		/////////////////////////////////////////////
		// Loop	 							
		/////////////////////////////////////////////
		?>
		
		<?php if(is_search()){
		
			global $wp_query;
			/* 
			$args = array(

					'order'    => 'DESC',
					'orderby' => array('post_type' => 'DESC', 'post_name' => 'DESC'),
					'post_type' => array('post', 'reply', 'topic'),
					
			);
			*/
			$args = array(

					'order'    => 'DESC',
					'orderby' => array('post_type' => 'DESC'),

					//'post_type' => array('post', 'reply', 'topic', 'attachment'),
					
			);

/* SAVED

'meta_query' => array(
array(
'key'=>'bp_latest_update', 
'value'=> 'description', 
'compare' => 'NOT LIKE', ),)
*/
			query_posts( array_merge($args, $wp_query->query) );
			
			
			
		}?>

	
	<?php if (have_posts()) : ?>
		
	<?php $prevPostType = "-1"; ?>	
		<!-- loops-wrapper -->
			<div id="loops-wrapper" class="loops-wrapper">


				<?php while (have_posts()) : the_post(); ?>
		
					<?php if(is_search()): 
			
 
						$currPostType = get_post_type(); 
$typeText = "-1";
switch ($currPostType) {
case "page":

    $typeText = "Pages";
    break;
case "post":

    $typeText = "Content";
    break;
case "topic":
case "reply":
    $typeText = "Conversations";
    break;

default:
        $typeText = "Unknown";
}	
	?>

			<?php	if($prevPostType == "-1"){
				$prevPostType = get_post_type() ;
					 echo "<h1 class='search-results-type'>Results from " .$typeText . "</h1>"; 
			}
			?>


						<?php $currPostType = get_post_type(); ?>

						<?php if($prevPostType != $currPostType){

	
							if( !(  ($prevPostType == 'reply' && $currPostType == 'topic')  ||  ($prevPostType == 'topic' && $currPostType == 'reply') )){
							 echo "<h1 class='search-results-type'>Results from " .$typeText . "</h1>"; 
							}

						

						}
						?>

						
						<?php 
						
						//if($post->post_type == 'post' || $post->post_type == 'topic' || $post->post_type == 'reply'  ){
							get_template_part( 'includes/loop' , 'search'); 
						  global $wp_query;
 if($wp_query->current_post + 1 < $wp_query->post_count){
echo "<hr style='margin-bottom: 35px;'>";
}
						//}
						//else{
							
						//}
						?>
					<?php else: ?>
						<?php get_template_part( 'includes/loop' , 'index'); ?>
					<?php endif; ?>
					<?php $prevPostType = get_post_type(); ?>
				<?php endwhile; ?>
							
			</div>
			<!-- /loops-wrapper -->

			<?php get_template_part( 'includes/pagination'); ?>
		
		<?php 
		/////////////////////////////////////////////
		// Error - No Page Found	 							
		/////////////////////////////////////////////
		?>
	
		<?php else : ?>
	
			<p><?php _e( 'Sorry, nothing found.', 'themify' ); ?></p>
	
		<?php endif; ?>			
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
