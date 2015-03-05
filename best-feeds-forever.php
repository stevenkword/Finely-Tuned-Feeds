<?php
/*
Plugin Name: Best Feeds Forever
Version: 0.1-alpha
Description: Feed 💗
Author: stevenkword
Author URI: YOUR SITE HERE
Plugin URI: PLUGIN SITE HERE
Text Domain: best-feeds-forever
Domain Path: /languages
*/


namespace WPEngine;

// Exit if this file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

class Best_Feeds_Forever {

	// Define and register singleton
	private static $instance = null;

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

		// Decouple RSS2
		remove_all_actions( 'do_feed_rss2' );

		add_action( 'do_feed_rss2', array( self::instance(), 'bff_rss2' ), 10, 1 );

	}

	function bff_rss2( $for_comments ) {

		$rss2_template = plugin_dir_path( __FILE__ ) . 'templates/feed-rss2.php';

		if( file_exists( $rss2_template ) ) {
			load_template( $rss2_template );
		} else {
			do_feed_rss2( $for_comments ); // Call default function
		}
	}

}
Best_Feeds_Forever::init();