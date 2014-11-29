<?php
/**
 * Unit Tests
 *
 * @package AutoClassifiedsPlatform
 * @copyright 2014 Internet Brands, Inc. All Rights Reserved.
 */
namespace Simplex\Tests;

use Exception;
use RuntimeException;
use Simplex\Framework;
use Simplex\ResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * FrameworkTest
 *
 * @author Michael Funk <mike.funk@internetbrands.com>
 */
class FrameworkTest extends \PHPUnit_Framework_TestCase
{

    /**
     * testNotFoundHandling
     *
     * @return void
     */
    public function testNotFoundHandling()
    {
        // assemble
        $eventDispatcher = $this->getMock(
            'Symfony\Component\EventDispatcher\EventDispatcherInterface'
        );
        $framework = $this->getFrameworkForException(
            new ResourceNotFoundException(),
            $eventDispatcher
        );

        // action
        $response = $framework->handle(new Request());

        // assert
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * testErrorHandling
     *
     * @return void
     */
    public function testErrorHandling()
    {
        // assemble
        $eventDispatcher = $this->getMock(
            'Symfony\Component\EventDispatcher\EventDispatcherInterface'
        );
        $framework = $this->getFrameworkForException(
            new RuntimeException(),
            $eventDispatcher
        );

        // action
        $response = $framework->handle(new Request());

        // assert
        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * testClosureControllerValidResponse
     *
     * @return void
     */
    public function testClosureControllerValidResponse()
    {
        // assemble
        // mock UrlMatcher
        $urlMatcher = $this->getMock(
            'Symfony\Component\Routing\Matcher\UrlMatcherInterface'
        );
        $request = new Request();
        $response = new Response('Hello Dork');

        // it should call match and get a valid route
        $urlMatcher->expects($this->once())
            ->method('match')
            ->will($this->returnValue(
                [
                    '_route' => 'foo',
                    'name' => 'Dork',
                    '_controller' => function ($name) use ($response) {
                        return $response;
                    }
                ]
            ));

        // it should dispatch a response event
        $eventDispatcher = $this->setupEventDispatcher($request, $response);

        // action
        $controllerResolver = new ControllerResolver();
        $framework = new Framework($urlMatcher, $controllerResolver, $eventDispatcher);
        $response = $framework->handle($request);

        // assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Hello Dork', $response->getContent());
    }

    /**
     * getFrameworkForException
     *
     * @private
     * @param Exception $exception
     * @param EventDispatcherInterface $eventDispatcher
     * @return Framework
     */
    private function getFrameworkForException(Exception $exception, EventDispatcherInterface $eventDispatcher)
    {
        $urlMatcher = $this->getMock(
            'Symfony\Component\Routing\Matcher\UrlMatcherInterface'
        );
        $urlMatcher->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception));

        $controllerResolver = $this->getMock(
            'Symfony\Component\HttpKernel\Controller\ControllerResolverInterface'
        );

        // it should dispatch a response event
        $eventDispatcher = $this->setupEventDispatcher(new Request(), new Response());

        return new Framework($urlMatcher, $controllerResolver, $eventDispatcher);
    }

    /**
     * setupEventDispatcher
     *
     * @param Request $request
     * @param Response $response
     * @return EventDispatcher
     */
    private function setupEventDispatcher(Request $request, Response $response)
    {
        $eventDispatcher = $this->getMock(
            'Symfony\Component\EventDispatcher\EventDispatcherInterface'
        );
        $eventDispatcher->expects($this->once())
            ->method('dispatch');
        return $eventDispatcher;
    }
}
