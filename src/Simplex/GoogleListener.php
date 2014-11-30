<?php
/**
 * google analytics listener
 *
 * @package AutoClassifiedsPlatform
 * @copyright 2014 Internet Brands, Inc. All Rights Reserved.
 */
namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;


/**
 * GoogleListener
 *
 * @author Michael Funk <mike.funk@internetbrands.com>
 */
class GoogleListener implements EventSubscriberInterface
{

    /**
     * get events subscribed to
     *
     * @static
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return ['response' => 'onResponse'];
    }

    /**
     * on response
     *
     * @param ResponseEvent $event
     * @return void
     */
    public function onResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();

        // if it's not an html request, not an html response, or it's redirected
        // don't add the analytics code
        if (
            $response->isRedirection()
            || (
                $response->headers->has('Content-Type')
                && false === strpos($response->headers->get('Content-Type'), 'html')
            )
            || 'html' !== $event->getRequest()->getRequestFormat()
        ) {
            return;
        }

        // add our fake google analytics code
        $response->setContent($response->getContent() . 'GA CODE HERE');
    }
}
