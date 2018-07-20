<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://jo.army.mil
 * @since      1.0.0
 *
 * @package    Caldol_Mega_Menu
 * @subpackage Caldol_Mega_Menu/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Caldol_Mega_Menu
 * @subpackage Caldol_Mega_Menu/includes
 * @author     Thomas Morel <thomas.morel@usma.edu>
 */
class Caldol_Mega_Menu_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'caldol-mega-menu',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
