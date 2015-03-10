<?php

// Exit if this file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Debug Duck
 * @param  [type] $text [description]
 * @return [type]       [description]
 */
function debug_duck( $text ) {
	return "Debug Duck";
}
//add_filter( 'esc_xml', 'debug_duck' );

/**
 * Replace Default Filters using esc_html with esc_xml
 */
remove_filter( 'the_title_rss', 'esc_html' );
add_filter( 'the_title_rss', 'esc_xml' );

remove_filter( 'comment_text_rss', 'esc_html' );
add_filter( 'comment_text_rss', 'esc_xml' );

// Format strings for display.
/*
foreach ( array( 'comment_author', 'term_name', 'link_name', 'link_description', 'link_notes', 'bloginfo', 'wp_title', 'widget_title' ) as $filter ) {
	remove_filter( $filter, 'esc_html' );
	remove_filter( $filter, 'esc_xml' );
}
*/

/* ---- Patches ---- /*

/**
 * 28816 - Wow! This fixes most things
 *
 * https://core.trac.wordpress.org/attachment/ticket/28816/order.patch
 */
remove_filter( 'the_title_rss', 'ent2ncr', 8 );
remove_filter( 'the_title_rss', 'esc_html' );
add_filter( 'the_title_rss',    'esc_html', 10 );
add_filter( 'the_title_rss',    'ent2ncr', 11 );

/**
 * GUID
 * @pending 31080
 */
remove_filter( 'the_guid', 'esc_url' );
function html_escape_the_guid( $guid ) {
	return esc_xml( $guid );
}
add_filter( 'the_guid', 'html_escape_the_guid' );

// Strip for XML
add_filter( 'esc_xml', 'strip_for_xml' );
//add_filter( 'esc_xml',    'patch_31190' );