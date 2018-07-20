<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://jo.army.mil
 * @since      1.0.0
 *
 * @package    Caldol_Mega_Menu
 * @subpackage Caldol_Mega_Menu/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Caldol_Mega_Menu
 * @subpackage Caldol_Mega_Menu/public
 * @author     Thomas Morel <thomas.morel@usma.edu>
 */
class Caldol_Mega_Menu_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Caldol_Mega_Menu_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Caldol_Mega_Menu_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/caldol-mega-menu-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Caldol_Mega_Menu_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Caldol_Mega_Menu_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/caldol-mega-menu-public.js', array( 'jquery' ), $this->version, false );

		//set a variable so that the path for this plugin's "public" folder can be used in the script so images can be used.
        $wppb_custom = array( 'public_template_url' => plugin_dir_url( __FILE__) );
        wp_localize_script( $this->plugin_name, 'wppb_custom', $wppb_custom );
	}



public function display_mega_menu(){

	    if(is_user_logged_in()) {
            include('partials/footer-mega-menu.inc');
        }
        else{
	        include('partials/footer-mega-menu-non-member.inc');
        }
    }



}
