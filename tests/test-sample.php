<?php

class Tests_Finely_Tuned_Feeds extends WP_UnitTestCase {

	// https://core.trac.wordpress.org/attachment/ticket/31190/31190.tests.diff
	function test_lone_ampersand() {
		$actual = esc_html( 'A & B' );
		$expected = "A &amp; B";
		$this->assertEquals( $expected, $actual );
	}

	function test_lone_esc_ampersand() {
		$actual = esc_html( 'A &amp; B' );
		$expected = "A &amp; B";
		$this->assertEquals( $expected, $actual );
	}

	function test_lone_ndash() {
		$actual = esc_html( 'A &ndash; B' );
		$expected = "A &ndash; B";
		$this->assertEquals( $expected, $actual );
	}

	function test_ampersand_and_ndash() {
		$actual = esc_html( 'A &amp;ndash; B' );
		$expected = "A &amp;ndash; B";
		$this->assertEquals( $expected, $actual );
	}

	function test_ampersand_and_ndash_missing_semicolon() {
		$actual = esc_html( 'A &amp;ndash B' );
		$expected = "A &amp;ndash B";
		$this->assertEquals( $expected, $actual );
	}

}