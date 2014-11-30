<?php
/**
 * response event
 *
 * @package AutoClassifiedsPlatform
 * @copyright 2014 Internet Brands, Inc. All Rights Reserved.
 */
namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\Event;

/**
 * ResponseEvent
 *
 * @author Michael Funk <mike.funk@internetbrands.com>
 */
class ResponseEvent extends Event
{

    /**
     * request
     *
     * @var Request
     */
    protected $request;

    /**
     * response
     *
     * @var Response
     */
    protected $response;

    /**
     * dependency injection
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function __construct(
        Request $request,
        Response $response
    ) {
        $this->request = $request;
        $this->response = $response;
    }
    /*
     * Getter for Response
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /*
     * Getter for Request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
