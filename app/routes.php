<?php

$app->get('/', function ($request, $response, $args) {
    $this->view->render($response, 'home.twig');
})->setName('home');

$app->post('/post', function ($request, $response, $args) use ($app) {
    // Get parameters
    $params = $request->getParams();
    // Generate hash
    $hash = md5(uniqid(true));

    $message = $this->db->prepare("
		INSERT INTO messages(hash, body) 
		VALUES (:hash, :body)
	");

    $message->execute([
        'hash' => $hash,
        'body' => $params['body'],
    ]);

    // send mail
    $this->phpmailer->addAddress($params['email'], '');
    $this->phpmailer->Subject = 'New message from Destructy';
    $this->phpmailer->Body = $this->view->fetch('emails/message.twig', ['hash' => $hash]);

    if (!$this->phpmailer->send()){
        $this->flash->addMessage('global', 'Message failed to be sent to ' . $params['email']);
    }else{
        $this->flash->addMessage('global', 'Message successfully sent to ' . $params['email']);
    }    
    // TODO: Use Slim to redirect
    return $response->withRedirect('/');

})->setName('send');

$app->get('/messages/{hash}', function ($request, $response, $args) {
    $message = $this->db->prepare("
		SELECT body 
		FROM messages
		WHERE hash = :hash;
		DELETE FROM messages
		WHERE hash = :hash;
	");

    $message->execute([
        'hash' => $args['hash'],
    ]);

    $message = $message->fetch(PDO::FETCH_OBJ);

    $this->view->render($response, 'messages/show.twig', [
        'message' => $message,
    ]);
})->setName('messages.show');

$app->get('/phpmailer', function ($request, $response, $args) {

})->setName('phpmailer');
