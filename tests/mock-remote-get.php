<?php
global $_CLIMATE_TAGGER_MOCK_URL;

function mock_wp_remote_get( $url ) {
	global $_CLIMATE_TAGGER_MOCK_URL;

	$_CLIMATE_TAGGER_MOCK_URL = $url;

	$response['response']['code'] = 200;
	$response['body']             = json_encode( array( 'Climate Change Adaptation' ) );

	return $response;
}

$mock_function_args = array(
	'wp_remote_get' => '$url',
);

include 'define-mock-functions.php';
