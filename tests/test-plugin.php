<?php

require_once 'climate-tagger.php';
require_once 'mock-remote-post.php';
require_once 'mock-remote-get.php';
require_once 'mock-option.php';

require_once 'ClimateTaggerTestBase.php';

class PluginTest extends ClimateTaggerTestBase {

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

		$response = $tagger->get_climate_tagger_response();

		$this->assertThat( $response, $this->isInstanceOf( 'WP_Error' ) );
	}

	public function return_error() {
		return new WP_Error();
	}

	public function test_text_includes_title() {
		$tagger = new ClimateTagger();

		$post = $this->get_new_post();
		$post->post_title = 'FEATURE: Three Steps to Decarbonising Development for a Zero-Carbon Future';

		$tagger->get_climate_tagger_response();

		global $_CLIMATE_TAGGER_MOCK_POST;

		$text = $_CLIMATE_TAGGER_MOCK_POST['text'];

		$this->assertThat(
			$text,
			$this->stringContains(
				'FEATURE: Three Steps to Decarbonising Development for a Zero-Carbon Future'
			)
		);
	}

	public function test_text_includes_post_content() {
		$tagger = new ClimateTagger();

		$post = $this->get_new_post();

		$content = <<<EOT
A new World Bank report lays out three steps for a smooth transition to a zero-carbon future. Through data, examples and policy advice, it aims to help countries makes the shift. It tells us that to prevent temperatures from rising more than 2 degrees Celsius, the world will need to transform its energy uses and electricity from clean energy sources will play an important role.
EOT;
		$post->post_content = $content;

		$tagger->get_climate_tagger_response();

		global $_CLIMATE_TAGGER_MOCK_POST;

		$text = $_CLIMATE_TAGGER_MOCK_POST['text'];

		$this->assertThat(
			$text,
			$this->stringContains( $content	)
		);
	}

	public function test_by_default_language_is_english() {
		$tagger = new ClimateTagger();

		$this->get_new_post();

		$tagger->get_climate_tagger_response();

		global $_CLIMATE_TAGGER_MOCK_POST;

		$locale = $_CLIMATE_TAGGER_MOCK_POST['locale'];

		$this->assertThat(
			$locale,
			$this->equalTo( 'en' )
		);
	}

	public function test_language_filter_applied() {
		$tagger = new ClimateTagger();

		add_filter(
			'climate-tagger-language',
			array( $this, 'return_spanish' ), 1, 2 );

		$this->get_new_post();

		$tagger->get_climate_tagger_response();

		global $_CLIMATE_TAGGER_MOCK_POST;

		$locale = $_CLIMATE_TAGGER_MOCK_POST['locale'];

		$this->assertThat(
			$locale,
			$this->equalTo( 'es' )
		);
	}

	public function return_spanish() {
		return 'es';
	}

	public function test_format_is_json() {
		$tagger = new ClimateTagger();

		$this->get_new_post();

		$tagger->get_climate_tagger_response();

		global $_CLIMATE_TAGGER_MOCK_POST;

		$format = $_CLIMATE_TAGGER_MOCK_POST['format'];

		$this->assertThat(
			$format,
			$this->equalTo( 'json' )
		);
	}

	public function test_api_token_retrieved_from_options() {
		$tagger = new ClimateTagger();

		$this->set_option( 'token',
			'dfkgjOoldsg3kKD6FSfkp7of9sjs8dofsdjosdfjA' );

		$this->get_new_post();

		$tagger->get_climate_tagger_response();

		global $_CLIMATE_TAGGER_MOCK_POST;

		$token = $_CLIMATE_TAGGER_MOCK_POST['token'];

		$this->assertThat(
			$token,
			$this->equalTo( 'dfkgjOoldsg3kKD6FSfkp7of9sjs8dofsdjosdfjA' )
		);
	}

	public function test_word_limit_retrieved_from_options() {
		$tagger = new ClimateTagger();

		$this->set_option( 'limit',
			'25' );

		$this->get_new_post();

		$tagger->get_climate_tagger_response();

		global $_CLIMATE_TAGGER_MOCK_POST;

		$limit = $_CLIMATE_TAGGER_MOCK_POST['countConcepts'];

		$this->assertThat(
			$limit,
			$this->equalTo( '25' )
		);
	}

	public function test_project_selection_retrieved_from_options() {
		$tagger = new ClimateTagger();

		$this->set_option( 'project',
			'climate_change_adaptation' );

		$this->get_new_post();

		$tagger->get_climate_tagger_response();

		global $_CLIMATE_TAGGER_MOCK_POST;

		$project = $_CLIMATE_TAGGER_MOCK_POST['tagger'];

		$this->assertThat(
			$project,
			$this->equalTo( 'Climate Change Adaptation' )
		);
  }

	public function test_api_url() {
		$tagger = new ClimateTagger();

		$this->get_new_post();

		$tagger->get_climate_tagger_response();

		global $_CLIMATE_TAGGER_MOCK_URL;

		$this->assertThat(
			$_CLIMATE_TAGGER_MOCK_URL,
			$this->equalTo( 'http://api.climatetagger.net/service/extract' )
		);
	}

	public function test_tags_ordered_by_score() {
		$tagger = new ClimateTagger();

		$this->get_new_post();

		global $_CLIMATE_TAGGER_MOCK_RESPONSE;

		$_CLIMATE_TAGGER_MOCK_RESPONSE = array(
			'body' => json_encode(array(
				'concepts' => array(
					array(
						'prefLabel' => 'climate change',
						'score' => 20,
					),
					array(
						'prefLabel' => 'IPPC',
						'score' => 1,
					),
					array(
						'prefLabel' => 'energy',
						'score' => 10,
					),
				))),
			'response' => array(
				'code' => 200,
			)
		);

		ob_start();
		$tagger->box_routine();
		$output = ob_get_contents();
		ob_end_clean();

		$links = $this->get_html_elements_from_output( $output, 'a' );

		$this->assertThat( (string)$links[0], $this->equalTo( 'climate change' ) );
		$this->assertThat( (string)$links[1], $this->equalTo( 'energy' ) );
		$this->assertThat( (string)$links[2], $this->equalTo( 'IPPC' ) );
	}

	public function test_error_returned_if_response_is_error() {
		$tagger = new ClimateTagger();

		$this->get_new_post();

		global $_CLIMATE_TAGGER_MOCK_RESPONSE;

		$error = new WP_Error();
		$error->add( '1', 'An error occurred' );

		$_CLIMATE_TAGGER_MOCK_RESPONSE = $error;

		ob_start();
		$tagger->box_routine();
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertThat( $output, $this->equalTo( 'An error occurred' ) );
	}

	public function test_body_returned_for_non_200_status_code() {
		$tagger = new ClimateTagger();

		$this->get_new_post();

		global $_CLIMATE_TAGGER_MOCK_RESPONSE;

		$_CLIMATE_TAGGER_MOCK_RESPONSE = array(
			'body' => 'Unrecognized API key',
			'response' => array(
				'code' => 403,
			)
		);

		ob_start();
		$tagger->box_routine();
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertThat( $output, $this->equalTo( 'Unrecognized API key' ) );
	}

	public function test_user_prompted_to_save_draft_when_no_tags() {
		$tagger = new ClimateTagger();

		$this->get_new_post();

		global $_CLIMATE_TAGGER_MOCK_RESPONSE;

		$_CLIMATE_TAGGER_MOCK_RESPONSE = array(
			'body' => json_encode(
				array(
					'concepts' => array(),
				)
			),
			'response' => array(
				'code' => 200,
			)
		);

		ob_start();
		$tagger->box_routine();
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertThat(
			$output,
			$this->equalTo( "Click 'Save Draft' to refresh tag suggestions."
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

	private function set_option( $name, $value ) {
		global $_CLIMATE_TAGGER_MOCK_OPTIONS;

		$_CLIMATE_TAGGER_MOCK_OPTIONS[ $name ] = $value;
	}
}
