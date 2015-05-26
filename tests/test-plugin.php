<?php

require_once 'climate-tagger.php';

class PluginTest extends WP_UnitTestCase {

	public function test_can_be_created() {
		$tagger = new ClimateTagger();

		$this->assertThat( $tagger, $this->isInstanceOf( 'ClimateTagger' ) );
	}
}
