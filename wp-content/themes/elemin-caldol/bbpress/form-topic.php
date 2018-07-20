<?php

/**
* New/Edit Topic
*
* @package bbPress
* @subpackage Theme
*/


$postTopicList = '';
$postCommunityList = '';
$checked = '';
$currentTags = array();
?>
<!-- forum-topic -->



<?php if ( !bbp_is_single_forum() ) : ?>

<div id="bbpress-forums">

<?php bbp_breadcrumb(); ?>

<?php endif; ?>

<?php if ( bbp_is_topic_edit() ) : ?>

<?php bbp_topic_tag_list( bbp_get_topic_id() ); ?>

<?php bbp_single_topic_description( array( 'topic_id' => bbp_get_topic_id() ) ); ?>

<?php endif; ?>

<?php if ( bbp_current_user_can_access_create_topic_form() ) : ?>

<div id="new-topic-<?php bbp_topic_id(); ?>" class="bbp-topic-form">

<form id="new-post" name="new-post" method="post" action="<?php the_permalink(); ?>">

<?php do_action( 'bbp_theme_before_topic_form' ); ?>

<fieldset class="bbp-form">
<legend>

<?php
if ( bbp_is_topic_edit() )
printf( __( 'Now Editing &ldquo;%s&rdquo;', 'bbpress' ), bbp_get_topic_title() );
else{

$slug = get_post( $post )->post_name;

if($slug != 'start-discussion'){
echo "<h2 id='openNewDiscussion'>Create New Discussion<span id='newDiscussionToggle'></span></h2>";
//bbp_is_single_forum() ? printf( __( 'Create New Discussion in &ldquo;%s&rdquo;', 'bbpress' ), bbp_get_forum_title() ) : _e( 'Create New Discussion', 'bbpress' );
}
else{
echo "<h2 id='openNewDiscussion'>Start a New Discussion</h2>";

}
}
?>

</legend>
<?php if ( !bbp_is_topic_edit() ){?>



<div id="createNewDiscussionDiv">


<?php }?>

<?php //do_action( 'bbp_theme_before_topic_form_notices' ); ?>

<?php if ( !bbp_is_topic_edit() && bbp_is_forum_closed() ) : ?>

<div class="bbp-template-notice">
<p>
<?php _e( 'This forum is marked as closed to new topics, however your posting capabilities still allow you to do so.', 'bbpress' ); ?>
</p>
</div>

<?php endif; ?>

<?php if (false && current_user_can( 'unfiltered_html' ) ) : ?>

<div class="bbp-template-notice">
<p>
<?php _e( 'Your account has the ability to post unrestricted HTML content.', 'bbpress' ); ?>
</p>
</div>

<?php endif; ?>

<?php do_action( 'bbp_template_notices' ); ?>

<div>
<?php //if ( true || !bbp_is_topic_edit() ) :?>


<p>
<label for="bbp_forum_id"><?php _e( 'Choose a Location:', 'bbpress' ); ?> </label><br />
<?php

if(bbp_is_topic_edit()){

bbp_dropdown( array(
'show_none' => 'Choose a location', //__( '(No Topic)', 'bbpress' ),
'selected'  => bbp_get_form_topic_forum()

) );
}
else{
bbp_dropdown( array(
  //'show_none' => 'Choose a location', //__( '(No Topic)', 'bbpress' ),
'selected'  => bbp_get_forum_id() //bbp_get_form_topic_forum()

) );
}
?>

</p>
  
  
 <?php /**********  DROP DOWN TO CHOOSE "COMMUNITY"  **********/ ?>
  <fieldset class="toolFieldSet">

<label for="postCommunityList"><?php _e('Community:', 'framework') ?></label><span class="new-post-label-description">(Think of this as your audience.  Who are you trying to bring into this discussion?)</span><br/>
<div  class="new-discussion-checkboxes" style="margin-left: 15px;">
<?php $args = array(
'descendants_and_self'  => 0,
'selected_cats'         => false,
'popular_cats'          => false,
'walker'                => null,
'taxonomy'              => 'category',
'checked_ontop'         => true
);



$loop = 1;
//wp_terms_checklist( $post_id, $args );?>

<?php $parentTopicID =  (get_category_by_slug('cat-community'))-> term_id; ?>

<?php  $communityCategories = get_terms( 'category', array(
'orderby'    => 'name',
'hide_empty' => 0,
'parent' => $parentTopicID,

) );


if(bbp_is_topic_edit()){
  
 

  $currentTags = explode(", ", bbp_get_form_topic_tags());
  //print_r($currentTags);
 // echo "<br/>communityCategories<br/><br/>";// . print_r($communityCategories);
  }
  

foreach($communityCategories as $term){

if( bbp_is_topic_edit()){
if(in_array($term->name, $currentTags)){

  $checked = "checked='checked'";
}
else{
$checked = '';
}
}

else{
//might be postback
if(isset($_POST['postCommunityList'])){
if(in_array($term->name, $_POST['postCommunityList'])){
$checked = "checked='checked'";

}
else{
$checked='';
}
}
}
echo '<input type="checkbox"' . $checked . ' name="postCommunityList[]" value="' . $term->name . '">&nbsp;'. $term->name . '&nbsp;&nbsp;&nbsp;&nbsp;';
   if($loop++ % 3 == 0){
      //echo "<br/>";
   }
}
?>

