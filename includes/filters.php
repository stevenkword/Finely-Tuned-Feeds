<?php

// Exit if this file is directly accessed
if ( ! defined( 'ABSPATH' ) ) exit;

// GUID
remove_filter( 'the_guid', 'esc_url' );
function html_escape_the_guid( $guid ) {
	return esc_html( $guid );
}
add_filter( 'the_guid', 'html_escape_the_guid' );