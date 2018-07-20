<?php
$widgets = get_option( "widget_text" );
$widgets[1002] = array (
  'title' => 'WordPress Gallery',
  'text' => '[gallery link="file" id="28" columns="4"]',
  'filter' => false,
);
update_option( "widget_text", $widgets );

$widgets = get_option( "widget_themify-links" );
$widgets[1003] = array (
  'title' => 'Follow Me',
  'category' => '24',
  'orderby' => 'id',
  'show_count' => '10',
  'show_thumb' => 'on',
  'show_name' => 'on',
  'show_desc' => NULL,
);
update_option( "widget_themify-links", $widgets );

$widgets = get_option( "widget_themify-list-pages" );
$widgets[1004] = array (
  'title' => 'Pages',
  'parent' => '0',
  'depth' => '1',
  'orderby' => 'post_title',
  'exclude' => '',
);
update_option( "widget_themify-list-pages", $widgets );

$widgets = get_option( "widget_themify-flickr" );
$widgets[1005] = array (
  'title' => 'Recent Photos',
  'username' => '52839779@N02',
  'show_count' => '8',
);
update_option( "widget_themify-flickr", $widgets );

$widgets = get_option( "widget_themify-recent-comments" );
$widgets[1006] = array (
  'title' => 'Recent Comments',
  'show_count' => '3',
  'show_avatar' => 'on',
  'avatar_size' => '32',
  'excerpt_length' => '60',
);
update_option( "widget_themify-recent-comments", $widgets );

$widgets = get_option( "widget_themify-social-links" );
$widgets[1007] = array (
  'title' => '',
  'show_link_name' => NULL,
  'open_new_window' => NULL,
  'icon_size' => 'icon-medium',
  'orientation' => 'horizontal',
);
update_option( "widget_themify-social-links", $widgets );

$widgets = get_option( "widget_themify-recent-comments" );
$widgets[1008] = array (
  'title' => 'Recent Comments',
  'show_count' => '3',
  'show_avatar' => 'on',
  'avatar_size' => '32',
  'excerpt_length' => '60',
);
update_option( "widget_themify-recent-comments", $widgets );

$widgets = get_option( "widget_themify-twitter" );
$widgets[1009] = array (
  'title' => 'Latest Tweets',
  'username' => 'themify',
  'show_count' => '2',
  'hide_timestamp' => NULL,
  'hide_url' => 'on',
  'show_follow' => 'on',
  'follow_text' => '→ Follow me',
  'include_retweets' => NULL,
  'exclude_replies' => NULL,
);
update_option( "widget_themify-twitter", $widgets );

$widgets = get_option( "widget_themify-flickr" );
$widgets[1010] = array (
  'title' => 'Recent Photos',
  'username' => '52839779@N02',
  'show_count' => '8',
  'show_link' => NULL,
);
update_option( "widget_themify-flickr", $widgets );



$sidebars_widgets = array (
  'sidebar-main' => 
  array (
    0 => 'text-1002',
  ),
  'sidebar-main-2a' => 
  array (
    0 => 'themify-links-1003',
  ),
  'sidebar-main-2b' => 
  array (
    0 => 'themify-list-pages-1004',
  ),
  'sidebar-main-3' => 
  array (
    0 => 'themify-flickr-1005',
    1 => 'themify-recent-comments-1006',
  ),
  'social-widget' => 
  array (
    0 => 'themify-social-links-1007',
  ),
  'footer-widget-1' => 
  array (
    0 => 'themify-recent-comments-1008',
  ),
  'footer-widget-2' => 
  array (
    0 => 'themify-twitter-1009',
  ),
  'footer-widget-3' => 
  array (
    0 => 'themify-flickr-1010',
  ),
); 
update_option( "sidebars_widgets", $sidebars_widgets );

$menu_locations = array();
$menu = get_terms( "nav_menu", array( "slug" => "footer-nav" ) );
if( is_array( $menu ) && ! empty( $menu ) ) $menu_locations["main-nav"] = $menu[0]->term_id;
$menu = get_terms( "nav_menu", array( "slug" => "footer-nav" ) );
if( is_array( $menu ) && ! empty( $menu ) ) $menu_locations["footer-nav"] = $menu[0]->term_id;
set_theme_mod( "nav_menu_locations", $menu_locations );


