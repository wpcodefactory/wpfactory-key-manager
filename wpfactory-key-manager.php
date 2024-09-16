<?php

if ( ! function_exists( 'wpf_key_manager' ) ) {
	/**
	 * Returns the main instance of Alg_WPCodeFactory_Helper to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function wpf_key_manager() {

		defined( 'ABSPATH' ) || exit;

		defined( 'WPFKM_UPDATE_SERVER' ) || define( 'WPFKM_UPDATE_SERVER', 'https://wpfactory.com' );

		defined( 'WPFKM_VERSION' ) || define( 'WPFKM_VERSION', '1.7.1' );

		defined( 'WPFKM_FILE' ) || define( 'WPFKM_FILE', __FILE__ );

		require_once( 'includes/class-wpfkm.php' );

		return WPFKM::instance();
	}
}