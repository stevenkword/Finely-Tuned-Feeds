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
	/**
	 * Filter a string cleaned and escaped for output in HTML.
	 *
	 * Text passed to esc_html() is stripped of invalid or special characters
	 * before output.
	 *
	 * @since 2.8.0
	 *
	 * @param string $safe_text The text after it has been escaped.
 	 * @param string $text      The text prior to being escaped.
	 */
	return apply_filters( 'esc_html', $safe_text, $text );
}