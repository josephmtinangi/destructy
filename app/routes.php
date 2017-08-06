<?php

$app->get('/home', function($request, $response, $args) {

	var_dump($this->db->query('SELECT 1')->fetch(PDO::FETCH_OBJ));

    $this->view->render($response, 'home.twig');
});
