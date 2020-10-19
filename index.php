<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require 'framework/Core.php';

$app = new Framework\Core();

// Register routes
$app->get('/', function () {
    return new Response('Hello world.');
});

$app->get('hello/{name}', function ($name) {
    return new Response('Hello ' . $name);
});

$request = Request::createFromGlobals();
$response = $app->handle($request);
$response->send();
