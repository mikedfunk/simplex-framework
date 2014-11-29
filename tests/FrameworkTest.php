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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpFoundation\Response;

/**
 * FrameworkTest
 *
 * @author Michael Funk <mike.funk@internetbrands.com>
 */
class FrameworkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * phpunit setup
     *
     * @return void
     */
    public function setUp()
    {

    }

    /**
     * phpunit teardown
     *
     * @return void
     */
    public function tearDown()
    {

    }


    /**
     * testNotFoundHandling
     *
     * @return void
     */
    public function testNotFoundHandling()
    {
        // assemble
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());

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
        $framework = $this->getFrameworkForException(new RuntimeException());

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
        // define a new fake route that will be matched when we call match
        $urlMatcher->expects($this->once())
            ->method('match')
            ->will($this->returnValue(
                [
                    '_route' => 'foo',
                    'name' => 'Dork',
                    '_controller' => function ($name) {
                        return new Response('Hello ' . $name);
                    }
                ]
            ));

        // action
        $controllerResolver = new ControllerResolver();
        $framework = new Framework($urlMatcher, $controllerResolver);
        $response = $framework->handle(new Request());

        // assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Hello Dork', $response->getContent());
    }

    /**
     * getFrameworkForException
     *
     * @private
     * @return Framework
     */
    private function getFrameworkForException(Exception $exception)
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

        return new Framework($urlMatcher, $controllerResolver);
    }
}
