<?php
/**
 * Leap year controller
 *
 * @package AutoClassifiedsPlatform
 * @copyright 2014 Internet Brands, Inc. All Rights Reserved.
 */
namespace Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Calendar\Model\LeapYear;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * LeapYearController
 *
 * @author Michael Funk <mike.funk@internetbrands.com>
 */
class LeapYearController
{

    /**
     * indexAction
     *
     * @param Request $request
     * @param int $year
     * @return Response
     */
    public function indexAction(Request $request, $year)
    {
        $leapYear = new LeapYear();
        if ($leapYear->isLeapYear($year)) {
            $response = 'Yep, this is a leap year!' . rand();
            // $response = new Response('Yep, this is a leap year!' . rand());
        } else {
            $response = 'Nope, this is not a leap year.' . rand();
            // $response = new Response('Nope, this is not a leap year.' . rand());
        }

        // set time-to-live for http caching to 10 seconds
        // (worked when we were returning a response directly)
        // $response->setTtl(10);

        return $response;
    }
}
