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

namespace WPEngine;

/**
 * BuddPress Automatic Friends Admin
 */
class Finely_Tuned_Feeds_Admin {

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

		// User options
		add_action( 'personal_options', array( $this, 'action_personal_options' )  );
		add_action( 'personal_options_update', array( $this, 'action_personal_options_update' ) );
		add_action( 'edit_user_profile_update', array( $this, 'action_personal_options_update' ) );

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
		//register_setting( Finely_Tuned_Feeds::LEGACY_OPTION, Finely_Tuned_Feeds::LEGACY_OPTION, array( $this, 's8d_Finely_Tuned_Feeds_settings_validate_options' ) );
	}


	/**
	 * Enqueue necessary scripts.
	 *
	 * @uses wp_enqueue_script
	 * @return null
	 */
	public function action_admin_enqueue_scripts() {
		//wp_enqueue_script( 'bpaf-admin', $this->plugins_url. '/js/admin.js', array( 'jquery', 'jquery-ui-autocomplete' ), Finely_Tuned_Feeds::REVISION, true );

		//wp_enqueue_style( 'bpaf-genericons', $this->plugins_url . '/fonts/genericons/genericons.css', '', Finely_Tuned_Feeds::REVISION );

		//wp_enqueue_style( 'bpaf-admin', $this->plugins_url . '/css/admin.css', array( 'bpaf-genericons' ), Finely_Tuned_Feeds::REVISION );
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

		add_submenu_page( 'options-general.php', __( 'Finely Tuned Feeds', Finely_Tuned_Feeds::TEXT_DOMAIN ), __( 'Finely Tuned Feeds', Finely_Tuned_Feeds::TEXT_DOMAIN ), 'manage_options', 'finely-tuned-feeds-settings', array( $this, 'settings_page' )  );

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
			<h2><span class="dashicons dashicons-rss" style="font-size: 1.2em;"></span>&nbsp;&nbsp;<?php _e( 'Finely Tuned Feeds', Finely_Tuned_Feeds::TEXT_DOMAIN );?></h2>
			<div id="poststuff" class="metabox-holder has-right-sidebar">
				<div class="inner-sidebar" id="side-info-column">
					<div id="side-sortables" class="meta-box-sortables ui-sortable">
						<!--
						<div id="Finely_Tuned_Feeds_display_option" class="postbox ">
							<h3 class="hndle"><span><?php _e( 'Help Improve BP Automatic Friends', Finely_Tuned_Feeds::TEXT_DOMAIN );?></span></h3>
							<div class="inside">
								<p><?php _e( 'We would really appreciate your input to help us continue to improve the product.', Finely_Tuned_Feeds::TEXT_DOMAIN );?></p>
								<p>
								<?php printf( __( 'Find us on %1$s or donate to the project using the button below.', Finely_Tuned_Feeds::TEXT_DOMAIN ), '<a href="https://github.com/stevenkword/BuddyPress-Automatic-Friends" target="_blank">GitHub</a>' ); ?>
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
						-->
						<div id="Finely_Tuned_Feeds_display_contact" class="postbox ">
							<h3 class="hndle"><span><?php _e( 'Support', Finely_Tuned_Feeds::TEXT_DOMAIN );?></span></h3>
							<div class="inside">
								<ul class="bpaf-contact-links">
									<li><a class="link-bpaf-forum" href="http://wordpress.org/support/plugin/bp-automatic-friends" target="_blank"><?php _e( 'Support Forums', Finely_Tuned_Feeds::TEXT_DOMAIN );?></a></li>
									<li><a class="link-bpaf-github" href="https://github.com/stevenkword/Finely-Tuned-Feeds" target="_blank"><?php _e( 'GitHub Project', Finely_Tuned_Feeds::TEXT_DOMAIN );?></a></li>
									<li><a class="link-bpaf-review" href="http://wordpress.org/support/view/plugin-reviews/bp-automatic-friends" target="_blank"><?php _e( 'Review on WordPress.org', Finely_Tuned_Feeds::TEXT_DOMAIN );?></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div id="post-body-content">

					<p>Valid and Well-formed XML for WordPress. These settings are not yet working!!!</p>

					<h2 class="title">RSS Escaping Methods</h2>

					<table class="form-table" style="clear: none;">
						<tbody>

						<tr valign="top">
							<th scope="row"><label for="wp_cache_status">GUID escaping method:</label></th>
							<td>
								<fieldset>
								<label><input type="radio" name="ftf_esc_method_guid" value="1" checked="checked">Escape as URL <em>(Default)</em></label><br>

								<label><input type="radio" name="ftf_esc_method_guid" value="0">Escape as HTML 💗</label><br>

								<label><input type="radio" name="ftf_esc_method_guid" value="2">Escape as XML 🔥<em>(Experimental)</em></label><br><br>
								<em>Relates: <a href="https://core.trac.wordpress.org/ticket/31080" target="_blank">#31080</a></em><br>
								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><label for="wp_cache_status">Title escaping method:</label></th>
							<td>
								<fieldset>
								<label><input type="radio" name="ftf_esc_method_title" value="1" checked="checked">Escape as HTML <em>(Default)</em></label><br>

								<label><input type="radio" name="ftf_esc_method_title" value="2">Escape as XML 💗🔥<em>(Experimental)</em></label><br>
								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><label for="wp_cache_status">Comment escaping method:</label></th>
							<td>
								<fieldset>
								<label><input type="radio" name="ftf_esc_method_comment" value="1" checked="checked">Escape as HTML <em>(Default)</em></label><br>

								<label><input type="radio" name="ftf_esc_method_comment" value="2">Escape as XML 💗🔥<em>(Experimental)</em></label><br>
								</fieldset>
							</td>
						</tr>

						</tbody>
					</table>

					<h2 class="title">Template Overrides</h2>

					<table class="form-table" style="clear: none;">
						<tbody>

						<tr valign="top">
							<th scope="row"><label for="wp_cache_status">Replace RSS2 Template:</label></th>
							<td>
								<fieldset>
								<label><input type="radio" name="ftf_template_rs2" value="1" checked="checked">no <em>(Default)</em></label><br>

								<label><input type="radio" name="ftf_template_rs2" value="0">yes 💗🔥</label><br>

								</fieldset>
							</td>
						</tr>
						</tbody>
					</table>

					<p>💗 <em>denotes the plugin author's recommendation</em></p>
					<p>🔥 <em>denotes the hotness</em></p>
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
		$meta_value = get_user_meta( $user->ID, Finely_Tuned_Feeds::METAKEY, true );
		?>
			</table>
			<table class="form-table">
			<h3><?php _e( 'BuddyPress Automatic Friends', Finely_Tuned_Feeds::TEXT_DOMAIN );?></h3>
			<tr>
				<th scope="row"><?php _e( 'Global Friend', Finely_Tuned_Feeds::TEXT_DOMAIN );?></th>
				<td>
					<label for="global-friend">
						<input type="checkbox" id="global-friend" name="global-friend" <?php checked( $meta_value ); ?> />
						<span> <?php _e( 'Automatically create friendships with all new users', Finely_Tuned_Feeds::TEXT_DOMAIN );?></span>
						<?php wp_nonce_field( Finely_Tuned_Feeds::NONCE, Finely_Tuned_Feeds::NONCE, false ); ?>
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
		return;
		// Nonce check
		if ( ! wp_verify_nonce( $_REQUEST[ Finely_Tuned_Feeds::NONCE ], Finely_Tuned_Feeds::NONCE ) || ! current_user_can( 'edit_user', $user_id ) ) {
			wp_die( Finely_Tuned_Feeds::NONCE_FAIL_MSG );
		}

		$meta_value = isset( $_REQUEST['global-friend'] ) ? true : false;
		update_usermeta( $user_id, Finely_Tuned_Feeds::METAKEY, $meta_value );
	}

} // Class
Finely_Tuned_Feeds_Admin::instance();