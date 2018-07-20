<?php

/**
 * Single Topic Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>
<?php //echo get_stylesheet_directory_uri() . '/media-queries.css'; ?>
<!-- child/content-single-topic -->
<div id="bbpress-forums">

	<?php bbp_breadcrumb(); ?>
	<?php do_action( 'bbp_template_before_single_topic' ); ?>

	<?php if ( post_password_required() ) : ?>

		<?php bbp_get_template_part( 'form', 'protected' ); ?>

	<?php else : ?>
<?php if( function_exists('ADDTOANY_SHARE_SAVE_ICONS') ) {echo '<div id="shareLink"><div class="innerDiv">';  ADDTOANY_SHARE_SAVE_ICONS(); } if( function_exists('ADDTOANY_SHARE_SAVE_BUTTON') ) { ADDTOANY_SHARE_SAVE_BUTTON(); echo '</div></div>'; } ?>
	
		<?php bbp_topic_tag_list(); ?>

		<?php // CALDOL CHANGE -- REMOVE THE DESCRIPTION BLOCK ?>
		<?php bbp_single_topic_description(); ?>

<!-- before single-topic-lead -->			<?php if ( bbp_show_lead_topic() ) : ?>
<!--  inside lead_topic -->
		<?php bbp_get_template_part( 'content', 'single-topic-lead' ); ?> 
		<?php endif; ?>
<!--  after single-topic-lead -->
		
		<?php if ( bbp_has_replies() ) : ?>

			<?php bbp_get_template_part( 'pagination', 'replies' ); ?>

			<?php bbp_get_template_part( 'loop',       'replies' ); ?>

			<?php bbp_get_template_part( 'pagination', 'replies' ); ?>

		<?php endif; ?>

		<?php bbp_get_template_part( 'form', 'reply' ); ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_single_topic' ); ?>

</div>
