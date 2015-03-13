<?php
/**
 * Admin Options
 *
 * All of the administrative functionality for BuddyPress Automatic Friends.
 *
 * @link http://wordpress.org/plugins/bp-automatic-friends/
 * @since 2.0.0
 *
 * @package BuddyPress Automatic Friends
 * @subpackage Admin
 */

/**
 * BuddPress Automatic Friends Admin
 */
class BPAF_Admin {

	public $plugins_url;

	/* Define and register singleton */
	private static $instance = false;
	public static function instance() {
		if( ! self::$instance ) {
			self::$instance = new self;
			self::$instance->setup();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	private function __construct() { }

	/**
	 * Clone
	 *
	 * @since 2.0.0
	 */
	private function __clone() { }

	/**
	 * Add actions and filters
	 *
	 * @uses add_action, add_filter
	 * @since 2.0.0
	 */
	function setup() {
		global $pagenow;

		// Setup
		$this->plugins_url = plugins_url( '/bp-automatic-friends' );

		// Admin Menu
		add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', array( $this, 'action_admin_menu' ), 11 );

		// AJAX
		add_action( 'wp_ajax_bpaf_suggest_global_friend', array( $this, 'action_ajax_bpaf_suggest_global_friend' ) );
		add_action( 'wp_ajax_bpaf_add_global_friend', array( $this, 'action_ajax_bpaf_add_global_friend' ) );
		add_action( 'wp_ajax_bpaf_delete_global_friend', array( $this, 'action_ajax_bpaf_delete_global_friend' ) );

		// User options
		add_action( 'personal_options', array( $this, 'action_personal_options' )  );
		add_action( 'personal_options_update', array( $this, 'action_personal_options_update' ) );
		add_action( 'edit_user_profile_update', array( $this, 'action_personal_options_update' ) );

		/* We don't need any of these things in other places */
		if( 'users.php' != $pagenow || ! isset( $_REQUEST['page'] ) || 's8d-bpaf-settings' != $_REQUEST['page'] ) {
			return;
		}

		// Init
		add_action( 'admin_init', array( $this, 'action_admin_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'action_admin_enqueue_scripts' ), 11 );

	}

	/**
	 * Setup the Admin.
	 *
	 * @uses register_setting, add_settings_section, add_settings_field
	 * @action admin_init
	 * @return null
	 */
	function action_admin_init() {
		// Register Settings
		register_setting( BPAF_Core::LEGACY_OPTION, BPAF_Core::LEGACY_OPTION, array( $this, 's8d_bpaf_settings_validate_options' ) );
	}


	/**
	 * Enqueue necessary scripts.
	 *
	 * @uses wp_enqueue_script
	 * @return null
	 */
	public function action_admin_enqueue_scripts() {
		wp_enqueue_script( 'bpaf-admin', $this->plugins_url. '/js/admin.js', array( 'jquery', 'jquery-ui-autocomplete' ), BPAF_Core::REVISION, true );

		wp_enqueue_style( 'bpaf-genericons', $this->plugins_url . '/fonts/genericons/genericons.css', '', BPAF_Core::REVISION );
		wp_enqueue_style( 'bpaf-admin', $this->plugins_url . '/css/admin.css', array( 'bpaf-genericons' ), BPAF_Core::REVISION );
	}

	/**
	 * Setup Admin Menu Options & Settings.
	 *
	 * @uses is_super_admin, add_submenu_page
	 * @action network_admin_menu, admin_menu
	 * @return null
	 */
	function action_admin_menu() {
		if ( ! is_super_admin() )
			return false;

		add_users_page( __( 'Automatic Friends', BPAF_Core::TEXT_DOMAIN), __( 'Automatic Friends', BPAF_Core::TEXT_DOMAIN ), 'manage_options', 's8d-bpaf-settings', array( $this, 'settings_page' ) );
	}

	/**
	 * Settings Page.
	 *
	 * @uses get_admin_url, settings_fields, do_settings_sections
	 * @return null
	 */
	function settings_page() {
		?>
		<div class="wrap">
			<?php //screen_icon(); ?>
			<h2><?php _e( 'BuddyPress Automatic Friends', BPAF_Core::TEXT_DOMAIN );?></h2>
			<div id="poststuff" class="metabox-holder has-right-sidebar">
				<div class="inner-sidebar" id="side-info-column">
					<div id="side-sortables" class="meta-box-sortables ui-sortable">
						<div id="bpaf_display_option" class="postbox ">
							<h3 class="hndle"><span><?php _e( 'Help Improve BP Automatic Friends', BPAF_Core::TEXT_DOMAIN );?></span></h3>
							<div class="inside">
								<p><?php _e( 'We would really appreciate your input to help us continue to improve the product.', BPAF_Core::TEXT_DOMAIN );?></p>
								<p>
								<?php printf( __( 'Find us on %1$s or donate to the project using the button below.', BPAF_Core::TEXT_DOMAIN ), '<a href="https://github.com/stevenkword/BuddyPress-Automatic-Friends" target="_blank">GitHub</a>' ); ?>
								</p>
								<div style="width: 100%; text-align: center;">
									<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
										<input type="hidden" name="cmd" value="_s-xclick">
										<input type="hidden" name="hosted_button_id" value="DWK9EXNAHLZ42">
										<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
										<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
									</form>
								</div>
							</div>
						</div>
						<div id="bpaf_display_contact" class="postbox ">
							<h3 class="hndle"><span><?php _e( 'Contact BP Automatic Friends', BPAF_Core::TEXT_DOMAIN );?></span></h3>
							<div class="inside">
								<ul class="bpaf-contact-links">
									<li><a class="link-bpaf-forum" href="http://wordpress.org/support/plugin/bp-automatic-friends" target="_blank"><?php _e( 'Support Forums', BPAF_Core::TEXT_DOMAIN );?></a></li>
									<li><a class="link-bpaf-web" href="http://stevenword.com/plugins/bp-automatic-friends/" target="_blank"><?php _e( 'BP Automatic Friends on the Web', BPAF_Core::TEXT_DOMAIN );?></a></li>
									<li><a class="link-bpaf-github" href="https://github.com/stevenkword/BuddyPress-Automatic-Friends" target="_blank"><?php _e( 'GitHub Project', BPAF_Core::TEXT_DOMAIN );?></a></li>
									<li><a class="link-bpaf-review" href="http://wordpress.org/support/view/plugin-reviews/bp-automatic-friends" target="_blank"><?php _e( 'Review on WordPress.org', BPAF_Core::TEXT_DOMAIN );?></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div id="post-body-content">
					<p><?php _e( 'When new user accounts are registered, friendships between the new user and each of the following global friends will be created automatically.', BPAF_Core::TEXT_DOMAIN );?></p>
					<h3 style="float: left; margin:1em 0;padding:0; line-height:2em;"><?php _e( 'Global Friends', BPAF_Core::TEXT_DOMAIN );?></h3>
					<div style="padding: 1em 0;">
						<?php $search_text = __('Search by Username', BPAF_Core::TEXT_DOMAIN );?>
						<input type="text" name="add-global-friend-field" id="add-global-friend-field" style="margin-left: 1em; color: #aaa;"value="<?php echo $search_text;?>" onfocus="if (this.value == '<?php echo $search_text;?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo $search_text;?>';}" size="40" maxlength="128">
						<button id="add-global-friend-button" class="button" disabled="disabled"><?php _e( 'Add User', BPAF_Core::TEXT_DOMAIN );?></button>
						<span class="spinner"></span>
					</div>
					<div id="global-friend-table-container">
						<?php $this->render_global_friend_table();?>
					</div>
				</div>
			</div>
		</div><!--/.wrap-->
		<?php
	}

	/**
	 * Personal Options.
	 *
	 * @return null
	 */
	function action_personal_options( $user ) {
		$meta_value = get_user_meta( $user->ID, BPAF_Core::METAKEY, true );
		?>
			</table>
			<table class="form-table">
			<h3><?php _e( 'BuddyPress Automatic Friends', BPAF_Core::TEXT_DOMAIN );?></h3>
			<tr>
				<th scope="row"><?php _e( 'Global Friend', BPAF_Core::TEXT_DOMAIN );?></th>
				<td>
					<label for="global-friend">
						<input type="checkbox" id="global-friend" name="global-friend" <?php checked( $meta_value ); ?> />
						<span> <?php _e( 'Automatically create friendships with all new users', BPAF_Core::TEXT_DOMAIN );?></span>
						<?php wp_nonce_field( BPAF_Core::NONCE, BPAF_Core::NONCE, false ); ?>
					</label>
				</td>
			</tr>
		<?php
	}

	/**
	 * Update personal options.
	 *
	 * @since 2.0.0
	 */
	function action_personal_options_update( $user_id ) {
		// Nonce check
		if ( ! wp_verify_nonce( $_REQUEST[ BPAF_Core::NONCE ], BPAF_Core::NONCE ) || ! current_user_can( 'edit_user', $user_id ) ) {
			wp_die( BPAF_Core::NONCE_FAIL_MSG );
		}

		$meta_value = isset( $_REQUEST['global-friend'] ) ? true : false;
		update_usermeta( $user_id, BPAF_Core::METAKEY, $meta_value );

		// Update the friend counts
		BP_Friends_Friendship::total_friend_count( $user_id );
	}

} // Class
BPAF_Admin::instance();