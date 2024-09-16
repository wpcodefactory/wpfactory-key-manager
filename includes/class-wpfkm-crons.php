<?php
/**
 * WPF Key Manager - Admin - Crons
 *
 * @version 1.5.8
 * @since   1.0.0
 *
 * @author  WPFactory.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPFKM_Crons' ) ) :

class WPFKM_Crons {

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) unschedule events?
	 */
	function __construct() {

		// Checks sites keys.
		add_action( 'init',                 array( $this, 'schedule_check_sites_keys' ) );
		add_action( 'admin_init',           array( $this, 'schedule_check_sites_keys' ) );
		add_action( 'wpfkm_check_sites_keys', array( $this, 'check_sites_keys' ) );

		// Gets plugins list.
		add_action( 'init',                 array( $this, 'schedule_get_plugins_list' ) );
		add_action( 'admin_init',           array( $this, 'schedule_get_plugins_list' ) );
		add_action( 'wpfkm_get_plugins_list', array( $this, 'get_plugins_list' ) );

		// Gets themes list.
		add_action( 'init',                 array( $this, 'schedule_get_themes_list' ) );
		add_action( 'admin_init',           array( $this, 'schedule_get_themes_list' ) );
		add_action( 'wpfkm_get_themes_list',  array( $this, 'get_themes_list' ) );

	}

	/**
	 * schedule_check_sites_keys.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function schedule_check_sites_keys() {
		$event_timestamp = wp_next_scheduled( 'wpfkm_check_sites_keys', array( 'daily' ) );
		update_option( 'wpfkm_check_sites_keys_cron_time_schedule', $event_timestamp );
		if ( ! $event_timestamp ) {
			wp_schedule_event( time(), 'daily', 'wpfkm_check_sites_keys', array( 'daily' ) );
		}
	}

	/**
	 * check_sites_keys.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function check_sites_keys( $interval ) {
		update_option( 'wpfkm_check_sites_keys_cron_time_last_run', time() );
		$items = wpf_key_manager()->plugins_updater->items_to_update;
		foreach ( $items as $item_slug ) {
			wpfkm_check_site_key( $item_slug );
		}
	}

	/**
	 * schedule_get_plugins_list.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function schedule_get_plugins_list() {
		$event_timestamp = wp_next_scheduled( 'wpfkm_get_plugins_list', array( 'daily' ) );
		update_option( 'wpfkm_get_plugins_list_cron_time_schedule', $event_timestamp );
		if ( ! $event_timestamp ) {
			wp_schedule_event( time(), 'daily', 'wpfkm_get_plugins_list', array( 'daily' ) );
		}
	}

	/**
	 * get_plugins_list.
	 *
	 * @version 1.5.7
	 * @since   1.0.0
	 */
	function get_plugins_list() {
		update_option( 'wpfkm_get_plugins_list_cron_time_last_run', time() );
		$url = add_query_arg( array( 'wpfkm_get_plugins_list' => '' ), wpf_key_manager()->update_server );
		if ( ( $response = wpf_key_manager()->get_response_from_url( $url ) ) ) {
			update_option( 'wpf_key_manager_plugins', json_decode( $response ) );
		}
	}

	/**
	 * schedule_get_themes_list.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function schedule_get_themes_list() {
		$event_timestamp = wp_next_scheduled( 'wpfkm_get_themes_list', array( 'daily' ) );
		update_option( 'wpfkm_get_themes_list_cron_time_schedule', $event_timestamp );
		if ( ! $event_timestamp ) {
			wp_schedule_event( time(), 'daily', 'wpfkm_get_themes_list', array( 'daily' ) );
		}
	}

	/**
	 * get_themes_list.
	 *
	 * @version 1.5.8
	 * @since   1.1.0
	 */
	function get_themes_list() {
		update_option( 'wpfkm_get_themes_list_cron_time_last_run', time() );
		$url = add_query_arg( array( 'wpfkm_get_themes_list' => '' ), wpf_key_manager()->update_server );
		if ( ( $response = wpf_key_manager()->get_response_from_url( $url ) ) ) {
			update_option( 'wpf_key_manager_themes', json_decode( $response ) );
		}
	}

}

endif;

return new WPFKM_Crons();
