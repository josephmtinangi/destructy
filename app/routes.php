<?php

$app->get('/home', function($request, $response, $args) {
    echo $this->config->get('db.mysql.host');  
});
