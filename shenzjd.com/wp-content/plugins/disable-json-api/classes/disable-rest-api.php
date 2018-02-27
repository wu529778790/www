<?php

/**
 * Disable_REST_API class
 *
 * Most of the work is done in here
 */
class Disable_REST_API {

	const MENU_SLUG = 'disable_rest_api_settings';
	const CAPABILITY = 'manage_options';

	/**
	 * Stores 'disable-json-api/disable-json-api.php' typically
	 *
	 * @var string
	 */
	private $base_file_path;


	/**
	 * Disable_REST_API constructor.
	 *
	 * @param $path
	 */
	public function __construct( $path ) {

		// Set variable so the class knows how to reference the plugin
		$this->base_file_path = plugin_basename( $path );

		add_action( 'admin_menu', array( &$this, 'define_admin_link' ) );

		add_filter( 'rest_authentication_errors', array( &$this, 'whitelist_routes' ), 20 );

	}


	/**
	 * Checks for a current route being requested, and processes the whitelist
	 *
	 * @param $access
	 *
	 * @return WP_Error|null|boolean
	 */
	public function whitelist_routes( $access ) {

		// Return current value of $access and skip all plugin functionality
		if ( $this->allow_rest_api() ) {
			return $access;
		}

		$current_route = $this->get_current_route();

		if ( ! empty( $current_route ) && ! $this->is_whitelisted( $current_route ) ) {
			return $this->get_wp_error( $access );
		}

		return $access;

	}


	/**
	 * Current REST route getter.
	 *
	 * @return string
	 */
	private function get_current_route() {
		$rest_route = $GLOBALS['wp']->query_vars['rest_route'];

		return ( empty( $rest_route ) || '/' == $rest_route ) ?
			$rest_route :
			untrailingslashit( $rest_route );
	}


	/**
	 * Checks a route for whether it belongs to the whitelist
	 *
	 * @param $currentRoute
	 *
	 * @return boolean
	 */
	private function is_whitelisted( $currentRoute ) {

		return array_reduce( $this->get_route_whitelist_option(), function ( $isMatched, $pattern ) use ( $currentRoute ) {
			return $isMatched || (bool) preg_match( '@^' . htmlspecialchars_decode( $pattern ) . '$@i', $currentRoute );
		}, false );

	}


	/**
	 * Get `DRA_route_whitelist` option array from database
	 *
	 * @return array
	 */
	private function get_route_whitelist_option() {

		return (array) get_option( 'DRA_route_whitelist', array() );

	}


	/**
	 * Add a menu
	 *
	 * @return void
	 */
	public function define_admin_link() {

		add_options_page( esc_html__( 'Disable REST API Settings', 'disable-json-api' ), esc_html__( 'Disable REST API', 'disable-json-api' ), self::CAPABILITY, self::MENU_SLUG, array(
			&$this,
			'settings_page'
		) );
		add_filter( "plugin_action_links_$this->base_file_path", array( &$this, 'settings_link' ) );

	}


	/**
	 * Add Settings Link to plugins page
	 *
	 * @param $links
	 *
	 * @return array
	 */
	public function settings_link( $links ) {

		$settings_url  = menu_page_url( self::MENU_SLUG, false );
		$settings_link = "<a href='$settings_url'>" . esc_html__( "Settings", "disable-json-api" ) . "</a>";
		array_unshift( $links, $settings_link );

		return $links;
	}


	/**
	 * Menu Callback
	 *
	 * @return void
	 */
	public function settings_page() {

		$this->maybe_process_settings_form();

		// Render the settings template
		include( __DIR__ . "/../admin.php" );

	}


	/**
	 * Process the admin page settings form submission
	 *
	 * @return void
	 */
	private function maybe_process_settings_form() {

		if ( ! ( isset( $_POST['_wpnonce'] ) && check_admin_referer( 'DRA_admin_nonce' ) ) ) {
			return;
		}

		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		// Catch the routes that should be whitelisted
		$rest_routes = ( isset( $_POST['rest_routes'] ) ) ?
			array_map( 'esc_html', wp_unslash( $_POST['rest_routes'] ) ) :
			null;

		// If resetting or whitelist is empty, clear the option and exit the function
		if ( empty( $rest_routes ) || isset( $_POST['reset'] ) ) {
			delete_option( 'DRA_route_whitelist' );
			add_settings_error( 'DRA-notices', esc_attr( 'settings_updated' ), esc_html__( 'All whitelists have been removed.' ), 'updated' );

			return;
		}

		// Save whitelist to the Options table
		update_option( 'DRA_route_whitelist', $rest_routes );
		add_settings_error( 'DRA-notices', esc_attr( 'settings_updated' ), esc_html__( 'Whitelist settings saved.' ), 'updated' );

	}


	/**
	 * Allow carte blanche access for logged-in users (or allow override via filter)
	 *
	 * @return bool
	 */
	private function allow_rest_api() {
		return (bool) apply_filters( 'dra_allow_rest_api', is_user_logged_in() );
	}


	/**
	 * If $access is already a WP_Error object, add our error to the list
	 * Otherwise return a new one
	 *
	 * @param $access
	 *
	 * @return WP_Error
	 */
	private function get_wp_error( $access ) {
		$error_message = esc_html__( 'DRA: Only authenticated users can access the REST API.', 'disable-json-api' );

		if ( is_wp_error( $access ) ) {
			$access->add( 'rest_cannot_access', $error_message, array( 'status' => rest_authorization_required_code() ) );

			return $access;
		}

		return new WP_Error( 'rest_cannot_access', $error_message, array( 'status' => rest_authorization_required_code() ) );
	}

}
