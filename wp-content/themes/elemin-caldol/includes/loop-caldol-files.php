<?php if(!is_single()){ global $more; $more = 0; } //enable more link ?>

<?php 
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php  global $current_user;
        wp_get_current_user();
        
       ?>
<?php themify_post_before(); // hook ?>
<!-- child/includes/loop-caldol-files -->
<article id="post-<?php the_ID(); ?>" <?php post_class("post clearfix " . $themify->get_categories_as_classes(get_the_ID())); ?>>
	
	<?php themify_post_start(); // hook ?>
	
	<?php if(is_single()) get_template_part( 'includes/post-media', 'loop'); ?>
		
	<div class="loop-caldol-files post-content">

		<?php if($themify->hide_title != 'yes'): ?>
				<?php themify_before_post_title(); // Hook ?>

				<?php if($themify->unlink_title == 'yes'): ?>
		
				<h2 class="post-title">
					<?php the_title(); ?>
					<?php if(caldol_can_edit_file($post->post_author)){?>
					<div class="caldol-tags" style="font-size: .7em; font-weight: normal; color: silver;"> 
					     [<a href="/update-file?fileID=<?php the_ID() ?>">edit</a>]
					</div>
					<?php }?>					
				</h2>
		
				<?php else: ?>
				<h2 class="post-title">
					<a href="<?php echo themify_get_featured_image_link(); ?>"
						title="<?php the_title_attribute(); ?>"><?php the_title(); ?> </a>
						

				</h2>

				<?php endif; //unlink post title ?>
				
				
				<?php themify_after_post_title(); // Hook ?> 
		<?php endif; //post title ?>
		
		<!-- start caldol-file-meta-data -->
		<?php if($themify->hide_meta != 'yes'): ?>
		
				<?php get_template_part( 'includes/caldol-file-meta-data' ); ?>
		
		<?php endif; //post meta ?>
		<!-- end caldol-file-meta-data -->
									

		
		<?php if(!is_single()) get_template_part( 'includes/post-media', 'loop'); ?>
		
		
		<?php if($themify->display_content == 'excerpt'): ?>

		
			<div class="caldol-files-excerpt"> <div class="excerpt-shade">
			 <?php the_excerpt(); ?>
			</div>
			</div>

			<?php if( themify_check('setting-excerpt_more') ) : ?>
				<p><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute('echo=0'); ?>" class="more-link"><?php echo themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify') ?></a><p>
			<?php endif; ?>
	
		<?php elseif($themify->display_content == 'none'): ?>
	
		<?php else: ?>
		
		
		<?php //Tom checking for search
		
		if(is_search()): 
		
		?>
		
		<?php endif; ?>
					
			<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>
					
					
	<?php 

	echo "<div id='attachments_list'>";
	  $attachmentCount = caldol_attachments_count($post->ID);
					if( $attachmentCount > 0){
			echo "<p>" . $attachmentCount . " Attachment(s): <ul>" . caldol_get_attachments_link_list($post->ID) . "</ul></p>";
	echo "</div>";
	 }?>
		<?php endif; //display content ?>
		
		<?php //edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>
		
	</div>
	<!-- /.post-content -->
	
	<?php themify_post_end(); // hook ?>
	
</article>
<?php themify_post_after(); // hook ?>

<!-- /.post -->
