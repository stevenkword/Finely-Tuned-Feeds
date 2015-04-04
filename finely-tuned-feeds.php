<?php
/*
Plugin Name: Finely Tuned Feeds
Version: 0.1-alpha
Description: Feed 💗 via valid and well-formed XML for WordPress
Author: Steven Word
Author URI: http://www.stevenword.com
Plugin URI: https://github.com/stevenkword/Finely-Tuned-Feeds
Text Domain: finely-tuned-feeds
Domain Path: /languages
*/

namespace Finely_Tuned_Feeds;

// Exit if this file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

class Finely_Tuned_Feeds {

	// Define and register singleton
	private static $instance = null;

	const VERSION        = '0.1.0';
	const REVISION       = '20150314';
	const NONCE          = 'finely_tuned_feeds_nonce';
	const NONCE_FAIL_MSG = 'Cheatin&#8217; huh?';
	const TEXT_DOMAIN    = 'finely-tuned-feeds';
	const TAGLINE        = 'Feed 💗 via valid and well-formed XML';

	/**
	 * Register singleton
	 *
	 * @since 0.1.0
	 */
	public static function instance() {
		// create a new object if it doesn't exist.
		is_null( self::$instance ) && self::$instance = new self;
		return self::$instance;
	}

	/**
	 * Initialize hooks and setup environment variables
	 *
	 * @since 0.1.0
	 */
	public static function init() {

		// Filters
		require_once( plugin_dir_path( __FILE__ ) . 'includes/filters.php' );

		// Functions
		require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php' );

		// WP Admin functions
		if( is_admin() ) {
			require_once( plugin_dir_path( __FILE__ ) . 'includes/class-wp-admin.php' );
		}

		// Apply the filters based on options
		self::apply_filters();

		// Replace Templates
		//remove_all_actions( 'do_feed_rss2' );
		//add_action( 'do_feed_rss2', array( self::instance(), 'load_template_rss2' ), 10, 1 );

	}

	public static function apply_filters(){

		/**
		 * Escape the GUID
		 * @var [type]
		 */
		$option_esc_the_guid = get_option( 'ftf_option_esc_the_guid', 'esc_url' );
		//$option_esc_the_guid = 'esc_xml';

		//var_dump( $option_esc_the_guid );
		if( 'esc_html' == $option_esc_the_guid ) {
			remove_filter( 'the_guid', 'esc_url' );
			add_filter( 'the_guid', 'esc_html' );
		} elseif( 'esc_xml' == $option_esc_the_guid ) {
			remove_filter( 'the_guid', 'esc_url' );
			add_filter( 'the_guid', 'xml_escape_the_guid' );
		}

		/**
		 * Escape the Title RSS
		 * @var [type]
		 */
		$option_esc_the_title_rss = get_option( 'ftf_option_esc_the_title_rss', 'esc_html' );
		if( 'esc_xml' == $option_esc_the_title_rss ) {
			/**
			 * Replace Core Title RSS Filters using `esc_html` with `esc_xml`
			 *
			 * This replaces the escaping method used by core with the
			 * `esc_xml()` method found in this plugin.
			 */
			remove_filter( 'the_title_rss', 'esc_html' );
			add_filter( 'the_title_rss', 'esc_xml' );
		}

		/**
		 * Escape the Title RSS
		 * @var [type]
		 */
		$option_esc_comment_text_rss = get_option( 'ftf_option_esc_comment_text_rss', 'esc_html' );
		if( 'esc_xml' == $option_esc_comment_text_rss ) {
			/**
			 * Replace Core Comments RSS Filters using `esc_html` with `esc_xml`
			 *
			 * This replaces the escaping method used by core with the
			 * `esc_xml()` method found in this plugin.
			 */
			remove_filter( 'comment_text_rss', 'esc_html' );
			add_filter( 'comment_text_rss', 'esc_xml' );
		}

		/**
		 * Reverse Escaping Order
		 */
		if( 2 === 1 ) {
			/**
			 * 28816 - Wow! This fixes most things
			 *
			 * https://core.trac.wordpress.org/attachment/ticket/28816/order.patch
			 */
			add_filter( 'the_title_rss', 'esc_xml', 10 );
			add_filter( 'the_title_rss', 'ent2ncr', 11 );
		}

		/**
		 * Replace HTML escaping with XML escaping
		 */
		if( 2 === 1 ) {
			/*
			global $wp_filter;
			echo '<pre>';
				var_dump( $wp_filter['the_excerpt_rss'] );
				var_dump( $wp_filter['the_content_feed'] );
				var_dump( $wp_filter['the_content'] );
			echo '</pre>';
			die('asdf');
			*/
			//add_filter( 'esc_xml', 'strip_for_xml' );
			//add_filter( 'esc_xml', 'patch_31190' );
			//add_filter( 'esc_xml', 'strip_non_xml_entities' );
		}


		/**
		 * Strip Tags for the Title RSS
		 * @var [type]
		 */
		$option_striptags_the_title_rss = get_option( 'ftf_option_striptags_the_title_rss', true );
		if( false === $option_striptags_the_title_rss ) {
			/**
			 * 9993 - Remove strip tags filter
			 */
			remove_filter( 'the_title_rss', 'strip_tags' );
		}

		/**
		 * Strip Control Characters from the Excerpt
		 * @ticket 29187, 19998
		 */
		if( 1 === 1 ) {
			add_filter( 'the_excerpt_rss', 'strip_for_xml' );
		}

	}

	/**
	 * Loads the replacement RSS2 template
	 *
	 * @param  [type] $for_comments [description]
	 * @return [type]               [description]
	 * @since  0.1.0
	 */
	function load_template_rss2( $for_comments ) {

		$rss2_template = plugin_dir_path( __FILE__ ) . 'templates/feed-rss2.php';

		if( file_exists( $rss2_template ) ) {
			load_template( $rss2_template );
		} else {
			do_feed_rss2( $for_comments ); // Call default function
		}
	}

}
Finely_Tuned_Feeds::init();