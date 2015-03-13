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

namespace Finely_Tuned_Feeds;

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
		$this->plugins_url = plugins_url( '/finely-tuned-feeds' );

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

		// Only add this variable on the settings page
		//if( ! self::is_settings_page() ) {
			//return;
		//}
		// Settings Styles
		wp_enqueue_style( 'ftf-genericons', $this->plugins_url . '/fonts/genericons/genericons.css', '', Finely_Tuned_Feeds::REVISION );
		wp_enqueue_style( 'ftf-admin-settings', $this->plugins_url . '/css/settings.css', '', Finely_Tuned_Feeds::REVISION );



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
		// Get active tab
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'escaping';
		?>
		<div class="wrap">
			<?php //screen_icon(); ?>
			<h2><span class="dashicons dashicons-rss" style="font-size: 1.2em;"></span>&nbsp;&nbsp;<?php _e( 'Finely Tuned Feeds', Finely_Tuned_Feeds::TEXT_DOMAIN );?></h2>
			<div id="poststuff" class="metabox-holder has-right-sidebar">
				<div class="inner-sidebar" id="side-info-column">
					<div id="side-sortables" class="meta-box-sortables ui-sortable">
						<div id="Finely_Tuned_Feeds_display_contact" class="postbox ">
							<h3 class="hndle"><span><?php _e( 'Legend', Finely_Tuned_Feeds::TEXT_DOMAIN );?></span></h3>
							<div class="inside">
								<ul class="ftf-contact-links">
									<li>💗 Recommended</li>
									<li>🔥 The hotness (experimental)</li>
									<li>🐢 WordPress Core functionality</li>
									<li>✨ New and improved!</li>
									<li>💣 This could break <u>all</u> of the things!</li>
								</ul>
							</div>
						</div>

						<div id="Finely_Tuned_Feeds_display_contact" class="postbox ">
							<h3 class="hndle"><span><?php _e( 'Support', Finely_Tuned_Feeds::TEXT_DOMAIN );?></span></h3>
							<div class="inside">
								<ul class="ftf-contact-links">
									<li><a class="link-ftf-forum" href="http://wordpress.org/support/plugin/bp-automatic-friends" target="_blank"><?php _e( 'Support Forums', Finely_Tuned_Feeds::TEXT_DOMAIN );?></a></li>
									<li><a class="link-ftf-github" href="https://github.com/stevenkword/Finely-Tuned-Feeds" target="_blank"><?php _e( 'GitHub Project', Finely_Tuned_Feeds::TEXT_DOMAIN );?></a></li>
									<li><a class="link-ftf-review" href="http://wordpress.org/support/view/plugin-reviews/bp-automatic-friends" target="_blank"><?php _e( 'Review on WordPress.org', Finely_Tuned_Feeds::TEXT_DOMAIN );?></a></li>
									<li><a class="link-ftf-contact" href="mailto:steven@wpengine.com?Subject=Finely%20Tuned%20%Feeds" target="_blank">Contact the Maintainer</a></li>
								</ul>
							</div>
						</div>

					</div>
				</div>
				<div id="post-body-content">

					<p>Valid and Well-formed XML for WordPress</p>
					<h2 class="nav-tab-wrapper" style="padding: 0;">
						<a href="<?php echo admin_url( 'options-general.php?page=finely-tuned-feeds-settings&tab=escaping' );?>" class="nav-tab <?php echo $active_tab == 'escaping' ? 'nav-tab-active' : ''; ?>">Escaping</a>
						<a href="<?php echo admin_url( 'options-general.php?page=finely-tuned-feeds-settings&tab=striptags' );?>" class="nav-tab <?php echo $active_tab == 'striptags' ? 'nav-tab-active' : ''; ?>">Strip Tags</a>
						<a href="<?php echo admin_url( 'options-general.php?page=finely-tuned-feeds-settings&tab=templates' );?>" class="nav-tab <?php echo $active_tab == 'templates' ? 'nav-tab-active' : ''; ?>">Templates</a>
						<a href="<?php echo admin_url( 'options-general.php?page=finely-tuned-feeds-settings&tab=about' );?>" class="nav-tab <?php echo $active_tab == 'about' ? 'nav-tab-active' : ''; ?>">About</a>
					</h2>

					<form method="post" action="options.php">
						<?php
						if( $active_tab == 'escaping' ) {
							self::display_escaping_tab();
							submit_button();
						} elseif( $active_tab == 'striptags' ) {
							self::display_striptags_tab();
							//submit_button();
						} elseif( $active_tab == 'templates' ) {
							self::display_templates_tab();
							submit_button();
						} elseif( $active_tab == 'about' ) {
							self::display_about_tab();
						} else {
							self::display_escaping_tab();
							submit_button();
						} // end if/else
						?>
					</form>
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

	/**
	 * [display_escaping_tab description]
	 * @return [type] [description]
	 */
	function display_escaping_tab(){
		?>
		<h2 class="title">RSS Escaping Methods</h2>

		<table class="form-table" style="clear: none;">
			<tbody>

			<tr valign="top">
				<th scope="row"><label for="wp_cache_status">GUID escaping method:</label></th>
				<td>
					<fieldset>
					<label><input type="radio" name="ftf_esc_method_guid" value="esc_url" checked="checked">Escape as URL 🐢<em>(default)</em></label><br>

					<label><input type="radio" name="ftf_esc_method_guid" value="esc_html">Escape as HTML 💗</label><br>

					<label><input type="radio" name="ftf_esc_method_guid" value="esc_xml">Escape as XML 🔥</label><br>

					<br><em>Trac: <a href="https://core.trac.wordpress.org/ticket/31080" target="_blank">#31080</a></em><br>

					</fieldset>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="wp_cache_status">Title escaping method:</label></th>
				<td>
					<fieldset>
					<label><input type="radio" name="ftf_esc_method_title" value="esc_html" checked="checked">Escape as HTML 💗🐢<em>(default)</em></label><br>

					<label><input type="radio" name="ftf_esc_method_title" value="esc_xml">Escape as XML 🔥</label><br>

					<br><em>Trac:&nbsp;
						<a href="https://core.trac.wordpress.org/ticket/9993" target="_blank">#9993</a>,&nbsp;
						<a href="https://core.trac.wordpress.org/ticket/13867" target="_blank">#13867</a>,&nbsp;
						<a href="https://core.trac.wordpress.org/ticket/28816" target="_blank">#28816</a>
					</em><br>

					</fieldset>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="wp_cache_status">Comment escaping method:</label></th>
				<td>
					<fieldset>
					<label><input type="radio" name="ftf_esc_method_comment" value="esc_html" checked="checked">Escape as HTML 💗🐢<em>(default)</em></label><br>

					<label><input type="radio" name="ftf_esc_method_comment" value="esc_xml">Escape as XML 🔥</label><br>
					</fieldset>
				</td>
			</tr>

			</tbody>
		</table>
		<?php
	}

	/**
	 * [display_escaping_tab description]
	 * @return [type] [description]
	 */
	function display_striptags_tab(){
		?>
		<h2 class="title">Strip Tags</h2>

		<table class="form-table" style="clear: none;">
			<tbody>

			<tr valign="top">
				<th scope="row"><label for="wp_cache_status">Strip Tags from RSS Titles?</label></th>
				<td>
					<fieldset>
					<label><input type="radio" name="ftf_striptags_all" value="esc_url" checked="checked">Yes 🐢<em>(default)</em></label><br>

					<label><input type="radio" name="ftf_striptags_all" value="esc_html">No 💗🔥</label><br>

					<br><em>Trac: <a href="https://core.trac.wordpress.org/ticket/19998" target="_blank">#19998</a></em><br>

					</fieldset>
				</td>
			</tr>

			</tbody>
		</table>
		<?php
	}

	/**
	 * [display_templates_tab description]
	 * @return [type] [description]
	 */
	function display_templates_tab(){
		?>
		<h2 class="title">Template Overrides</h2>

		<table class="form-table" style="clear: none;">
			<tbody>

			<tr valign="top">
				<th scope="row"><label for="wp_cache_status">Replace RSS2 Template:</label></th>
				<td>
					<fieldset>
					<label><input type="radio" name="ftf_template_rs2" value="1" checked="checked">no 💗🐢<em>(default)</em></label><br>

					<label><input type="radio" name="ftf_template_rs2" value="0">yes 🔥💣</label><br>

					</fieldset>
				</td>
			</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * [display_about_tab description]
	 * @return [type] [description]
	 */
	function display_about_tab(){
		?>
		<h2 class="title">About Finely Tuned Feeds</h2>
		<?php

		//Get plugin path
		$plugin_path = dirname( dirname( __FILE__ ) );
		$master_plan_file = fopen( $plugin_path . '/readme.txt', 'r' );
		while ( ! feof( $master_plan_file ) )
			echo fgets( $master_plan_file ) . '<br />';
		fclose( $master_plan_file );
	}

} // Class
Finely_Tuned_Feeds_Admin::instance();