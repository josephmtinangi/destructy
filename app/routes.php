<?php

$app->get('/', function($request, $response, $args) {
    $this->view->render($response, 'home.twig');
})->setName('home');

$app->post('/post', function($request, $response, $args) use ($app) {
	// Get parameters
	$params = $request->getParams();
	// Generate hash
	$hash = md5(uniqid(true));

	$message = $this->db->prepare("
		INSERT INTO messages(hash, body) 
		VALUES (:hash, :body)
	");

	$message->execute([
		'hash'    => $hash,
		'body' => $params['body'],
	]);


	$this->mail->messages()->send($this->config->get('services.mailgun.domain'), [
	  'from'    => 'noreply@destructy.rf.gd',
	  'to'      => 'josephmtinangi@gmail.com',
	  'subject' => 'New message from Destructy',
	  'html'    => $this->view->fetch('emails/message.twig', [
	  	'hash' => $hash,
	  ]),
	]);

	echo 'Sent';	

	// TODO: Use Slim to redirect
	return $response->withRedirect('/');

})->setName('send');

$app->get('/messages/{hash}', function ($request, $response, $args) {
	$message = $this->db->prepare("
		SELECT body 
		FROM messages
		WHERE hash = :hash
	");

	$message->execute([
		'hash' => $args['hash'],
	]);

	$message = $message->fetch(PDO::FETCH_OBJ);

	$this->view->render($response, 'messages/show.twig', [
		'message' => $message,
	]);
})->setName('messages.show');
