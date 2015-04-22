<?php

namespace Finely_Tuned_Feeds;

// Exit if this file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get the timestamp of the most recently modified post from WP_Query
 *
 * If viewing a comment feed, the date of the most recently modified
 * comment will be returned.
 *
 * @since 4.3.0
 *
 * @return string Date ('Y-m-d H:i:s' for use with mysql2date() )
 */
function get_last_build_date_feed() {
	global $wp_query, $wpdb;

	if ( $wp_query->have_posts() ) {
		$post_ids = array();
		foreach( $wp_query->posts as $post ) {
			$post_ids[] = $post->ID;
		}
		$postids = implode( "','", $post_ids );

		$max_post_time = $wpdb->get_var( "SELECT MAX($wpdb->posts.post_modified_gmt) FROM $wpdb->posts WHERE $wpdb->posts.ID IN ('$postids')" );

		if( $wp_query->is_comment_feed() ) {
			$max_comment_time = $wpdb->get_var( "SELECT MAX($wpdb->comments.comment_date_gmt) FROM $wpdb->comments WHERE $wpdb->comments.comment_post_ID IN ('$postids')" );
			return max( $max_post_time, $max_comment_time );
		}
		return $max_post_time;
	}

	// Fallback to last time any post was modified or published.
	return get_lastpostmodified( 'GMT' );
}

/**
 * Removes otherwise valid utf8 characters that break XML output.
 *
 * https://core.trac.wordpress.org/attachment/ticket/19998/19998b.patch
 *
 * When outputting user supplied content in an XML context we should strip these control and other unwanted characters - they are unprintable and just break feed parsers.
 *
 * @since 0.1.0
 *
 * @param string $string User supplied content that may contain dis-allowed characters.
 * @return string Filtered string with space in place of removed characters.
 */
function strip_for_xml( $string ) {
// Store the site charset as a static to avoid multiple calls to get_option()
	static $is_utf8;
	if ( ! isset( $is_utf8 ) ) {
		$is_utf8 = in_array( get_option( 'blog_charset' ), array( 'utf8', 'utf-8', 'UTF8', 'UTF-8' ) );
	}
	if ( ! $is_utf8 ) {
		return $string;
	}
	return preg_replace( '/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string );
}

/**
 * Escaping for XML blocks.
 *
 * @since 0.1.0
 *
 * @param string $text
 * @return string
 */
function esc_xml( $text ) {

	// Checks for invalid UTF8 in a string. This can be one step in sanitizing input data. For complete sanitizing, including checking for valid UTF8, use one of the sanitize_*() functions.
	$safe_text = wp_check_invalid_utf8( $text );

	//Converts a number of special characters into their HTML entities
	$safe_text = _wp_specialchars( $safe_text, ENT_QUOTES );

	$valid_xml_entities = array( '&quot;', '&amp;', '&apos;', '&lt;', '&gt;' );
	/**
	 * Filter a string cleaned and escaped for output in XML.
	 *
	 * Text passed to esc_xml() is stripped of invalid or special characters
	 * before output.
	 *
	 * @since 4.2.0
	 *
	 * @param string $safe_text The text after it has been escaped.
 	 * @param string $text      The text prior to being escaped.
	 */
	return apply_filters( 'esc_xml', $safe_text, $text );
}

/**
 * [strip_non_xml_entities description]
 * @param  [type] $text [description]
 * @return [type]       [description]
 */
function strip_non_xml_entities( $text ) {
	//&amp;, &quot;, &lt;, &gt;, &apos;
	//return str_replace("&ndash;", "", $text );

    $str = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    $str = preg_replace_callback("/(&#[0-9]+;)/", function($m) { return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES"); }, $str);
    return $str;
}

function patch_31190( $text ) {
	/**
	 * 31190 - Solves ndash problem
	 *
	 * https://core.trac.wordpress.org/ticket/31190
	 */
	$safe_text = wp_kses_normalize_entities( $text );
	$safe_text = str_replace( array("&amp;ndash"), "&amp; &ndash", $safe_text );
	$safe_text = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $safe_text);

	return $safe_text;
}