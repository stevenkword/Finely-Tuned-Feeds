<?php

// Exit if this file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Escaping for XML blocks.
 *
 * @since 2.8.0
 *
 * @param string $text
 * @return string
 */
function esc_xml( $text ) {
	$safe_text = wp_check_invalid_utf8( $text );
	$safe_text = _wp_specialchars( $safe_text, ENT_QUOTES );

	// Store the site charset as a static to avoid multiple calls to get_option()
	// https://core.trac.wordpress.org/attachment/ticket/19998/19998b.patch
	static $is_utf8;
	if ( ! isset( $is_utf8 ) ) {
		$is_utf8 = in_array( get_option( 'blog_charset' ), array( 'utf8', 'utf-8', 'UTF8', 'UTF-8' ) );
	}
	if ( $is_utf8 ) {
		$safe_text = preg_replace( '/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $safe_text );
	}

	/**
	 * Filter a string cleaned and escaped for output in HTML.
	 *
	 * Text passed to esc_html() is stripped of invalid or special characters
	 * before output.
	 *
	 * @since 4.2.0
	 *
	 * @param string $safe_text The text after it has been escaped.
 	 * @param string $text      The text prior to being escaped.
	 */
	return apply_filters( 'esc_xml', $safe_text, $text );
}