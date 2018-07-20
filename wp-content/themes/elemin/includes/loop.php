<?php if(!is_single()) { global $more; $more = 0; } //enable more link ?>
<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php themify_post_before(); //hook ?>
<!-- post -->
<article id="post-<?php the_id(); ?>" <?php post_class("post clearfix " . $themify->get_categories_as_classes(get_the_id())); ?>>
	<?php themify_post_start(); //hook ?>

	<span class="post-icon"></span><!-- /post-icon -->

	<!-- post-title -->
	<?php if($themify->hide_title != "yes"): ?>
		<?php themify_post_title(); ?>
	<?php endif; //post title ?>
	<!-- /post-title -->

	<!-- post-meta -->
	<p class="post-meta entry-meta">
		<?php if($themify->hide_date != "yes"): ?>
			<time datetime="<?php the_time('o-m-d') ?>" class="post-date entry-date updated"><?php echo get_the_date( apply_filters( 'themify_loop_date', '' ) ) ?></time>
		<?php endif; ?>

		<?php if($themify->hide_meta != 'yes'): ?>
				<span class="post-author"><em><?php _e( 'By', 'themify' ); ?></em> <?php echo themify_get_author_link(); ?></span>
				<span class="post-category"><em><?php _e( 'in', 'themify' ); ?></em> <?php the_category(', ') ?></span>
				<?php  if( !themify_get('setting-comments_posts') && ( comments_open() || get_comments_number() > 0 ) ) : ?>
					<span class="post-comment"><?php comments_popup_link(__('No Comments','themify'), __('1 Comment','themify'), __('% Comments','themify')); ?></span>
				<?php endif; //post comment ?>
				<?php the_tags(__(' <span class="post-tag"><em>Tags:</em> ','themify'), ', ', '</span>'); ?>
		<?php endif; ?>
	</p>
	<!-- /post-meta -->

	<?php get_template_part('includes/loop-' . $themify->get_format_template()); ?>

	<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>

    <?php themify_post_end(); //hook ?>
</article>
<!-- /post -->
<?php themify_post_after(); //hook ?>
