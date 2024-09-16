<?php
/**
 * WPFactory Key Manager - Admin Site Key Functions
 *
 * @version 1.0.0
 * @since   1.0.0
 *
 * @author  WPFactory.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wpfkm_get_site_key' ) ) {
	/**
	 * wpfkm_get_site_key.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function wpfkm_get_site_key( $item_slug ) {
		$keys = get_option( 'alg_site_keys', array() );
		return ( isset( $keys[ $item_slug ] ) ? trim( $keys[ $item_slug ] ) : '' );
	}
}

if ( ! function_exists( 'wpfkm_update_site_key_status' ) ) {
	/**
	 * wpfkm_update_site_key_status.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function wpfkm_update_site_key_status( $item_slug, $server_response, $client_data = '' ) {
		$statuses = get_option( 'alg_site_keys_statuses', array() );
		if ( in_array( $client_data, array( 'NO_RESPONSE', 'SERVER_ERROR' ) ) && wpfkm_is_site_key_valid( $item_slug ) ) {
			// we don't want to overwrite valid licence response with server errors
			return;
		}
		$statuses[ $item_slug ] = array(
			'server_response' => $server_response,
			'client_data'     => $client_data,
			'time_checked'    => time(),
		);
		update_option( 'alg_site_keys_statuses', $statuses );
	}
}

if ( ! function_exists( 'wpfkm_get_site_key_status' ) ) {
	/**
	 * wpfkm_get_site_key_status.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function wpfkm_get_site_key_status( $item_slug ) {
		$statuses = get_option( 'alg_site_keys_statuses', array() );
		return ( isset( $statuses[ $item_slug ] ) ? $statuses[ $item_slug ] : false );
	}
}

if ( ! function_exists( 'wpfkm_is_site_key_valid' ) ) {
	/**
	 * wpfkm_is_site_key_valid.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function wpfkm_is_site_key_valid( $item_slug ) {
		if ( false !== ( $site_key_status = wpfkm_get_site_key_status( $item_slug ) ) ) {
			return ( isset( $site_key_status['server_response']->status ) && $site_key_status['server_response']->status );
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'wpfkm_get_site_key_status_message' ) ) {
	/**
	 * wpfkm_get_site_key_status_message.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) `SERVER_ERROR`: not used?
	 * @todo    (dev) No key set: `sprintf( __( 'Key can be set <a href="%s">here</a>.', 'wpf-key-manager' ), admin_url( 'options-general.php?page=wpfkm&item_slug=' . $item_slug ) )`
	 * @todo    (dev) check `false === $site_key_status && '' == wpfkm_get_site_key( $item_slug )`
	 */
	function wpfkm_get_site_key_status_message( $item_slug ) {
		$site_key_status = wpfkm_get_site_key_status( $item_slug );
		if ( false === $site_key_status && '' == wpfkm_get_site_key( $item_slug ) ) {
			$site_key_status = array();
			$site_key_status['client_data'] = 'EMPTY_SITE_KEY';
		}
		if ( isset( $site_key_status['server_response']->error->message ) ) {
			return $site_key_status['server_response']->error->message;
		} else {
			if ( isset( $site_key_status['client_data'] ) ) {
				switch ( $site_key_status['client_data'] ) {
					case 'EMPTY_SITE_KEY':
						return __( 'No key set.', 'wpf-key-manager' ) . ' ' .
							sprintf( __( 'To get the key, please visit <a target="_blank" href="%s">your account page at %s</a>.', 'wpf-key-manager' ),
								wpf_key_manager()->update_server . '/my-account/downloads/', wpf_key_manager()->update_server_text );
					case 'NO_RESPONSE':
						return sprintf( __( 'No response from server. Please <a href="%s">try again</a> later.', 'wpf-key-manager' ), add_query_arg( 'wpfkm_check_item_site_key', $item_slug ) );
					case 'SERVER_ERROR':
						return sprintf( __( 'Server error. Please <a href="%s">try again</a> later.', 'wpf-key-manager' ), add_query_arg( 'wpfkm_check_item_site_key', $item_slug ) );
				}
			}
			return __( 'Error: Unexpected error.', 'wpf-key-manager' );
		}
	}
}

if ( ! function_exists( 'wpfkm_check_site_key' ) ) {
	/**
	 * wpfkm_check_site_key.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function wpfkm_check_site_key( $item_slug ) {
		if ( '' != ( $site_key = wpfkm_get_site_key( $item_slug ) ) ) {
			$url = add_query_arg( array(
				'check_site_key' => $site_key,
				'item_slug'      => $item_slug,
				'site_url'       => wpf_key_manager()->site_url,
			), wpf_key_manager()->update_server );
			if ( $response = wpf_key_manager()->get_response_from_url( $url ) ) {
				$server_response = json_decode( $response );
				$client_data     = '';
			} else {
				$server_response = array();
				$client_data     = 'NO_RESPONSE';
			}
		} else {
			$server_response = array();
			$client_data     = 'EMPTY_SITE_KEY';
		}
		wpfkm_update_site_key_status( $item_slug, $server_response, $client_data );
	}
}
