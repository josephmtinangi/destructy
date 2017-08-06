<?php

require_once '../vendor/autoload.php';

$container = new \Slim\Container;

$container['config'] = function($c) {
  return new \Noodlehaus\Config('../config/app.php');  
};

$app = new \Slim\App($container);

require_once "routes.php";
