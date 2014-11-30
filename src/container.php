<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

// get a service container
$serviceContainer = new ContainerBuilder();

// register the http request context, url matcher, and controller resolver
$serviceContainer->register('context', 'Symfony\Component\Routing\RequestContext');
$serviceContainer->register('matcher', 'Symfony\Component\Routing\Matcher\UrlMatcher')
    ->setArguments(['%routes%', new Reference('context')]);
$serviceContainer->register('resolver', 'Symfony\Component\HttpKernel\Controller\ControllerResolver');

// register event listeners
$serviceContainer->register('listener.router', 'Symfony\Component\HttpKernel\EventListener\RouterListener')
    ->setArguments([new Reference('matcher')]);
// %% arguments need to be set separately
$serviceContainer->register('listener.response', 'Symfony\Component\HttpKernel\EventListener\ResponseListener')
    ->setArguments(['%charset%']);
// This can be overridden somewhere else
$serviceContainer->setParameter('charset', 'UTF-8');
$serviceContainer->register('listener.exception', 'Symfony\Component\HttpKernel\EventListener\ExceptionListener')
    ->setArguments(['Calendar\\Controller\\ErrorController::exceptionAction']);
$serviceContainer->register('listener.string_response', 'Simplex\StringResponseListener');

// register the event dispatcher with the listeners
$serviceContainer->register('dispatcher', 'Symfony\Component\EventDispatcher\EventDispatcher')
    ->addMethodCall('addSubscriber', [new Reference('listener.router')])
    ->addMethodCall('addSubscriber', [new Reference('listener.response')])
    ->addMethodCall('addSubscriber', [new Reference('listener.exception')])
    ->addMethodCall('addSubscriber', [new Reference('listener.string_response')]);

// register the framework itself
$serviceContainer->register('framework', 'Simplex\Framework')
    ->setArguments([new Reference('dispatcher'), new Reference('resolver')]);

return $serviceContainer;
