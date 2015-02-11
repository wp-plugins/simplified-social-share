<?php
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * The main class and initialization point of the mailchimp plugin admin.
 */
if ( ! class_exists( 'LR_Social_Share_Admin' ) ) {

	class LR_Social_Share_Admin {
		
		/**
		 * LR_Social_Share_Admin class instance
		 *
		 * @var string
		 */
		private static $instance;

		/**
		 * Get singleton object for class LR_Social_Share_Admin
		 *
		 * @return object LR_Social_Share_Admin
		 */
		public static function get_instance() {
			if ( !isset( self::$instance ) && !( self::$instance instanceof LR_Social_Share_Admin ) ) {
				self::$instance = new LR_Social_Share_Admin();
			}
			return self::$instance;
		}

		/*
		 * Constructor for class LR_Social_Share_Admin
		 */
		public function __construct() {
			// Registering hooks callback for admin section.
			$this->register_hook_callbacks();

			// // Add a callback public function to save any data a user enters in
			// $this->meta_box_setup();
		}

		/*
		 * Register admin hook callbacks
		 */
		public function register_hook_callbacks() {

			// Used for aia activation.
			add_action( 'wp_ajax_lr_save_apikey', array( $this, 'save_apikey' ) );

			// Add a meta box on all posts and pages to disable sharing.
			add_action( 'add_meta_boxes', array( $this, 'meta_box_setup' ) );

			// Add a callback public function to save any data a user enters in
			add_action( 'save_post', array( $this, 'save_meta') );
		}

		/*
		 * adding LoginRadius meta box on each page and post
		 */
		public function meta_box_setup() {
			foreach ( array('post', 'page') as $type ) {
				add_meta_box( 'login_radius_meta', 'LoginRadius Sharing', array( $this, 'meta_setup'), $type );
			}
		}

		/**
		 * Display  metabox information on page and post
		 */
		public function meta_setup() {
			global $post;
			$postType = $post->post_type;
			$lrMeta = get_post_meta( $post->ID, '_login_radius_meta', true );
			$lrMeta['sharing'] = isset( $lrMeta['sharing'] ) ? $lrMeta['sharing'] : '';
			?>
			<p>
				<label for="login_radius_sharing">
					<input type="checkbox" name="_login_radius_meta[sharing]" id="login_radius_sharing" value='1' <?php checked( '1', $lrMeta['sharing'] ); ?> />
					<?php _e( 'Disable Social Sharing on this ' . $postType, 'LoginRadius' ) ?>
				</label>
			</p>
			<?php
			// Custom nonce for verification later.
			echo '<input type="hidden" name="login_radius_meta_nonce" value="' . wp_create_nonce( __FILE__ ) . '" />';
		}

		/**
		 * Save sharing enable/diable meta fields.
		 */
		public function save_meta( $postId ) {
			// make sure data came from our meta box
			if ( !isset( $_POST['login_radius_meta_nonce'] ) || !wp_verify_nonce( $_POST['login_radius_meta_nonce'], __FILE__ ) ) {
				return $postId;
			}
			// check user permissions
			if ( $_POST['post_type'] == 'page' ) {
				if ( !current_user_can( 'edit_page', $postId ) ) {
					return $postId;
				}
			} else {
				if ( !current_user_can( 'edit_post', $postId ) ) {
					return $postId;
				}
			}
			if ( isset( $_POST['_login_radius_meta'] ) ) {
				$newData = $_POST['_login_radius_meta'];
			} else {
				$newData = 0;
			}
			update_post_meta( $postId, '_login_radius_meta', $newData );
			return $postId;
		}

		/**
		 * Save LoginRadius API key in the database
		 */
		public static function save_apikey() {
			if ( isset( $_POST['apikey'] ) && trim( $_POST['apikey'] ) != '' ) {
				$options = get_option( 'LoginRadius_API_settings' );
				$options['LoginRadius_apikey'] = trim( $_POST['apikey'] );
				if(update_option( 'LoginRadius_API_settings', $options ) ){
					die('success');
				}
			}
			die('error');
		}

		/*
		 * Callback for add_menu_page,
		 * This is the first function which is called while plugin admin page is requested
		 */
		public static function options_page() {
			global $loginRadiusObject, $loginradius_api_settings, $loginradius_share_settings;

			// Sharing Activation Required. 
			$activate_sharing = false;

			include_once "views/settings.php";

			// Get LoginRadius plugin settings.
			$loginradius_api_settings = get_option( 'LoginRadius_API_settings' );
			$loginradius_share_settings = get_option( 'LoginRadius_share_settings' );
			
			LR_Social_Share_Settings::render_options_page();		
		}
	}
}
new LR_Social_Share_Admin();