<!DOCTYPE html>
<?php 
//header("Access-Control-Allow-Origin: *");
?>
<html <?php language_attributes(); ?>>
<head>
<?php
/** Themify Default Variables
 @var object */
global $themify; ?>

<meta charset="<?php bloginfo( 'charset' ); ?>">
<script type="text/javascript">
  
  
	if ( 'undefined' !== typeof AudioPlayer ) {
		AudioPlayer.setup("<?php echo get_template_directory_uri(); ?>/player.swf", {
			width: '90%',
			transparentpagebg: 'yes'
		});
	}

</script>

	<!-- wp_header -->
	<?php wp_head(); ?>

</head>
  
  <?php 
/*
  	if(get_current_user_id() == 6){
 	phpinfo(); 
}

*/
	
	?>

<body <?php body_class(); ?>>
  
<?php
/*
$user_ID= get_current_user_id(); 

$currNonce = wp_create_nonce();

$os = array(1,2);
if (in_array($user_ID, $os)) {
  echo "<a href='/wp-login.php?action=switch_to_user&user_id=32&_wpnonce=" . $currNonce . "&redirect_to=https%3A%2F%2Fjuniorofficer.army.mil%2Fmembers%2Fjo-admin%2F'>BOLC Team</a>";
  echo "<a href='/wp-login.php?action=switch_to_user&user_id=28&_wpnonce=bc4631694c&redirect_to=https%3A%2F%2Fjuniorofficer.army.mil%2Fmembers%2Fjo-admin%2F'>CC Team</a>";
  echo "<a href='/wp-login.php?action=switch_to_user&user_id=29&_wpnonce=bc4631694c&redirect_to=https%3A%2F%2Fjuniorofficer.army.mil%2Fmembers%2Fjo-admin%2F'>PL Team</a>";
}
*/
  ?>
<?php themify_body_start(); //hook ?>
<div id="pagewrap" class="hfeed site <?php echo (defined('IS_DEVELOPMENT'))?'is-development':'';?>">
    <div id="headerwrap" <?php echo (defined('IS_DEVELOPMENT'))?'style="background-color: red;"':'';?>>
       
    	<?php themify_header_before(); //hook ?>
        <header id="header" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
<?php themify_header_start(); //hook ?>

            <div class="hgroup">
                <?php echo themify_logo_image('site_logo'); ?>

				<?php if ( $site_desc = get_bloginfo( 'description' ) ) : ?>
					<?php global $themify_customizer; ?>
			  <div id="site-description" class="site-description"><div id="site-logo-left"><img src="<?php echo site_url();?>/wp-content/uploads/2016/12/JO-logo-48x110-trans.png"></div><div id="site-title"><a href="/">Junior Officer Forum</a></div><div id="site-logo-right"><img src="<?php echo site_url();?>/wp-content/uploads/2016/12/caldol-globe-trans-110-110.png"></div></div>
				<?php endif; ?>

            </div>
	        <!-- /hgroup -->

            <!-- social-widget -->
            <div class="social-widget">

                <?php dynamic_sidebar('social-widget'); ?>

                <?php if(!themify_check('setting-exclude_rss')): ?>
                    <div class="rss"><a href="<?php if(themify_get('setting-custom_feed_url') != ""){ echo themify_get('setting-custom_feed_url'); } else { bloginfo('rss2_url'); } ?>">RSS</a></div>
                <?php endif ?>

            </div>
            <!-- /social-widget -->

            <div id="main-nav-wrap">
                <div id="menu-icon" class="mobile-button"></div>
                <nav itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">
                    <?php
					if ( function_exists( 'themify_custom_menu_nav' ) ) {
						themify_custom_menu_nav();
					} else {
						wp_nav_menu( array(
							'theme_location' => 'main-nav',
							'fallback_cb'    => 'themify_default_main_nav',
							'container'      => '',
							'menu_id'        => 'main-nav',
							'menu_class'     => 'main-nav'
						));
					}
					?>
                </nav>
                <!-- /main-nav -->
            </div>
            <!-- /#main-nav-wrap -->

            <?php if(!themify_check('setting-exclude_search_form')): ?>
				<div id="searchform-wrap">
					<div id="search-icon" class="mobile-button"></div>
						<?php get_search_form(); ?>
				</div>
			<?php endif ?>
            <!-- /#searchform-wrap -->

			<?php themify_header_end(); //hook ?>
        </header>
        <!-- /header -->
        <?php themify_header_after(); //hook ?>

    </div>
    <!-- /headerwrap -->

	<div id="body" class="clearfix">
	  
	  <?php

        if(is_page()){
		  echo "<!-- setpostview :" . get_the_ID() . ": -->";
	        setPostViews(get_the_ID());
        }
		?>
	  
	  <?php
/*
		$tracking = get_user_meta(get_current_user_id(),'_user_login_history',true);
		//var_dump($tracking);
		if($tracking != "" || $tracking.length > 0){
		    foreach ($tracking as $loginDate){

				$actualSeconds = ( (int)$loginDate);

				echo date("d/m/Y H:i", $actualSeconds) . '<br/>';
			}
		}
		*/
		?>
		
		<?php
		if(current_user_can('manage_options')){
      if(bp_is_user() && bbp_get_displayed_user_id() != null ){

          //var_dump(bbp_get_displayed_user_id());
          echo do_shortcode("[user-history userid=" . bbp_get_displayed_user_id() . "]");

      }
		}
      ?>
    <?php themify_layout_before(); //hook ?>
