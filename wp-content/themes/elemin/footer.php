<?php
/** Themify Default Variables
 @var object */
	global $themify; ?>
	
	<?php get_template_part( 'includes/footer-slider'); ?>
		
	<?php themify_layout_after(); //hook ?>
    </div>
	<!-- /body -->
	
    <div id="footerwrap">
    	
        <?php themify_footer_before(); //hook ?>
		<footer id="footer" class="clearfix" itemscope="itemscope" itemtype="https://schema.org/WPFooter">
        	<?php themify_footer_start(); //hook ?>

			<?php get_template_part( 'includes/footer-widgets'); ?>
			
			<?php echo themify_logo_image('footer_logo', 'footer-logo'); ?>
			<!-- /footer-logo -->

			<?php if (function_exists('wp_nav_menu')) {
				wp_nav_menu(array('theme_location' => 'footer-nav' , 'fallback_cb' => '' , 'container'  => '' , 'menu_id' => 'footer-nav' , 'menu_class' => 'footer-nav')); 
			} ?>

			<div class="footer-text clearfix">
				<?php themify_the_footer_text(); ?>
				<?php themify_the_footer_text('right'); ?>
			</div>
			<!-- /footer-text --> 

			<p class="back-top"><a href="#header">&uarr; <span><?php _e('Back to top', 'themify'); ?></span></a></p>

			<?php themify_footer_end(); //hook ?>
		</footer>
		<!-- /footer -->
        <?php themify_footer_after(); //hook ?>
        
	</div>
    <!--/footerwrap -->
	
</div>
<!-- /pagewrap -->

<?php
/**
 *  Stylesheets and Javascript files are enqueued in theme-functions.php
 */
?>

<?php themify_body_end(); // hook ?>
<!-- wp_footer -->
<?php wp_footer(); ?>

</body>
</html>