</div>
</fieldset>
  
 <fieldset class="toolFieldSet">

<label for="postTopicList"><?php _e('Topic:', 'framework') ?></label><span class="new-post-label-description">(These are some common tags that will help like-minded people find your discussion when they search.  You can always add more below.)</span><br/>
<div  class="new-discussion-checkboxes" style="margin-left: 15px;">
<?php $args = array(
'descendants_and_self'  => 0,
'selected_cats'         => false,
'popular_cats'          => false,
'walker'                => null,
'taxonomy'              => 'category',
'checked_ontop'         => true
);



$loop = 1;
//wp_terms_checklist( $post_id, $args );?>

<?php $parentTopicID =  (get_category_by_slug('cat-topic'))-> term_id; ?>

<?php  $topicCategories = get_terms( 'category', array(
'orderby'    => 'name',
'hide_empty' => 0,
'parent' => $parentTopicID,

) );

  

foreach($topicCategories as $term){

if(bbp_is_topic_edit()){
if(in_array($term->name, $currentTags)){
$checked = "checked='checked'";
}

else{
$checked = '';
}
}
else{
//might be postback
if(isset($_POST['postTopicList'])){
if(in_array($term->name, $_POST['postTopicList'])){
$checked = "checked='checked'";

}
else{
$checked='';
}
}
}


echo '<input type="checkbox"' . $checked . ' name="postTopicList[]" value="' . $term->name . '">'. $term->name . '&nbsp;&nbsp;';
   if(false && $loop++ % 3 == 0)
      echo "<br/>";
}
?>

<?php //wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'postTopicList', 'orderby' => 'NAME', 'show_option_none' => 'No Topic', 'child_of' => 17, 'selected' => $category->parent)); ?>
</div>
</fieldset>


<?php //endif;?>
<?php if ( bbp_allow_topic_tags() && current_user_can( 'assign_topic_tags' ) ) : ?>

<?php do_action( 'bbp_theme_before_topic_form_tags' ); ?>

