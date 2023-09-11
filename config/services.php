<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => '',
		'secret' => '',
	],

	'mandrill' => [
		'secret' => '',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'User',
		'secret' => '',
	],

	/*'vkontakte' => [
		'client_id' 	=> env('VK_CLIENT_ID'),
		'client_secret' => env('VK_CLIENT_SECRET'),
		'redirect' 		=> env('VK_REDIRECT'),
		'scope' => env('VK_SCOPE'),
	],*/
	'facebook' => [
		'client_id'     => env('FB_CLIENT_ID'),
		'client_secret' => env('FB_CLIENT_SECRET'),
		'redirect'      => env('FB_REDIRECT'),
	],

];
