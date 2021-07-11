<?php

namespace Angle\Utilities\Address;

abstract class Country
{
    /**
     * Supported Regions
     * Using ISO 3166-2
     * @return array
     */
    protected static $regions;

    /**
     * Supported Regions
     * Using ISO 3166-2
     * @return array
     */
    public static function getAvailableRegions()
    {
        return static::$regions;
    }

    public static function getRegionName($region)
    {
        if (array_key_exists($region, static::$regions)) {
            return static::$regions[$region];
        }

        return 'Desconocido';
    }
}