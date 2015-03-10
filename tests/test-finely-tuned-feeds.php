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
		$title         = '& > test <';
		$title_encoded = '&amp; &gt; test &lt;';

		$this->assertEquals( $title, apply_filters( 'wp_title_rss', $title ) );
		$this->assertEquals( $title_encoded, apply_filters( 'the_title_rss', $title ) );

		$title         = '<test>This is a test</test>';
		$title_encoded = '&lt;test&gt;This is a test&lt;/test&gt;';

		$this->assertEquals( $title, apply_filters( 'wp_title_rss', $title ) );
		$this->assertEquals( $title_encoded, apply_filters( 'the_title_rss', $title ) );

		$title         = 'Use <h1> to <h6> for headings, <p> for paragraphy, but not formatting';
		$title_encoded = 'Use &lt;h1&gt; to &lt;h6&gt; for headings, &lt;p&gt; for paragraphy, but not formatting';

		$this->assertEquals( $title, apply_filters( 'wp_title_rss', $title ) );
		$this->assertEquals( $title_encoded, apply_filters( 'the_title_rss', $title ) );
	}
	*/

}