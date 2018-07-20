<?php global $themify; ?>

<p class="caldol-post-meta">
	<?php if($themify->hide_meta != 'yes'): ?>
	<!-- <span class="post-author"><?php the_author_posts_link() ?></span>
					<span class="separator">/</span>
					-->
	<?php endif; ?>

	<?php if($themify->hide_meta != 'yes'): ?>
	<p><span class="categoryList">Type: </span>
	<?php echo caldol_get_the_type_list( get_the_ID(), 'category', ' <span class="post-category">', ', ', '</span>' ); ?>

	<?php endif; ?>

	<?php if($themify->hide_meta != 'yes'): ?>
	<span class="categoryList">&nbsp;&nbsp;&nbsp;Community: </span>
	<?php echo caldol_get_the_community_list( get_the_ID(), 'category', ' <span class="post-category">', ', ', '</span>' ); ?>
	<br/>
	<?php endif; ?>

	<?php if($themify->hide_meta != 'yes'): ?>
	<span class="categoryList">Topics: </span>
	<?php echo caldol_get_the_topic_list( get_the_ID(), 'category', ' <span class="post-category">', ', ', '</span>' ); ?>
	<?php endif; ?>


	<?php if($themify->hide_meta != 'yes'): ?>
	  <br/><span class="categoryList">Tags: </span>
	<?php the_tags('<span class="caldol-glyph tags post-tag">', ', ', '</span>'); ?>
	<?php endif; ?>

	<br />
	<?php $tagList = explode(", ", bbp_get_topic_tag_names(get_the_ID()));

	    foreach($tagList as $term){
	   echo "<a href='discussion-tag/" . $term . "'>$term </a>";

}

	?>

	<?php if(false && $themify->hide_date != 'yes'): ?>

	<time datetime="<?php the_time('o-m-d') ?>" class="caldol-glyph large-post-date" pubdate>
	  <strong>Contributed:</strong> <?php the_time(apply_filters('themify_loop_date', 'M j, Y')) ?>
	</time>
	<?php endif; //post date ?>


	<?php  if(false && !themify_get('setting-comments_posts') && comments_open() && $themify->hide_meta != 'yes' ) : ?>
   <span class="caldol-glyph post-comment"><?php comments_popup_link( __( ' 0 comments', 'themify' ), __( ' 1 comment', 'themify' ), __( ' % comments', 'themify' ) ); ?>
	</span>
	<?php endif; ?>
	

	<?php if($themify->hide_meta != 'yes'):?>
	  <span class="post-author"><strong>Contributed by:</strong> <?php the_author_posts_link() ?> on <?php the_time(apply_filters('themify_loop_date', 'M j, Y')) ?> with <?php comments_popup_link( __( ' 0 comments', 'themify' ), __( ' 1 comment', 'themify' ), __( ' % comments', 'themify' ) ); ?></span>
	<?php endif; ?>
	
	<?php if(caldol_can_edit_file($post->post_author)){?>

	<span class="caldol-glyph edit"><a
		href="<?php echo site_url(); ?>/update-file?fileID=<?php the_ID() ?>">edit</a> </span>

	<?php }?>
<br/>
<p style="opacity:1.0;">
	<?php
         echo getPostViews(get_the_ID()) ;
         /* see if the user has liked this post  */
         $userLiked = hasLiked(get_the_ID(), wp_get_current_user()->ID);
         $userPied = hasPied(get_the_ID(), wp_get_current_user()->ID);
         ?>
					<input type="hidden" id='targetPostID' value='<?php the_ID();?>'/>
					<span id="likeCount_<?php echo get_the_ID(); ?>">&nbsp;&nbsp;&nbsp;<?php echo getPostLikes(get_the_ID());?>&nbsp;&nbsp;</span>
					<?php if ( current_user_can( 'manage_options' ) ) { ?>
					<span id="pieCount_<?php echo get_the_ID(); ?>">&nbsp;<?php echo getPostPies(get_the_ID());?>&nbsp;&nbsp;</span>
					<?php }?>
					<?php if(!$userLiked){?>
					<?php /**** OLD INPUT METHOD FOR LIKE AND PIE
<input type="image" value="<?php echo get_the_ID(); ?>" class="llikeButton lshowLikeButton llikeButton_<?php echo get_the_ID(); ?>" width="60px" height="30px" src="<?php echo get_stylesheet_directory_uri();?>/images/likePlus.png"/>

					<input type="image" value="<?php echo get_the_ID(); ?>" class="pieButton showPieButton pieButton_<?php echo get_the_ID(); ?>" title="Did you find this Positive, Inspiring, or Energizing (PIE)?. If so, click the button to let the author know that.  An email will be sent to the author to let them know." width="70px" height="18px" src="<?php echo get_stylesheet_directory_uri();?>/images/pie2.png"/>
***/?>

					<button value="<?php echo get_the_ID(); ?>" class="likeButton showLikeButton likeButton_<?php echo get_the_ID(); ?>" >Like</button>
						<?php } ?>
					<span class="hasLiked <?php echo $userLiked?'':'hideLiked'?>" id="hasLiked_<?php echo get_the_ID(); ?>">You like this&nbsp;&nbsp;&nbsp;</span>
					<?php if(!$userPied){?>
					<button value="<?php echo get_the_ID(); ?>" class="pieButton showPieButton pieButton_<?php echo get_the_ID(); ?>" title="Did you find this Positive, Inspiring, or Energizing (PIE)?. If so, click the button to let the author know that.  An email will be sent to the author to let them know.">PIE</button>

					<?php }?>
					<span class="hasPied <?php echo $userPied?'':'hidePied'?>" id="hasPied_<?php echo get_the_ID(); ?>">You sent some PIE!&nbsp;&nbsp;&nbsp;</span>
						</p>
</p>
<hr class="fileMetaSeparator"/>

