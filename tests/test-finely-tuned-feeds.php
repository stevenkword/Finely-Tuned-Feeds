<?php

class Tests_Finely_Tuned_Feeds extends WP_UnitTestCase {

	// https://core.trac.wordpress.org/attachment/ticket/31190/31190.tests.diff
	function test_lone_ampersand() {
		$actual = esc_xml( 'A & B' );
		$expected = "A &amp; B";
		$this->assertEquals( $expected, $actual );
	}

	// https://core.trac.wordpress.org/attachment/ticket/31190/31190.tests.diff
	function test_lone_esc_ampersand() {
		$actual = esc_xml( 'A &amp; B' );
		$expected = "A &amp; B";
		$this->assertEquals( $expected, $actual );
	}

	// https://core.trac.wordpress.org/attachment/ticket/31190/31190.tests.diff
	function test_lone_ndash() {
		$actual = esc_xml( 'A &ndash; B' );
		$expected = "A &ndash; B";
		$this->assertEquals( $expected, $actual );
	}

	// https://core.trac.wordpress.org/attachment/ticket/31190/31190.tests.diff
	function test_ampersand_and_ndash() {
		$actual = esc_xml( 'A &amp;ndash; B' );
		$expected = "A &amp;ndash; B";
		$this->assertEquals( $expected, $actual );
	}

	// https://core.trac.wordpress.org/attachment/ticket/31190/31190.tests.diff
	function test_ampersand_and_ndash_missing_semicolon() {
		$actual = esc_xml( 'A &amp;ndash B' );
		$expected = "A &amp;ndash B";
		$this->assertEquals( $expected, $actual );
	}

	/**
	 * https://core.trac.wordpress.org/ticket/9992
	 * @return [type] [description]
	 */
	function test_ticket_9992() {
		$actual = apply_filters( 'the_title_rss', '> & test' );
		$expected = '> & test';
		$this->assertEquals( $expected, $actual );

		$actual = apply_filters( 'the_title_rss', 'Johnson & Johnson' );
		$expected = 'Johnson & Johnson';
		$this->assertEquals( $expected, $actual );
	}

	/**
	 * https://core.trac.wordpress.org/ticket/9993
	 * @return [type] [description]
	 */
	function test_ticket_9993() {
		$actual = apply_filters( 'the_title_rss', '& > test <' );
		$expected = '& > test <';
		$this->assertEquals( $expected, $actual );

		$actual = apply_filters( 'the_title_rss', '<test>This is a test</test>' );
		$expected = '<test>This is a test</test>';
		$this->assertEquals( $expected, $actual );
	}
}