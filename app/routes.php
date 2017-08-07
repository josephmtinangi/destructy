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
    $this->mail->messages()->send($this->config->get('services.mailgun.domain'), [
        'from' => 'noreply@destructy.rf.gd',
        'to' => 'josephmtinangi@gmail.com',
        'subject' => 'New message from Destructy',
        'html' => $this->view->fetch('emails/message.twig', [
            'hash' => $hash,
        ]),
    ]);

    $this->phpmailer->addAddress('noreply@destructy.rf.gd', 'Joseph');
    $this->phpmailer->Subject = 'New message from Destructy';
    $this->phpmailer->Body = $this->view->fetch('emails/message.twig', ['hash' => $hash]);

    if (!$this->phpmailer->send()){
        echo 'Message could not be sent.!';
        echo 'Mailer Error: ' . $this->phpmailer->ErrorInfo;
    }else{
        echo 'Message has been sent';
    }    

    $this->flash->addMessage('global', 'Message Sent!');
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
