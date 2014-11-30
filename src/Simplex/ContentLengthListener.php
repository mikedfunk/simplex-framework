<?php
/**
 * content length event listener
 *
 * @package AutoClassifiedsPlatform
 * @copyright 2014 Internet Brands, Inc. All Rights Reserved.
 */
namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * ContentLengthListener
 *
 * @author Michael Funk <mike.funk@internetbrands.com>
 */
class ContentLengthListener implements EventSubscriberInterface
{

    /**
     * return subscribed event details
     *
     * @static
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return ['response', ['onResponse', -255]];
    }

    /**
     * on response
     *
     * @param ResponseEvent $responseEvent
     * @return void
     */
    public function onResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $headers = $response->headers;

        // set content length if not already set
        if (!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')) {
            $headers->set('Content-Length', strlen($response->getContent()));
        }
    }
}
