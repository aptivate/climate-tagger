<?php

require_once 'climate-tagger.php';
require_once 'mock-remote-post.php';

class PluginTest extends WP_UnitTestCase {

	public function test_can_be_created() {
		$tagger = new ClimateTagger();

		$this->assertThat( $tagger, $this->isInstanceOf( 'ClimateTagger' ) );
	}

	public function test_returns_content_if_filter_error() {
		$tagger = new ClimateTagger();

		add_filter(
			'climate-tagger-content',
			array( $this, 'return_error' ), 1, 2 );

		$this->get_new_post();

		$response = $tagger->get_reegle_tagger_response();

		$this->assertThat( $response, $this->isInstanceOf( 'WP_Error' ) );
	}

	public function return_error() {
		return new WP_Error();
	}

	public function test_text_includes_title() {
		$tagger = new ClimateTagger();

		add_filter(
			'climate_tagger_content',
			array( $this, 'return_error' ), 1, 2 );

		$post = $this->get_new_post();
		$post->post_title = 'FEATURE: Three Steps to Decarbonising Development for a Zero-Carbon Future';

		$tagger->get_reegle_tagger_response();

		global $_CLIMATE_TAGGER_MOCK_POST;

		$text = $_CLIMATE_TAGGER_MOCK_POST['text'];

		$this->assertThat(
			$text,
			$this->stringContains(
				'FEATURE: Three Steps to Decarbonising Development for a Zero-Carbon Future'
			)
		);
	}

	private function get_new_post() {
		global $post;
		$post = new StdClass();
		$post->post_title = '';
		$post->post_content = '';

		return $post;
	}
}
