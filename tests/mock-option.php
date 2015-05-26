<?php
global $_CLIMATE_TAGGER_MOCK_OPTIONS;

$_CLIMATE_TAGGER_MOCK_OPTIONS = array();

function mock_get_option( $option, $default = false ) {
	switch ( $option ) {
		case 'climate_tagger_general_settings':
			$defaults = array(
				'limit' => '',
				'token' => '',
			);

			global $_CLIMATE_TAGGER_MOCK_OPTIONS;

			$options = array_merge( $defaults, $_CLIMATE_TAGGER_MOCK_OPTIONS );

			return $options;

		default:
			return "Mock option: $option";
	}
}

$mock_function_args = array(
	'get_option' => '$option,$default = false',
);

include 'define-mock-functions.php';
