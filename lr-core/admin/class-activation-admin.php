<?php
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * The main class and initialization point of the plugin admin.
 */
if ( !class_exists( 'LR_Activation_Admin' ) ) {
	
	class LR_Activation_Admin {

		/**
		 * LR_Activation_Admin class instance
		 *
		 * @var string
		 */
		private static $instance;

		/**
		 * Get singleton object for class LR_Activation_Admin
		 *
		 * @return object LR_Activation_Admin
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof LR_Activation_Admin ) ) {
				self::$instance = new LR_Activation_Admin();
			}
			return self::$instance;
		}

		/*
		 * Constructor for class LR_Social_Login_Admin
		 */

		public function __construct() {
			$this->install();
			$this->js_in_footer();
			// Registering hooks callback for admin section.
			$this->register_hook_callbacks();
		}

		// Create Api Options if not already created.
		public function install() {
			global $loginradius_api_settings;
			if( ! get_option( 'LoginRadius_API_settings' ) ) {
				$api_options = array(
						'LoginRadius_apikey'        => '',
						'LoginRadius_secret'        => '',
						'scripts_in_footer'         => '1',
						'delete_options'	    => '1',
						'sitename'                  => ''
				);
				update_option( 'LoginRadius_API_settings', $api_options );
			}
			$loginradius_api_settings = get_option( 'LoginRadius_API_settings' );
		}

		public static function js_in_footer() {
			global $loginradius_api_settings, $lr_js_in_footer;
			
			// Set js in footer bool.
			$lr_js_in_footer = isset( $loginradius_api_settings['scripts_in_footer'] ) && $loginradius_api_settings['scripts_in_footer'] == '1' ? true : false;
		}

		/*
		 * Register admin hook callbacks
		 */
		public function register_hook_callbacks() {
			add_action( 'admin_init', array($this, 'admin_init') );
		}

		/**
		 * Callback for admin_menu hook,
		 * Register LoginRadius_settings and its sanitization callback. Add Login Radius meta box to pages and posts.
		 */
		public function admin_init() {
			global $pagenow, $loginradius_api_settings;
			register_setting( 'loginradius_api_settings', 'LoginRadius_API_settings' );
		}

		/*
		 * Callback for add_menu_page,
		 * This is the first function which is called while plugin admin page is requested
		 */
		public static function options_page() {
			include_once "views/class-activation-settings-view.php";
			LR_Activation_Settings::render_options_page();
		}
	}
}

LR_Activation_Admin:: get_instance();
