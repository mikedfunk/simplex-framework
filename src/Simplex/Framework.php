<?php
/**
 * Example "simplex" framework
 *
 * @package AutoClassifiedsPlatform
 * @copyright 2014 Internet Brands, Inc. All Rights Reserved.
 */
namespace Simplex;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

/**
 * Framework
 *
 * @author Michael Funk <mike.funk@internetbrands.com>
 */
class Framework
{

    /**
     * url matcher
     *
     * @var UrlMatcherInterface
     */
    protected $urlMatcher;

    /**
     * controller resolver
     *
     * @var ControllerResolverInterface
     */
    protected $controllerResolver;

    /**
     * dependency injection
     *
     * @param UrlMatcherInterface $urlMatcher
     * @param ControllerResolverInterface $controllerResolver
     * @return void
     */
    public function __construct(
        UrlMatcherInterface $urlMatcher,
        ControllerResolverInterface $controllerResolver
    ) {
        $this->urlMatcher = $urlMatcher;
        $this->controllerResolver = $controllerResolver;
    }

    /**
     * handle a request
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        try {
            // add request attributes based on the matched route, which is based
            // on the url
            $request->attributes->add($this->urlMatcher->match($request->getPathInfo()));

            // get the controller based on the _controller attribute in the
            // route, which has been sent into the request
            $controller = $this->controllerResolver->getController($request);

            // call_user_func_array gives the flexibility to pass an instance,
            // use a closure, use an object -> method, or just call a function.
            $arguments = $this->controllerResolver->getArguments($request, $controller);

            return call_user_func_array($controller, $arguments);

        } catch (ResourceNotFoundException $e) {

            return new Response('Not Found', 404);

        } catch (Exception $e) {

            return new Response('An error occurred', 500);
        }
    }
}
