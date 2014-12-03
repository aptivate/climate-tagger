<?php
/*
  Plugin Name: Climate Tagger
  Description: Recommends tags in a tag cloud based on reegle tagging API.
  Version: 0.1
  Author: Aptivate, Jimmy O'Higgins
*/

if ( is_dir( WPMU_PLUGIN_DIR . '/climate-tagger' ) )
	define( 'CLIMATE_TAGGER_INCLUDES', WPMU_PLUGIN_URL . '/climate-tagger' );
else
	define( 'CLIMATE_TAGGER_INCLUDES', WP_PLUGIN_URL . '/climate-tagger' );

class ClimateTagger {
	function add_box() {
		add_meta_box('boxid',
					 'Suggested Tags',
					 array( ClimateTagger, 'box_routine' ),
					 'post',
					 'side',
					 'low');
	}

	function box_routine() {
		// TODO: Check if we need a limit and what is should be set to
		$limit = 12;
		$tags_post = self::tag_list_generate_post();

		if ( empty( $tags_post ) ) {
			echo "Click 'Save Draft' to refresh tag suggestions.";
			return;
		}

		$tags_rec = $tags_post;

		arsort( $tags_rec );
		array_splice( $tags_rec, $limit );

		//TAG CLOUD
		//Init tag cloud variables
		$min_size = 10;
		$max_size = 24;

		$minimum_strength = min( array_values( $tags_rec ) );
		$maximum_strength = max( array_values( $tags_rec ) );

		$spread = $maximum_strength - $minimum_strength;
		if ( $spread == 0 ) {
			$spread = 1;
		}

		$step = ( $max_size - $min_size ) / $spread;

		//Print tag cloud
		foreach ( $tags_rec as $tag_name => $tag_strength ) {
			$size = $min_size + ($tag_strength - $minimum_strength) * $step;
				?>
				<a href="#" style="font-size: <?php echo "$size"?>pt;" onClick="tag_add('<?php echo $tag_name; ?>');return false;"><?php echo "$tag_name"?></a>
<?php
		}
		//Space between tags
		echo "&nbsp&nbsp&nbsp";
	}

	function tag_list_generate_post() {
		global $post;

		$phrase_length_max = 4;
		$phrases = array();

		//Initialize post content
		$content = $post->post_title .  ' ' . $post->post_content;

		// TODO send $content to tagging API

		// http://api.reegle.info/documentation

		// build $phrases as array of 'word' => strength

		$url = 'http://api.reegle.info/service/extract';

		// TODO: Make option
		$token = '7b8b9ec6eaae437ea8321995aada08fa';

		$fields = array(
			'text' => $content,
			'locale' => 'en', // TODO get language of post
			'format' => 'json',
			'token' => $token,
			'countConcepts' => 100,
		);

		$response = wp_remote_post( $url, array( 'body' => $fields ) );

		// TODO - check for unexpected response or site down

		$result = json_decode( $response['body'], true );

		$concepts = $result['concepts'];

		$tags = array();

		foreach ( $concepts as $concept ) {
			$tags[ $concept['prefLabel'] ] = $concept['score'];
		}

		return $tags;
	}

	function admin_add_my_script()
	{
		wp_enqueue_script( 'climate-tagger-add-tag', CLIMATE_TAGGER_INCLUDES . '/climate-tagger-add-tag.js',
						   array( 'jquery' ) );
	}
}

if ( is_admin() ) {
	add_action( 'admin_menu',
				array( 'ClimateTagger', 'add_box' ) );
	add_action( 'admin_print_scripts',
				array( 'ClimateTagger', 'admin_add_my_script' ) );
}
?>
