<?php

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

// get routes and service container with services registered
$serviceContainer = include __DIR__ . '/../src/container.php';

// %routes% is defined in container.php when instantiating the url matcher.
// this injects our routes into the matcher.
$serviceContainer->setParameter('routes', include __DIR__ . '/../src/app.php');

// get the request, set up the response, and send it to the browser
$request = Request::createFromGlobals();
$response = $serviceContainer->get('framework')->handle($request);
$response->send();
