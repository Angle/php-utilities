<?php

namespace Angle\Utilities;
/*
 * Adapted from: https://stackoverflow.com/a/1653826 by Alix Axel
 */

abstract class BCMathExtra
{
    /**
     * @param $number
     * @param int $precision
     * @return string
     */
    public static function bcround($number, $precision = 0)
    {
        if (strpos($number, '.') !== false) {
            if ($number[0] != '-') return bcadd($number, '0.' . str_repeat('0', $precision) . '5', $precision);
            return bcsub($number, '0.' . str_repeat('0', $precision) . '5', $precision);
        }
        return $number;
    }

    /**
     * @param $number
     * @return string
     */
    public static function bcceil($number)
    {
        if (strpos($number, '.') !== false) {
            if (preg_match("~\.[0]+$~", $number)) return self::bcround($number, 0);
            if ($number[0] != '-') return bcadd($number, 1, 0);
            return bcsub($number, 0, 0);
        }
        return $number;
    }

    /**
     * @param $number
     * @return string
     */
    public static function bcfloor($number)
    {
        if (strpos($number, '.') !== false) {
            if (preg_match("~\.[0]+$~", $number)) return self::bcround($number, 0);
            if ($number[0] != '-') return bcadd($number, 0, 0);
            return bcsub($number, 1, 0);
        }
        return $number;
    }
}