<?php
global $_CLIMATE_TAGGER_MOCK_POST;
global $_CLIMATE_TAGGER_MOCK_URL;
global $_CLIMATE_TAGGER_MOCK_RESPONSE;

function mock_wp_remote_post( $url, $body ) {
	global $_CLIMATE_TAGGER_MOCK_POST;
	global $_CLIMATE_TAGGER_MOCK_URL;
	global $_CLIMATE_TAGGER_MOCK_RESPONSE;

	$_CLIMATE_TAGGER_MOCK_URL = $url;
	$_CLIMATE_TAGGER_MOCK_POST = $body['body'];

	return $_CLIMATE_TAGGER_MOCK_RESPONSE;
}

$mock_function_args = array(
	'wp_remote_post' => '$url, $body',
);

include 'define-mock-functions.php';
