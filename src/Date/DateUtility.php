<?php

namespace Angle\Utilities\Date;

use DateTime;
use DatePeriod;
use DateTimeZone;
use DateInterval;

abstract class DateUtility
{
    public static function getTodayInTimezone(DateTimeZone $tz)
    {
        $today = new DateTime('now', $tz);
        $today->setTime(0,0,0);
        return $today;
    }

    public static function getTodayDateComponents(DateTimeZone $tz)
    {
        $today = self::getTodayInTimezone($tz);
        return self::getDateComponents($today);
    }

    /**
     * @param DateTime $date
     * @return array [year, month, day] ints
     */
    public static function getDateComponents(DateTime $date): array
    {
        $date = clone $date;

        $currentYear = intval($date->format('Y'));
        $currentMonth = intval($date->format('n'));
        $currentDay = intval($date->format('j'));

        return [$currentYear, $currentMonth, $currentDay];
    }


    /**
     * @param int $year
     * @param int $month
     * @return DateTime
     */
    public static function calculateStartDate($year, $month): DateTime
    {
        $dateStr = sprintf('%d-%d-%d', $year, $month, 1);
        return DateTime::createFromFormat('Y-m-d', $dateStr);
    }

    /**
     * @param int $year
     * @param int $month
     * @return DateTime
     */
    public static function calculateEndDate($year, $month): DateTime
    {
        // First we find the start date
        $firstDay =  self::calculateStartDate($year, $month);
        // Then we use the format key "t" to print the last day of the month, and we create a new date from that string
        return DateTime::createFromFormat('Y-m-d', $firstDay->format('Y-m-t'));
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return DateTime[] array
     * @throws \Exception
     */
    public static function getDateRange(DateTime $startDate, DateTime $endDate): array
    {
        if ($startDate > $endDate) {
            throw new \Exception('StartDate cannot be greater than EndDate');
        }

        // small fix to also include the End Date
        $fixEndDate = clone $endDate;
        $fixEndDate = $fixEndDate->modify('+1 day');

        // Generate a DateRange
        $range = new DatePeriod(
            $startDate,
            new DateInterval('P1D'),
            $fixEndDate
        );

        return iterator_to_array($range);
    }

    public static function getStartOfNextMonth(DateTime $date, DateTimeZone $tz): DateTime
    {
        $date = clone $date;

        $date->setTimezone($tz);
        $date->setTime(0, 0, 0); // reset the time
        $date->modify('+1 month'); // add one month

        list($year, $month, $day) = DateUtility::getDateComponents($date);

        $date->setDate($year, $month, 1);

        return $date;
    }

    public static function getEndOfCurrentMonth(DateTime $date, DateTimeZone $tz): DateTime
    {
        $date = clone $date;

        $date->setTimezone($tz);
        $date->setTime(0, 0, 0); // reset the time

        $y = intval($date->format('Y'));
        $m = intval($date->format('n'));
        $endDay = intval($date->format('t')); // Number of days in the given month

        $date->setDate($y, $m, $endDay); // overriding the day to set it to the last day of the month

        return $date;
    }

    /**
     * Add N days to the next month
     * @param DateTime $date
     * @param DateTimeZone $tz
     * @param int $days
     * @return DateTime
     */
    public static function getNDaysOfCurrentMonth(DateTime $date, DateTimeZone $tz, int $days): DateTime
    {
        $date = clone $date;

        $date->setTimezone($tz);
        $date->setTime(0, 0, 0); // reset the time

        list($year, $month, $day) = DateUtility::getDateComponents($date);

        $date->setDate($year, $month, $days); // override the "day"

        return $date;
    }

    /**
     * Add N days to the next month
     * @param DateTime $date
     * @param DateTimeZone $tz
     * @param int $days
     * @return DateTime
     */
    public static function getNDaysOfNextMonth(DateTime $date, DateTimeZone $tz, int $days): DateTime
    {
        $date = clone $date;

        $date->setTimezone($tz);
        $date->setTime(0, 0, 0); // reset the time
        $date->modify('+1 month'); // add one month

        list($year, $month, $day) = DateUtility::getDateComponents($date);

        $date->setDate($year, $month, $days); // override the "day"

        return $date;
    }
}