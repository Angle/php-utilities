<?php

namespace Angle\Utilities\Date;

use DateTime;
use DatePeriod;
use DateInterval;

abstract class TimePeriodUtility
{
    public static function periodsInRange(int $startYear, int $startPeriod, int $endYear, int $endPeriod): array
    {
        // Convert the start period into a single int value: (year * 12) + period
        $start = ($startYear * 12) + $startPeriod;
        $end = ($endYear * 12) + $endPeriod;

        if ($start > $end) {
            throw new \RuntimeException('StartPeriod is greater than EndPeriod');
        }

        $n = ($end - $start) + 1;
        $p = [];

        for ($i = 0; $i < $n; $i++) {
            $y = (int)floor(($start + $i) / 12);
            $m = ($start + $i) % 12;

            if ($m == 0) {
                $y -= 1;
                $m = 12;
            }

            $p[] = [
                'year' => $y,
                'period' => $m,
                'key' => self::monthStringFromYearAndMonth($y, $m),
            ];
        }

        return $p;
    }

    public static function monthStringFromYearAndMonth(int $year, int $month): string
    {
        return $year . "-" . str_pad($month, 2,'0', STR_PAD_LEFT);
    }

    public static function monthStringFromYearAndQuarter(int $year, int $quarter): string
    {
        $month = self::getMonthFromQuarter($quarter);

        return self::monthStringFromYearAndMonth($year, $month);

    }

    public static function monthStringFromDate(\DateTime $date): string
    {
        if (!($date instanceof \DateTime)) {
            throw new \RuntimeException('Expected a valid DateTime object');
        }

        $year = intval($date->format('Y'));
        $month = intval($date->format('m'));

        return self::monthStringFromYearAndMonth($year, $month);
    }



    public static function quarterStringFromYearAndMonth(int $year, int $month): string
    {
        $quarter = self::getQuarterFromMonth($month);

        return self::quarterStringFromYearAndQuarter($year, $quarter);
    }

    public static function quarterStringFromYearAndQuarter(int $year, int $quarter): string
    {
        return $year . "-T" . $quarter;
    }

    public static function quarterStringFromDate(\DateTime $date): string
    {
        if (!($date instanceof \DateTime)) {
            throw new \RuntimeException('Expected a valid DateTime object');
        }

        $year = intval($date->format('Y'));
        $month = intval($date->format('m'));

        return self::quarterStringFromYearAndMonth($year, $month);
    }


    public static function getQuarterFromMonth(int $month): int
    {
        if ($month < 1 || $month > 12) {
            throw new \RuntimeException("Month '$month' is not in valid range 1-12");
        }

        if ($month == 1 ||$month == 2 || $month == 3) {
            return 1;
        } elseif ($month == 4 ||$month == 5 || $month == 6) {
            return 2;
        } elseif ($month == 7 ||$month == 8 || $month == 9) {
            return 3;
        } elseif ($month == 10 ||$month == 11 || $month == 12) {
            return 4;
        }

        // This should never happen.
        throw new \RuntimeException("Unexpected month.");
    }

    public static function getMonthFromQuarter(int $quarter): int
    {
        if ($quarter < 1 || $quarter > 4) {
            throw new \RuntimeException("Quarter '$quarter' is not in valid range 1-4");
        }

        if ($quarter == 1) {
            return 3;
        } elseif ($quarter == 2) {
            return 6;
        } elseif ($quarter == 3) {
            return 9;
        } elseif ($quarter == 4) {
            return 12;
        }

        // This should never happen.
        throw new \RuntimeException("Unexpected quarter.");
    }


    public static function getStartMonthFromQuarter(int $quarter): int
    {
        if ($quarter < 1 || $quarter > 4) {
            throw new \RuntimeException("Quarter '$quarter' is not in valid range 1-4");
        }

        if ($quarter == 1) {
            return 1;
        } elseif ($quarter == 2) {
            return 4;
        } elseif ($quarter == 3) {
            return 7;
        } elseif ($quarter == 4) {
            return 10;
        }

        // This should never happen.
        throw new \RuntimeException("Unexpected quarter.");
    }

    public static function getEndMonthFromQuarter(int $quarter): int
    {
        // the base "getMonthFromQuarter" method already returns the ending period
        return self::getMonthFromQuarter($quarter);
    }

    /**
     * @param int $year
     * @param int $month
     * @return DateTime[] array
     * @throws \Exception
     */
    public static function getDateRangeForPeriod(int $year, int $month): array
    {
        $startDate = DateUtility::calculateStartDate($year, $month);
        $endDate = DateUtility::calculateEndDate($year, $month);
        return DateUtility::getDateRange($startDate, $endDate);
    }

    public static function convertToMonths(int $year, int $period): int
    {
        return ($year * 12) + $period;
    }

    public static function convertToYearAndPeriod(int $months): array
    {
        $y = (int)floor($months / 12);
        $m = $months % 12;

        if ($m == 0) {
            $y -= 1;
            $m = 12;
        }

        return [
            'year' => $y,
            'period' => $m,
        ];

    }

}
