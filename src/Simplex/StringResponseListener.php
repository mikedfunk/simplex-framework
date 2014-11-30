<?php
/**
 * convert string to a response
 *
 * @package AutoClassifiedsPlatform
 * @copyright 2014 Internet Brands, Inc. All Rights Reserved.
 */
namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Response;

/**
 * StringResponseListener
 *
 * @author Michael Funk <mike.funk@internetbrands.com>
 */
class StringResponseListener implements EventSubscriberInterface
{

    /**
     * on kernel view
     *
     * @param GetResponseForControllerResultEvent $event
     * @return void
     */
    public function onView(GetResponseForControllerResultEvent $event)
    {
        $response = $event->getControllerResult();

        if (is_string($response)) {
            $response = new Response($response);

            // set time-to-live for http caching to 10 seconds
            // i know, violates SRP
            $response->setTtl(10);

            $event->setResponse($response);
        }
    }

    /**
     * get subscribed events
     *
     * @static
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return ['kernel.view' => 'onView'];
    }
}
