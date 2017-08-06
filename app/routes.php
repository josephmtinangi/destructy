<?php

$app->get('/home', function($request, $response, $args) {
    // $this->view->render($response, 'home.twig'); 
    echo 'home';
});
