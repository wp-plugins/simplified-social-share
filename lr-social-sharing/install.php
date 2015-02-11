<?php
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * class responsible for setting default settings for social invite.
 */
class LR_Sharing_Install {


	private static $options = array(
			'horizontal_enable'          => '1',
			'vertical_enable'            => '',
			'horizontal_share_interface' => '32-h',
			'vertical_share_interface'   => '32-v',
			'mobile_enable'				 => '1',
			'horizontal_sharing_providers'=>array(
				'Default' => array(
					'Facebook' => 'Facebook',
					'Email' => 'Email',
					'Print' => 'Print',
					'GooglePlus' => 'GooglePlus',
					'LinkedIn' => 'LinkedIn',
					'Twitter' => 'Twitter',
					'Pinterest' => 'Pinterest'
				),
				'Hybrid'  => array(
					'Facebook Like' => 'Facebook Like',
					'Twitter Tweet' => 'Twitter Tweet',
					'Google+ Share' => 'Google+ Share',
					'Pinterest Pin it' => 'Pinterest Pin it',
					'LinkedIn Share' => 'LinkedIn Share'
				)
			),
			'vertical_sharing_providers'=>array(
				'Default' => array(
					'Facebook' => 'Facebook',
					'Email' => 'Email',
					'Print' => 'Print',
					'GooglePlus' => 'GooglePlus',
					'LinkedIn' => 'LinkedIn',
					'Twitter' => 'Twitter',
					'Pinterest' => 'Pinterest'
				),
				'Hybrid'  => array(
					'Facebook Like' => 'Facebook Like',
					'Twitter Tweet' => 'Twitter Tweet',
					'Google+ Share' => 'Google+ Share',
					'Pinterest Pin it' => 'Pinterest Pin it',
					'LinkedIn Share' => 'LinkedIn Share'
				)
			),
			'lr-clicker-hr-home' => '1',
			'lr-clicker-hr-post' => '1',
			'lr-clicker-hr-static' => '1',
			'lr-clicker-hr-excerpts' => '1',
			'horizontal_position' => array(
				'Home' => array(
					'Top' => 'Top'
				),
				'Posts' => array(
					'Top' => 'Top',
					'Bottom' => 'Bottom'
				),
				'Pages' => array(
					'Top' => 'Top'
				),
				'Excerpts' => array(
					'Top' => 'Top'
				)
			),
			'horizontal_rearrange_providers' => array(
				'Facebook',
				'Twitter',
				'LinkedIn',
				'GooglePlus',
				'Pinterest',
				'Email',
				'Print'
			),
			'vertical_rearrange_providers' => array(
				'Facebook',
				'Twitter',
				'LinkedIn',
				'GooglePlus',
				'Pinterest',
				'Email',
				'Print'
			)
	);

	private $api_options = array(
			'LoginRadius_apikey'		=> '',
			'LoginRadius_secret'		=> '',
			'scripts_in_footer'			=> '1',
			'sitename'                  => ''
	);

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->set_default_options();

		add_action( 'admin_enqueue_scripts', array( $this, 'share_add_stylesheet' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_share_scripts' ) );
		add_action( 'admin_init', array( $this, 'register_share_settings' ) );
	}

	/**
	 * Function for adding default social_profile_data settings at activation.
	 */
	public static function set_default_options() {
		global $loginradius_share_settings;
		if( ! get_option( 'LoginRadius_share_settings' ) ) {

			// Adding LoginRadius plugin options if not available.
			update_option( 'LoginRadius_share_settings', self::$options );
		}

		if( ! get_option( 'LoginRadius_API_settings' ) ) {
			// Get Existing API key for update.
			if( get_option( 'LoginRadius_settings' ) ) {
				$loginradius_existing_settings = get_option( 'LoginRadius_settings' );
				if( isset($loginradius_existing_settings['LoginRadius_apikey'] ) && ! empty( $loginradius_existing_settings['LoginRadius_apikey'] ) ) {
					$api_options['LoginRadius_apikey'] = $loginradius_existing_settings['LoginRadius_apikey'];
				}
			}

			if( get_option( 'LoginRadius_sharing_settings' ) ) {
				$loginradius_existing_settings = get_option( 'LoginRadius_sharing_settings' );
				if( isset( $loginradius_existing_settings['LoginRadius_apikey'] ) && ! empty( $loginradius_existing_settings['LoginRadius_apikey'] ) ) {
					$api_options['LoginRadius_apikey'] = $loginradius_existing_settings['LoginRadius_apikey'];
					
				}
			}
			update_option( 'LoginRadius_API_settings', $api_options );
		}

		// Get LoginRadius plugin settings.
		$loginradius_share_settings = get_option( 'LoginRadius_share_settings' );

		// Get LoginRadius plugin settings.
		$loginradius_api_settings = get_option( 'LoginRadius_API_settings' );

		// Set share in footer to true if set.
		if( isset( $loginradius_share_settings['js_footer_enable'] ) && $loginradius_share_settings['js_footer_enable'] == '1' ) {
			global $lr_js_in_footer;
			$lr_js_in_footer = true;
		}
	}

	/**
	 * Add stylesheet and JavaScript to admin section.
	 */
	public function share_add_stylesheet( $hook ) {
		global $lr_js_in_footer;
		if( $hook != 'loginradius_page_loginradius_share' && $hook != 'toplevel_page_loginradius_share' ) {
			return;
		}
		wp_enqueue_style( 'loginradius_sharing_style', plugins_url('/assets/css/lr-social-sharing-admin.css', __FILE__) );
		wp_enqueue_script( 'loginradius_share_admin_javascript', plugins_url( '/assets/js/loginradius_sharing_admin.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-mouse', 'jquery-touch-punch' ), false, $lr_js_in_footer );
	}
	
	/**
	 * Add stylesheet and JavaScript to client sections
	 */
	public function enqueue_share_scripts() {
		// Init javascript variables - should load in head
		wp_enqueue_script( 'loginradius_javascript_init', plugins_url( '/assets/js/loginradius_sharing.js', __FILE__ ), '1.0.0', false );
		wp_enqueue_script( 'lr-social-sharing' );
	}
	
	/**
	 * Register Share Settings.
	 */
	public function register_share_settings() {
		register_setting( 'loginradius_share_settings', 'LoginRadius_share_settings' );
		register_setting( 'loginradius_api_settings', 'LoginRadius_API_settings' );
	}
	
	/**
	 * Reset Sharing Settings.
	 */
	public static function reset_share_options() {		
		// Load reset options.
		update_option( 'LoginRadius_share_settings', self::$options );
	}
}
new LR_Sharing_Install();