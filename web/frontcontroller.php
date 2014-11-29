<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require_once __DIR__ . '/../vendor/autoload.php';

// instantiate request and response
$request = Request::createFromGlobals();
$routes = include __DIR__ . '/../src/app.php';

// set up some building blocks
$requestContext = new RequestContext();
$requestContext->fromRequest($request);
$urlMatcher = new UrlMatcher($routes, $requestContext);
$controllerResolver = new ControllerResolver();

// let the framework take it from here
$framework = new Simplex\Framework($urlMatcher, $controllerResolver);
$response = $framework->handle($request);

$response->send();

/**
 * render_template
 *
 * @param Request $request
 * @access public
 * @return Response
 */
function render_template(Request $request)
{
    extract($request->attributes->all(), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__ . '/../src/pages/%s.php', $_route);

    return new Response(ob_get_clean());
}
