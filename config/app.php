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
    	],
        'smtp2go' => [
            'host' => $_ENV['SMTP2GO_HOST'],
            'username' => $_ENV['SMTP2GO_USERNAME'],
            'password' => $_ENV['SMTP2GO_PASSWORD'],
            'secure' => $_ENV['SMTP2GO_SECURE'],
            'port' => $_ENV['SMTP2GO_PORT'],
            'from' => $_ENV['SMTP2GO_FROM'],
            'fromName' => $_ENV['SMTP2GO_FROM_NAME'],
        ],
    ],
];