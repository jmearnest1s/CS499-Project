<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */
	
	'linkedin' => [
		'client_id' => '78p31nrctmpkbn',         // Your linkedin Client ID
		'client_secret' => 'uYyhn7ESmc5l5J4f', // Your linkedin Client Secret
		'redirect' => 'https://agwiki.com/login/linkedin/callback',
	],
	
	'facebook' => [
		'client_id' => '1218651891634035',         // Your linkedin Client ID
		'client_secret' => 'd19894cda2bd1a629b9d154f93c36d5b', // Your linkedin Client Secret
		'redirect' => 'https://agwiki.com/login/facebook/callback/',
	],


    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
