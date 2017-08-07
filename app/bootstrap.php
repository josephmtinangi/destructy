<?php

require_once '../vendor/autoload.php';

$container = new \Slim\Container;

$container['config'] = function($c) {
  return new \Noodlehaus\Config('../config/app.php');  
};

$container['flash'] =  function($c) {
    session_start();
    
    return new \Slim\Flash\Messages();
};

$container['view'] = function($c) {
    $view = new \Slim\Views\Twig('../resources/views');
    
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['config']->get('url')
    ));
    
    $view->getEnvironment()->addGlobal('flash', $c['flash']);

    return $view;
};

$container['db'] = function ($c) {
	return new PDO('mysql:host='. $c['config']->get('db.mysql.host') .';dbname='. $c['config']->get('db.mysql.dbname'), 
		$c['config']->get('db.mysql.username'), 
		$c['config']->get('db.mysql.password')
		);
};

$container['mail'] = function ($c) {
    return \Mailgun\Mailgun::create($c['config']->get('services.mailgun.secret'));
};

$container['phpmailer'] = function ($c) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    // $mail->SMTPDebug = 2;
    $mail->Host = $c['config']->get('services.smtp2go.host');
    $mail->SMTPAuth = true;
    $mail->Username = $c['config']->get('services.smtp2go.username');
    $mail->Password = $c['config']->get('services.smtp2go.password');
    $mail->SMTPSecure = $c['config']->get('services.smtp2go.secure');
    $mail->Port = $c['config']->get('services.smtp2go.port');

    $mail->isHTML(true);

    $mail->setFrom(
       $c['config']->get('services.smtp2go.from') , 
       $c['config']->get('services.smtp2go.fromName')
    );  

    return $mail;
};

$app = new \Slim\App($container);

require_once "routes.php";