<p>
<label for="bbp_topic_tags"><?php _e( 'Topic Tags:', 'bbpress' ); ?>
</label><span class="new-post-label-description">(If you wanted people to find your discussion in a Google search, what terms would they use? Put them below.  There's no need to duplicate the tags you already checked above in Topics.)</span><br /> <input type="text"
value="<?php bbp_form_topic_tags(); ?>"
tabindex="<?php bbp_tab_index(); ?>" size="40"
name="bbp_topic_tags" id="bbp_topic_tags"
<?php disabled( bbp_is_topic_spam() ); ?> />
</p>

<?php do_action( 'bbp_theme_after_topic_form_tags' ); ?>

<?php endif; ?>

<?php bbp_get_template_part( 'form', 'anonymous' ); ?>

<?php do_action( 'bbp_theme_before_topic_form_title' ); ?>

<p>
<label for="bbp_topic_title"><?php printf( __( 'Discussion Title (Maximum Length: %d):', 'bbpress' ), bbp_get_title_max_length() ); ?>
</label><br /> <input type="text" id="bbp_topic_title"
value="<?php bbp_form_topic_title(); ?>"
tabindex="<?php bbp_tab_index(); ?>" size="40"
name="bbp_topic_title"
maxlength="<?php bbp_title_max_length(); ?>" />
</p>

<?php do_action( 'bbp_theme_after_topic_form_title' ); ?>

<?php do_action( 'bbp_theme_before_topic_form_content' ); ?>

<?php bbp_the_content( array( 'context' => 'topic' ) ); ?>

<?php do_action( 'bbp_theme_after_topic_form_content' ); ?>

<?php if ( ! ( bbp_use_wp_editor() || current_user_can( 'unfiltered_html' ) ) ) : ?>

<p class="form-allowed-tags">
<label><?php _e( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes:','bbpress' ); ?>
</label><br />
<code>
<?php bbp_allowed_tags(); ?>
</code>
</p>

<?php endif; ?>



<?php if ( !bbp_is_single_forum() ) : ?>

<?php do_action( 'bbp_theme_before_topic_form_forum' ); ?>
<?php
// CALDOL SET TO FALSE SO IT WON'T SHOW
// NOT SURE WHY THIS IS HERE SO I DIDN'T WANT
// TO REMOVE IT.
?>
<?php if(false && bbp_is_topic_edit()){?>
<p>
<label for="bbp_forum_id"><?php _e( 'Forum:', 'bbpress' ); ?> </label><br />
<?php
bbp_dropdown( array(
'show_none' => __( '(No Topic)', 'bbpress' ),
'selected'  => bbp_get_form_topic_forum()
) );
?>
</p>
<?php } // end if start-discussion?>
<?php do_action( 'bbp_theme_after_topic_form_forum' ); ?>

<?php endif; ?>

<?php if ( current_user_can( 'moderate' ) ) : ?>

<?php do_action( 'bbp_theme_before_topic_form_type' ); ?>
<div style="display:none;">
<p>

<label for="bbp_stick_topic"><?php _e( 'Topic Type:', 'bbpress' ); ?>
</label><br />

<?php bbp_form_topic_type_dropdown(); ?>

</p>

<?php do_action( 'bbp_theme_after_topic_form_type' ); ?>

<?php do_action( 'bbp_theme_before_topic_form_status' ); ?>

<p>

<label for="bbp_topic_status"><?php _e( 'Topic Status:', 'bbpress' ); ?>
</label><br />

<?php bbp_form_topic_status_dropdown(); ?>

</p>
</div>
<?php do_action( 'bbp_theme_after_topic_form_status' ); ?>

<?php endif; ?>

<?php if ( bbp_is_subscriptions_active() && !bbp_is_anonymous() && ( !bbp_is_topic_edit() || ( bbp_is_topic_edit() && !bbp_is_topic_anonymous() ) ) ) : ?>

<?php do_action( 'bbp_theme_before_topic_form_subscriptions' ); ?>

<p>
<input name="bbp_topic_subscription" id="bbp_topic_subscription"
type="checkbox" value="bbp_subscribe"
<?php bbp_form_topic_subscribed(); ?>
tabindex="<?php bbp_tab_index(); ?>" />

<?php if ( bbp_is_topic_edit() && ( bbp_get_topic_author_id() !== bbp_get_current_user_id() ) ) : ?>

<label for="bbp_topic_subscription"><?php _e( 'Notify the author of follow-up replies via email', 'bbpress' ); ?>
</label>

<?php else : ?>

<label for="bbp_topic_subscription"><?php _e( 'Notify me of follow-up replies via email', 'bbpress' ); ?>
</label>

<?php endif; ?>
</p>

<?php do_action( 'bbp_theme_after_topic_form_subscriptions' ); ?>

<?php endif; ?>

<?php if ( bbp_allow_revisions() && bbp_is_topic_edit() ) : ?>

<?php do_action( 'bbp_theme_before_topic_form_revisions' ); ?>

<fieldset class="bbp-form">
<legend>
<input name="bbp_log_topic_edit" id="bbp_log_topic_edit"
type="checkbox" value="1" <?php bbp_form_topic_log_edit(); ?>
tabindex="<?php bbp_tab_index(); ?>" /> <label
for="bbp_log_topic_edit"><?php _e( 'Keep a log of this edit:', 'bbpress' ); ?>
</label><br />
</legend>

<div>
<label for="bbp_topic_edit_reason"><?php printf( __( 'Optional reason for editing:', 'bbpress' ), bbp_get_current_user_name() ); ?>
</label><br /> <input type="text"
value="<?php bbp_form_topic_edit_reason(); ?>"
tabindex="<?php bbp_tab_index(); ?>" size="40"
name="bbp_topic_edit_reason" id="bbp_topic_edit_reason" />
</div>
</fieldset>

<?php do_action( 'bbp_theme_after_topic_form_revisions' ); ?>

<?php endif; ?>

<?php do_action( 'bbp_theme_before_topic_form_submit_wrapper' ); ?>

<div class="bbp-submit-wrapper">

<?php do_action( 'bbp_theme_before_topic_form_submit_button' ); ?>

<button type="submit" tabindex="<?php bbp_tab_index(); ?>"
id="bbp_topic_submit" name="bbp_topic_submit"
class="button submit">
<?php _e( 'Submit', 'bbpress' ); ?>
</button>

<?php do_action( 'bbp_theme_after_topic_form_submit_button' ); ?>

</div>

<?php do_action( 'bbp_theme_after_topic_form_submit_wrapper' ); ?>

</div>

<?php bbp_topic_form_fields(); ?>
<?php if ( !bbp_is_topic_edit() ){?>
</div>
<?php }?>
</fieldset>

<?php do_action( 'bbp_theme_after_topic_form' ); ?>

</form>
</div>
<!--  </div> -->
<?php elseif ( bbp_is_forum_closed() ) : ?>

<div id="no-topic-<?php bbp_topic_id(); ?>" class="bbp-no-topic">
<div class="bbp-template-notice">
<p><?php printf( __( 'The topic &#8216;%s&#8217; is closed to new discussions and replies.', 'bbpress' ), bbp_get_forum_title() ); ?></p>
</div>
</div>

<?php else : ?>

<div id="no-topic-<?php bbp_topic_id(); ?>" class="bbp-no-topic">
<div class="bbp-template-notice">
<p><?php is_user_logged_in() ? _e( 'You cannot create new topics.', 'bbpress' ) : _e( 'You must be logged in to create new topics.', 'bbpress' ); ?></p>
</div>
</div>

<?php endif; ?>

<?php if ( !bbp_is_single_forum() ) : ?>

</div>

<?php endif; ?>
