<?php

namespace Angle\Utilities;

use Exception;

abstract class UbuntuUtility
{
    public static function load(): string
    {
        $load = sys_getloadavg();
        return $load[0]; // CPU Load
    }

    public static function memory(): array
    {
        $free = trim(exec('free'));
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem, function($value) { return ($value !== null && $value !== false && $value !== ''); }); // removes nulls from array
        $mem = array_merge($mem); // puts arrays back to [0],[1],[2] after

        return [
            'mem_total'      => round($mem[1] / 1000000,2),
            'mem_used'       => round($mem[2] / 1000000,2),
            'mem_free'       => round($mem[3] / 1000000,2),
            'mem_shared'     => round($mem[4] / 1000000,2),
            'mem_cached'     => round($mem[5] / 1000000,2),
            'mem_available'  => round($mem[6] / 1000000,2),
        ]; // Available Memory
    }

    public static function disk(): array
    {
        $diskFree = round(disk_free_space(".") / 1000000000);
        $diskTotal = round(disk_total_space(".") / 1000000000);
        $diskUsed = round($diskTotal - $diskFree);

        return [
            'disk_free'      => $diskFree,
            'disk_total'     => $diskTotal,
            'disk_used'      => $diskUsed,
            'disk_usage'     => round($diskUsed / $diskTotal * 100),
        ]; // Available Disk
    }
}