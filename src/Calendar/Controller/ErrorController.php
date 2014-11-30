<?php
/**
 * error controller
 *
 * @package AutoClassifiedsPlatform
 * @copyright 2014 Internet Brands, Inc. All Rights Reserved.
 */
namespace Calendar\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\FlattenException;

/**
 * ErrorController
 *
 * @author Michael Funk <mike.funk@internetbrands.com>
 */
class ErrorController
{

    /**
     * exception action
     *
     * @param FlattenException $exception
     * @return Response
     */
    public function exceptionAction(FlattenException $exception)
    {
        $message = 'Something went crazy: ' . $exception->getMessage();

        return new Response($message, $exception->getStatusCode());
    }
}
