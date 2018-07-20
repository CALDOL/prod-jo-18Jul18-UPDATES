<?php 
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<!-- quote-content -->
<div class="quote-content">
	
	<div class="entry-content">

	<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>
	
		<?php the_excerpt(); ?>

			<?php if( themify_check('setting-excerpt_more') ) : ?>
				<p><a href="<?php the_permalink(); ?>" class="more-link"><?php echo themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify') ?></a></p>
			<?php endif; ?>
	
	<?php elseif ( 'none' == $themify->display_content && ! is_attachment() ) : ?>
	
	<?php else: ?>
	
		<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>
	
	<?php endif; //display content ?>

	</div><!-- /.entry-content -->
</div>
<!-- /quote-content -->

<?php echo $themify->get_quote_author(themify_get('quote_author'), themify_get('quote_author_link')); ?>