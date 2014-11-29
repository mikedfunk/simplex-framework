<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$routes = new RouteCollection();
$routes->add(
    'hello',
    new Route(
        '/hello/{name}',
        [
            'name' => 'World',
            '_controller' => 'render_template',
        ]
    )
);
$routes->add(
    'bye',
    new Route(
        '/bye',
        [
            '_controller' => 'render_template',
        ]
    )
);
$routes->add(
    'leap_year',
    new Route(
        '/is-leap-year/{year}',
        [
            'year' => null,
            '_controller' => 'Calendar\\Controller\\LeapYearController::indexAction'
        ]
    )
);

return $routes;
