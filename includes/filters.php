<?php

// Exit if this file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

// Debug catch all that outputs "DEBUG" on all esc_xml fields.
//add_filter( 'esc_xml', function() { return 'DEBUG'; } );

/**
 * Escape the GUID with XML
 *
 * Acts on changeset 31726
 * https://core.trac.wordpress.org/changeset/31726
 */
function xml_escape_the_guid( $guid ) {
	global $wp_query;
	return $wp_query->is_author();
	return esc_xml( $guid );
}