<?php

require_once '../vendor/autoload.php';

$Loader = new josegonzalez\Dotenv\Loader('../.env');
// Parse the .env file
$Loader->parse();
// Send the parsed .env file to the $_ENV variable
$Loader->toEnv();

return [
    'url' => $_ENV['APP_URL'],
    'db'  => [
        'mysql' => [
            'host'     => $_ENV['DB_HOST'],
            'dbname'   => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
        ],    
    ],
    'services' => [
    	'mailgun' => [
    		'domain' => $_ENV['MAILGUN_DOMAIN'],
    		'secret' => $_ENV['MAILGUN_SECRET'],
    	]
    ],
];