$homepage = get_posts( array( 'name' => 'home', 'post_type' => 'page' ) );
	if( is_array( $homepage ) && ! empty( $homepage ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $homepage[0]->ID );
	}
	ob_start(); ?>a:156:{s:15:"setting-favicon";s:0:"";s:23:"setting-custom_feed_url";s:0:"";s:19:"setting-header_html";s:0:"";s:19:"setting-footer_html";s:0:"";s:23:"setting-search_settings";s:0:"";s:21:"setting-feed_settings";s:0:"";s:24:"setting-webfonts_subsets";s:0:"";s:21:"setting-editor-gfonts";s:131:"["Cinzel","EB Garamond","Istok Web","Jura","Kameron","Lato","Lustria","Muli","Nunito","Open Sans","Oranienbaum","Oswald","PT Sans"]";s:22:"setting-default_layout";s:8:"sidebar1";s:27:"setting-default_post_layout";s:9:"list-post";s:30:"setting-default_layout_display";s:7:"content";s:25:"setting-default_more_text";s:4:"More";s:21:"setting-index_orderby";s:4:"date";s:19:"setting-index_order";s:4:"DESC";s:26:"setting-default_post_title";s:0:"";s:33:"setting-default_unlink_post_title";s:0:"";s:25:"setting-default_post_meta";s:0:"";s:25:"setting-default_post_date";s:0:"";s:26:"setting-default_post_image";s:0:"";s:33:"setting-default_unlink_post_image";s:0:"";s:31:"setting-image_post_feature_size";s:5:"blank";s:24:"setting-image_post_width";s:0:"";s:25:"setting-image_post_height";s:0:"";s:24:"setting-image_post_align";s:0:"";s:32:"setting-default_page_post_layout";s:8:"sidebar1";s:31:"setting-default_page_post_title";s:0:"";s:38:"setting-default_page_unlink_post_title";s:0:"";s:30:"setting-default_page_post_meta";s:0:"";s:30:"setting-default_page_post_date";s:0:"";s:31:"setting-default_page_post_image";s:0:"";s:38:"setting-default_page_unlink_post_image";s:0:"";s:38:"setting-image_post_single_feature_size";s:5:"blank";s:31:"setting-image_post_single_width";s:0:"";s:32:"setting-image_post_single_height";s:0:"";s:31:"setting-image_post_single_align";s:0:"";s:27:"setting-default_page_layout";s:8:"sidebar1";s:23:"setting-hide_page_title";s:0:"";s:24:"setting-gallery_lightbox";s:8:"lightbox";s:19:"setting-entries_nav";s:8:"numbered";s:24:"setting-homepage_welcome";s:79:"Welcome to Elemin theme demo. Here you can insert your custom greeting message.";s:29:"setting-header_slider_enabled";s:2:"on";s:29:"setting-header_slider_display";s:5:"posts";s:36:"setting-header_slider_posts_category";s:1:"0";s:34:"setting-header_slider_posts_slides";s:1:"5";s:37:"setting-header_slider_default_display";s:7:"excerpt";s:32:"setting-header_slider_hide_title";s:0:"";s:38:"setting-header_slider_images_one_title";s:0:"";s:37:"setting-header_slider_images_one_link";s:0:"";s:38:"setting-header_slider_images_one_image";s:0:"";s:38:"setting-header_slider_images_two_title";s:0:"";s:37:"setting-header_slider_images_two_link";s:0:"";s:38:"setting-header_slider_images_two_image";s:0:"";s:40:"setting-header_slider_images_three_title";s:0:"";s:39:"setting-header_slider_images_three_link";s:0:"";s:40:"setting-header_slider_images_three_image";s:0:"";s:39:"setting-header_slider_images_four_title";s:0:"";s:38:"setting-header_slider_images_four_link";s:0:"";s:39:"setting-header_slider_images_four_image";s:0:"";s:39:"setting-header_slider_images_five_title";s:0:"";s:38:"setting-header_slider_images_five_link";s:0:"";s:39:"setting-header_slider_images_five_image";s:0:"";s:38:"setting-header_slider_images_six_title";s:0:"";s:37:"setting-header_slider_images_six_link";s:0:"";s:38:"setting-header_slider_images_six_image";s:0:"";s:40:"setting-header_slider_images_seven_title";s:0:"";s:39:"setting-header_slider_images_seven_link";s:0:"";s:40:"setting-header_slider_images_seven_image";s:0:"";s:40:"setting-header_slider_images_eight_title";s:0:"";s:39:"setting-header_slider_images_eight_link";s:0:"";s:40:"setting-header_slider_images_eight_image";s:0:"";s:39:"setting-header_slider_images_nine_title";s:0:"";s:38:"setting-header_slider_images_nine_link";s:0:"";s:39:"setting-header_slider_images_nine_image";s:0:"";s:38:"setting-header_slider_images_ten_title";s:0:"";s:37:"setting-header_slider_images_ten_link";s:0:"";s:38:"setting-header_slider_images_ten_image";s:0:"";s:29:"setting-header_slider_visible";s:1:"4";s:26:"setting-header_slider_auto";s:1:"0";s:28:"setting-header_slider_scroll";s:1:"1";s:27:"setting-header_slider_speed";s:4:"1000";s:26:"setting-header_slider_wrap";s:3:"yes";s:27:"setting-header_slider_width";s:0:"";s:28:"setting-header_slider_height";s:0:"";s:29:"setting-footer_slider_enabled";s:2:"on";s:29:"setting-footer_slider_display";s:5:"posts";s:36:"setting-footer_slider_posts_category";s:1:"0";s:34:"setting-footer_slider_posts_slides";s:1:"5";s:37:"setting-footer_slider_default_display";s:4:"none";s:32:"setting-footer_slider_hide_title";s:0:"";s:38:"setting-footer_slider_images_one_title";s:0:"";s:37:"setting-footer_slider_images_one_link";s:0:"";s:38:"setting-footer_slider_images_one_image";s:0:"";s:38:"setting-footer_slider_images_two_title";s:0:"";s:37:"setting-footer_slider_images_two_link";s:0:"";s:38:"setting-footer_slider_images_two_image";s:0:"";s:40:"setting-footer_slider_images_three_title";s:0:"";s:39:"setting-footer_slider_images_three_link";s:0:"";s:40:"setting-footer_slider_images_three_image";s:0:"";s:39:"setting-footer_slider_images_four_title";s:0:"";s:38:"setting-footer_slider_images_four_link";s:0:"";s:39:"setting-footer_slider_images_four_image";s:0:"";s:39:"setting-footer_slider_images_five_title";s:0:"";s:38:"setting-footer_slider_images_five_link";s:0:"";s:39:"setting-footer_slider_images_five_image";s:0:"";s:38:"setting-footer_slider_images_six_title";s:0:"";s:37:"setting-footer_slider_images_six_link";s:0:"";s:38:"setting-footer_slider_images_six_image";s:0:"";s:40:"setting-footer_slider_images_seven_title";s:0:"";s:39:"setting-footer_slider_images_seven_link";s:0:"";s:40:"setting-footer_slider_images_seven_image";s:0:"";s:40:"setting-footer_slider_images_eight_title";s:0:"";s:39:"setting-footer_slider_images_eight_link";s:0:"";s:40:"setting-footer_slider_images_eight_image";s:0:"";s:39:"setting-footer_slider_images_nine_title";s:0:"";s:38:"setting-footer_slider_images_nine_link";s:0:"";s:39:"setting-footer_slider_images_nine_image";s:0:"";s:38:"setting-footer_slider_images_ten_title";s:0:"";s:37:"setting-footer_slider_images_ten_link";s:0:"";s:38:"setting-footer_slider_images_ten_image";s:0:"";s:29:"setting-footer_slider_visible";s:1:"4";s:26:"setting-footer_slider_auto";s:1:"0";s:28:"setting-footer_slider_scroll";s:1:"1";s:27:"setting-footer_slider_speed";s:3:"300";s:26:"setting-footer_slider_wrap";s:3:"yes";s:27:"setting-footer_slider_width";s:0:"";s:28:"setting-footer_slider_height";s:0:"";s:22:"setting-footer_widgets";s:17:"footerwidget-3col";s:24:"setting-footer_text_left";s:0:"";s:25:"setting-footer_text_right";s:0:"";s:27:"setting-global_feature_size";s:5:"large";s:22:"setting-link_icon_type";s:10:"image-icon";s:32:"setting-link_type_themify-link-0";s:10:"image-icon";s:33:"setting-link_title_themify-link-0";s:7:"Twitter";s:32:"setting-link_link_themify-link-0";s:26:"http://twitter.com/themify";s:31:"setting-link_img_themify-link-0";s:92:"http://themify.me/demo/themes/elemin/wp-content/themes/elemin/themify/img/social/twitter.png";s:32:"setting-link_type_themify-link-1";s:10:"image-icon";s:33:"setting-link_title_themify-link-1";s:8:"Facebook";s:32:"setting-link_link_themify-link-1";s:31:"http://www.facebook.com/themify";s:31:"setting-link_img_themify-link-1";s:93:"http://themify.me/demo/themes/elemin/wp-content/themes/elemin/themify/img/social/facebook.png";s:32:"setting-link_type_themify-link-2";s:10:"image-icon";s:33:"setting-link_title_themify-link-2";s:7:"Google+";s:32:"setting-link_link_themify-link-2";s:0:"";s:31:"setting-link_img_themify-link-2";s:96:"http://themify.me/demo/themes/elemin/wp-content/themes/elemin/themify/img/social/google-plus.png";s:32:"setting-link_type_themify-link-3";s:10:"image-icon";s:33:"setting-link_title_themify-link-3";s:7:"YouTube";s:32:"setting-link_link_themify-link-3";s:38:"https://www.youtube.com/user/themifyme";s:31:"setting-link_img_themify-link-3";s:92:"http://themify.me/demo/themes/elemin/wp-content/themes/elemin/themify/img/social/youtube.png";s:32:"setting-link_type_themify-link-4";s:10:"image-icon";s:33:"setting-link_title_themify-link-4";s:9:"Pinterest";s:32:"setting-link_link_themify-link-4";s:0:"";s:31:"setting-link_img_themify-link-4";s:94:"http://themify.me/demo/themes/elemin/wp-content/themes/elemin/themify/img/social/pinterest.png";s:22:"setting-link_field_ids";s:171:"{"themify-link-0":"themify-link-0","themify-link-1":"themify-link-1","themify-link-2":"themify-link-2","themify-link-3":"themify-link-3","themify-link-4":"themify-link-4"}";s:23:"setting-link_field_hash";s:1:"5";s:30:"setting-page_builder_is_active";s:6:"enable";s:23:"setting-hooks_field_ids";s:2:"[]";s:4:"skin";s:0:"";}<?php $themify_data = ob_get_clean();
	themify_set_data( unserialize( $themify_data ) );
	?>