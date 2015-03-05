<?php
/*
Plugin Name: Best-feeds-forever
Version: 0.1-alpha
Description: PLUGIN DESCRIPTION HERE
Author: YOUR NAME HERE
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
	private static $instance = false;

	public static function instance() {
		if( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __clone() { }

	private function __construct() {

		// Decouple RSS2
		remove_all_actions( 'do_feed_rss2' );

		add_action( 'do_feed_rss2', array( self::instance(), 'bff_rss2' ), 10, 1 );

	}

	function bff_rss2( $for_comments ) {

		return;

		$rss2_template = plugin_dir_path( __FILE__ ) . 'templates/feed-rss2.php';

		if( file_exists( $rss2_template ) ) {

			//die( 'death to smoochy' );
			load_template( $rss2_template );
		} else {
			do_feed_rss2( $for_comments ); // Call default function
		}
	}

}
Best_Feeds_Forever::instance();