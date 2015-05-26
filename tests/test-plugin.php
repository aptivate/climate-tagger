<?php

require_once 'climate-tagger.php';

class PluginTest extends WP_UnitTestCase {

	public function test_can_be_created() {
		$tagger = new ClimateTagger();

		$this->assertThat( $tagger, $this->isInstanceOf( 'ClimateTagger' ) );
	}

	public function test_returns_content_if_filter_error() {
		$tagger = new ClimateTagger();

		add_filter(
			'climate_tagger_content',
			array( $this, 'return_error' ), 1, 2 );

		global $post;
		$post = new StdClass();
		$post->post_title = '';
		$post->post_content = '';

		$response = $tagger->get_reegle_tagger_response();

		$this->assertThat( $response, $this->isInstanceOf( 'WP_Error' ) );
	}

	public function return_error() {
		return new WP_Error();
	}
}
