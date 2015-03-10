<?php

// Exit if this file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

// Debug catch all that outputs "DEBUG" on all esc_xml fields.
//add_filter( 'esc_xml', function() { return 'DEBUG'; } );

/**
 * Escape the GUID with XML
 * @ticket 31080
 */
remove_filter( 'the_guid', 'esc_url' );
function html_escape_the_guid( $guid ) {
	return esc_xml( $guid );
}
add_filter( 'the_guid', 'html_escape_the_guid' );

/**
 * Replace Core Title RSS Filters using `esc_html` with `esc_xml`
 *
 * This replaces the escaping method used by core with the
 * `esc_xml()` method found in this plugin.
 */
remove_filter( 'the_title_rss', 'esc_html' );
add_filter( 'the_title_rss', 'esc_xml' );

/**
 * Replace Core Comments RSS Filters using `esc_html` with `esc_xml`
 *
 * This replaces the escaping method used by core with the
 * `esc_xml()` method found in this plugin.
 */
remove_filter( 'comment_text_rss', 'esc_html' );
add_filter( 'comment_text_rss', 'esc_xml' );

/**
 * Apply patch filters
 */
//add_filter( 'esc_xml', 'strip_for_xml' );
//add_filter( 'esc_xml', 'patch_31190' );
//add_filter( 'esc_xml', 'strip_non_xml_entities' );

/**
 * 28816 - Wow! This fixes most things
 *
 * https://core.trac.wordpress.org/attachment/ticket/28816/order.patch
 */
add_filter( 'the_title_rss', 'esc_xml', 10 );
add_filter( 'the_title_rss', 'ent2ncr', 11 );

/**
 * 9993 - Remove strip tags filter
 */
remove_filter( 'the_title_rss', 'strip_tags' );