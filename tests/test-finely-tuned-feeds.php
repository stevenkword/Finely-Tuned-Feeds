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
	 * Test potential encoding and formatting problems.
	 * @return [type] [description]
	 */
	/*
	function test_htmlentities() {

		// Title test for #9993, 9992
		$actual = esc_xml( '& > test <' );
		$expected = '&amp; &gt; test &lt;';
		$this->assertEquals( $expected, $actual );

		$actual = esc_xml( '<test>This is a test</test>' );
		$expected = '&lt;test&gt;This is a test&lt;/test&gt;';
		$this->assertEquals( $expected, $actual );

		$actual = esc_xml( 'Use <h1> to <h6> for headings, <p> for paragraphy, but not formatting' );
		$expected = 'Use &lt;h1&gt; to &lt;h6&gt; for headings, &lt;p&gt; for paragraphy, but not formatting';
		$this->assertEquals( $expected, $actual );
	}
	*/

}