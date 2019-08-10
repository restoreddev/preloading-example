<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
    ]
]);
$container = $app->getContainer();

$container['view'] = function ($container) {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates/');
};

$app->get('/', function (Request $request, Response $response, $args) use ($container) {
    return $this->view->render($response, 'index.phtml');
});

$app->run();
