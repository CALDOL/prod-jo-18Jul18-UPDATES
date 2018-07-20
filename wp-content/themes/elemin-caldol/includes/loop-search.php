<?php if(!is_single()){ global $more; $more = 0; } //enable more link ?>

<?php 
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php  global $current_user;
// get_currentuserinfo();
wp_get_current_user();


       ?>
<?php themify_post_before(); // hook ?>
<!-- child/includes/loop-search -->


<article id="post-<?php the_ID(); ?>" 
<?php //post_class("post clearfix " . $themify->theme->get_categories_as_classes(get_the_ID())); ?>
<?php post_class("post clearfix " . $themify->get_categories_as_classes(get_the_ID())); ?>>
	
	<?php themify_post_start(); // hook ?>
	
	<?php

	//if('above' == $themify->media_position || is_single()) get_template_part( 'includes/post-media', 'loop'); 
	if('above' == 'above' || is_single()) get_template_part( 'includes/post-media', 'loop'); 
  
  	?>
		
	<div class="post-content">

		<?php if($themify->hide_title != 'yes'): ?>
			<?php themify_before_post_title(); // Hook ?>
				<?php if($themify->unlink_title == 'yes'): ?>
		
				<h2 class="post-title">
				
						
				</h2>
		
				<?php else: ?>
				<h2 class="post-title">
					<a href="<?php echo themify_get_featured_image_link(); ?>"
						title="<?php the_title_attribute(); ?>"><?php the_title(); ?> </a>

				</h2>
				<?php endif; //unlink post title ?>
			
			<?php themify_after_post_title(); // Hook ?> 
		<?php endif; //post title ?>
		<?php if($themify->hide_meta != 'yes'): ?>
		
				<?php get_template_part( 'includes/caldol-file-meta-data' ); ?>

		<?php endif; //post meta ?>

		
	  <?php //CALDOL MODIFICATION

	//if('above' != $themify->media_position && !is_single()) get_template_part( 'includes/post-media', 'loop'); 
	if('above' != 'bogus-variable' && !is_single()) get_template_part( 'includes/post-media', 'loop'); 
	  
	  ?>
		
		
		<?php if($themify->display_content == 'excerpt'): ?>

			<?php the_excerpt(); ?>

			<?php if( themify_check('setting-excerpt_more') ) : ?>
				<p><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute('echo=0'); ?>" class="more-link"><?php echo themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify') ?></a><p>
			<?php endif; ?>
	
		<?php elseif($themify->display_content == 'none'): ?>
	
		<?php else: ?>
		
					<?php the_excerpt(); ?>

					
			<?php //the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>


			<?php 			
			echo "<div id='fileAttachments'>";
	  $attachmentCount = caldol_attachments_count($post->ID);
	  if( $attachmentCount == 1){
	  	echo "<p>Attachment: <ul>" . caldol_get_attachments_link_list($post->ID) . "</ul></p>";
	
	 }
	 elseif($attachmentCount > 1){
	 	echo "<p>" . $attachmentCount . " Attachments" ."<ol>" . caldol_get_attachments_link_list($post->ID) . "</ol></p>";
	 	
	 	 
	 }
	 
	 echo "</div>";	 
	 ?>
			<?php endif; //display content ?>
		
		<?php //edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>
	

	</div>
	<!-- /.post-content -->
	
	<?php themify_post_end(); // hook ?>
	
</article>
<?php themify_post_after(); // hook ?>
<?php $prevPostType = get_post_type();?>
<!-- /.post -->
