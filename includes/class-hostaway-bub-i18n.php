<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://www.buildupbookings.com/
 * @since      1.0.0
 *
 * @package    Hostaway_Bub
 * @subpackage Hostaway_Bub/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Hostaway_Bub
 * @subpackage Hostaway_Bub/includes
 * @author     Braudy Pedrosa <braudy@buildupbookings.com>
 */
class Hostaway_Bub_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'hostaway-bub',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
