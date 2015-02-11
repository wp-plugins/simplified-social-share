<?php
// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( 'LR_Core' ) ) {

	/**
	 * The main class and initialization point of the plugin.
	 */
	class LR_Core {

		/**
		 * Constructor
		 */
		public function __construct() {

			// Declare constants and register files.
			$this->define_constants();
			$this->load_dependencies();

			add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_files' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_files' ) );
			add_action( 'login_enqueue_scripts', array( $this, 'register_login_files' ) );
			add_action( 'admin_menu', array( $this, 'create_loginradius_menu' ) );
		}

		/**
		 * Define constants needed across the plug-in.
		 */
		public function define_constants() {
			define( 'LR_CORE_DIR', plugin_dir_path( __FILE__ ) );
			define( 'LR_CORE_URL', plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Loads PHP files that required by the plug-in
		 *
		 * @global loginRadiusSettings, loginRadiusObject
		 */
		private function load_dependencies() {
			global $loginRadiusObject;

			//Load required files.
			require_once ( LR_CORE_DIR . 'lib/LoginRadiusSDK.php' );
			// Get object for LoginRadius SDK.
			$loginRadiusObject = new LoginRadius();

			/**
			 * Advanced Functions
			 *
			 * Used for MailChimp, Pardot Others
			 */
			require_once("includes/helpers/class-advanced-functions.php");

			// Activation settings class.
			require_once( LR_CORE_DIR . 'admin/class-activation-admin.php' );
			require_once( LR_CORE_DIR . 'admin/views/class-activation-settings-view.php' );
			
		}

		/**
		 * Create menu.
		 */
		public function create_loginradius_menu() {
			
			// Create Menu.			
			add_menu_page( 'LoginRadius', 'LoginRadius', 'manage_options', 'LoginRadius', array( 'LR_Activation_Admin', 'options_page'), LR_CORE_URL . 'assets/images/favicon.ico', 69.1 );

			// Social Sharing Module
			if ( file_exists( LR_ROOT_DIR . 'lr-social-sharing/lr-social-sharing.php' )  ) {
				if( ! class_exists('LR_Social_Login') || ! class_exists('LR_Raas')) {
					// Create Menu.		
					add_menu_page( 'LoginRadius', 'Social Sharing', 'manage_options', 'loginradius_share', array( 'LR_Social_Share_Admin', 'options_page'), LR_CORE_URL . 'assets/images/favicon.ico', 69.1 );
				}else {
					// Add Social Sharing menu.
					add_submenu_page( 'LoginRadius', 'Social Sharing Settings', 'Social Sharing', 'manage_options', 'loginradius_share', array( 'LR_Social_Share_Admin', 'options_page') );
				}
			}

		}

		/**
		 * Registers Scripts and Styles needed in all sections, is called from all sections
		 *
		 */
		public static function register_scripts_styles() {
			global $lr_js_in_footer;

			// LoginRadius js sdk must be loaded in header.
			wp_register_script( 'lr-sdk', LR_CORE_URL . 'js/LoginRadiusSDK.2.0.0.js', array(), '2.0.0', false );

			// Social Sharing js must be loaded in head.
			wp_register_script( 'lr-social-sharing', '//cdn.loginradius.com/share/v1/LoginRadius.js', array(), '1.0.0', false );
			
			wp_register_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css', array(), '1.0.0' );
		}

		/**
		 * Registers Scripts and Styles needed throughout front end of plugin
		 *
		 */
		public function register_frontend_files() {
			self::register_scripts_styles();
			//self::enqueue_login_scripts();
		}

		/**
		 * Registers Scripts and Styles needed throughout front end of plugin
		 *
		 */
		public function register_admin_files() {

			self::register_scripts_styles();
			//self::enqueue_login_scripts();
			
			wp_register_style( 'lr-admin-style', LR_CORE_URL . 'assets/css/lr-admin-style.css', array(), '1.0.0' );
			wp_enqueue_style( 'lr-admin-style' );
		}

		/**
		 * Registers Scripts and Styles needed throughout front end of plugin
		 *
		 */
		public function register_login_files() {
			self::register_scripts_styles();
		}
	}
}
new LR_Core();