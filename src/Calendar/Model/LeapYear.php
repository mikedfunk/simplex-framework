<?php
/**
 * Leapyear
 *
 * @package AutoClassifiedsPlatform
 * @copyright 2014 Internet Brands, Inc. All Rights Reserved.
 */
namespace Calendar\Model;

/**
 * LeapYear
 *
 * @author Michael Funk <mike.funk@internetbrands.com>
 */
class LeapYear
{

    /**
     * is this a leap year?
     *
     * @param int $year (default: null)
     * @return bool
     */
    public function isLeapYear($year = null)
    {
        // set default of current year if not set
        if (null === $year) {
            $year = date('Y');
        }

        // if it's divisible by 400 or it's divisible by 4 but not 100
        return 0 == $year % 400 || (0 == $year % 4 && 0 != $year % 100);
    }
}
