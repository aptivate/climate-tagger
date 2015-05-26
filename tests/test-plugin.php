<?php

require_once 'climate-tagger.php';

class PluginTest extends WP_UnitTestCase {

	public function testCanBeCreated() {
		$tagger = new ClimateTagger();

		$this->assertThat( $tagger, $this->isInstanceOf( 'ClimateTagger' ) );
	}
}
