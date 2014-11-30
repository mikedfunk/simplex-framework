<?php

use Simplex\ContentLengthListener;
use Simplex\GoogleListener;
use Simplex\ResponseEvent;
use Simplex\StringResponseListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\EventListener\StreamedResponseListener;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\HttpCache\Esi;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require_once __DIR__ . '/../vendor/autoload.php';

// instantiate request and response
$request = Request::createFromGlobals();
$routes = include __DIR__ . '/../src/app.php';

// set up some building blocks
$requestContext = new RequestContext();
$urlMatcher = new UrlMatcher($routes, $requestContext);
$controllerResolver = new ControllerResolver();

// subscribe to events
$eventDispatcher = new EventDispatcher();
$eventDispatcher->addSubscriber(new RouterListener($urlMatcher));

// exception handler
$exceptionHandler = 'Calendar\\Controller\\ErrorController::exceptionAction';
$eventDispatcher->addSubscriber(new ExceptionListener($exceptionHandler));

// convert string to response
$eventDispatcher->addSubscriber(new StringResponseListener());

// let the framework take it from here
$framework = new Simplex\Framework($eventDispatcher, $controllerResolver);
$framework = new HttpCache($framework, new Store(__DIR__ . '/../cache'));
$response = $framework->handle($request);

// ensure the response is compliant with the http spec
$eventDispatcher->addSubscriber(new ResponseListener('UTF-8'));

// support streamed responses
$eventDispatcher->addSubscriber(new StreamedResponseListener());

$response->send();
