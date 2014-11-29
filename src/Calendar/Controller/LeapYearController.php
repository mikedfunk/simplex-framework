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
            return new Response('Yep, this is a leap year!');
        }

        return new Response('Nope, this is not a leap year.');
    }
}
