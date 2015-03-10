<?php

// Exit if this file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Replace Default Filters
 */
remove_filter( 'the_title_rss', 'esc_html' );
add_filter( 'the_title_rss', 'esc_xml' );

remove_filter( 'comment_text_rss', 'esc_html' );
add_filter( 'comment_text_rss', 'esc_xml' );

// Format strings for display.
foreach ( array( 'comment_author', 'term_name', 'link_name', 'link_description', 'link_notes', 'bloginfo', 'wp_title', 'widget_title' ) as $filter ) {
	remove_filter( $filter, 'esc_html' );
	remove_filter( $filter, 'esc_xml' );
}

/**
 * GUID
 * @pending 31080
 */
remove_filter( 'the_guid', 'esc_url' );
function html_escape_the_guid( $guid ) {
	return esc_html( $guid );
}
add_filter( 'the_guid', 'html_escape_the_guid' );