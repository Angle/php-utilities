<?php

namespace Angle\Utilities;

use DateTime;
use Exception;

abstract class GitUtility
{
    /*
    public function gitLastCommit(): string
    {
        // get current file dir
        $dir = dirname(__FILE__);
        $head = $dir . '/../../../.git/FETCH_HEAD';
        $head = realpath($head);

        if ($head === false) {
            return '';
        }

        $raw = file_get_contents($head);

        if ($raw === false) {
            return '';
        }

        // return the first 8 chars of the HEAD
        return substr($raw, 0, 8);
    }

    public function gitBranch(): string
    {
        // get current file dir
        $dir = dirname(__FILE__);
        $head = $dir . '/../../../.git/FETCH_HEAD';
        $head = realpath($head);

        if ($head === false) {
            return '';
        }

        $raw = file_get_contents($head);

        if ($raw === false) {
            return '';
        }

        // return the text between the single quotes in the FETCH_HEAD file
        // example:
        // a5817f29d01944eedbb6b5d9d81d89b530ece122		branch 'master' of https://github.com/MyOrg/MyRepo
        // should return: master
        if (preg_match("/'([^']+)'/", $raw, $matches)) {
            return $matches[1];
        } else {
            return '';
        }
    }
    */

    /**
     * @param string|null $repoDirectory
     * @return string
     * @throws Exception
     */
    public static function lastCommitHash(?string $repoDirectory=null): string
    {
        $cmd = self::setWorkingDirectoryCommand($repoDirectory) . 'git log --pretty="%h" -n1 HEAD 2>&1';
        $t = exec($cmd, $o, $r);

        if ($r != 0) {
            throw new Exception(sprintf('git error [%d]: %s', $r, end($o)));
        }

        return trim($t);
    }

    public static function lastCommitDate(?string $repoDirectory=null): string
    {
        $cmd = self::setWorkingDirectoryCommand($repoDirectory) . 'git log -n1 --pretty=%ci HEAD 2>&1';
        $t = exec($cmd, $o, $r);

        if ($r != 0) {
            throw new Exception(sprintf('git error [%d]: %s', $r, end($o)));
        }

        $t = trim($t);

        try {
            $commitDate = new DateTime($t);
            $commitDate->setTimezone(new \DateTimeZone('UTC'));
            $commitDate = $commitDate->format('Y-m-d H:i:s') . ' UTC';
        } catch (Exception $e) {
            $commitDate = "N/A";
        }

        return $commitDate;
    }

    public static function branch(?string $repoDirectory=null): string
    {
        $cmd = self::setWorkingDirectoryCommand($repoDirectory) . 'git rev-parse --abbrev-ref HEAD 2>&1';
        $t = exec($cmd, $o, $r);

        if ($r != 0) {
            throw new Exception(sprintf('git error [%d]: %s', $r, end($o)));
        }

        return trim($t);
    }

    public static function releaseTag(?string $repoDirectory=null): string
    {
        // we will first try to get an exact match
        try {
            return self::currentReleaseTag($repoDirectory);
        } catch (Exception $e) {
            // no problem, we most likely we not on a "current" release, so we will try another way
        }

        return self::lastReleaseTag($repoDirectory);
    }

    /**
     * Get the last Release Tag in the history, but our current commit might or
     * might not be on this precise tag, we can be
     * @param string|null $repoDirectory
     * @return string
     * @throws Exception
     */
    public static function lastReleaseTag(?string $repoDirectory=null): string
    {
        $cmd = self::setWorkingDirectoryCommand($repoDirectory) . 'git describe --tags 2>&1';
        $t = exec($cmd, $o, $r);

        if ($r != 0) {
            throw new Exception(sprintf('git error [%d]: %s', $r, end($o)));
        }

        return trim($t);
    }

    public static function currentReleaseTag(?string $repoDirectory=null): string
    {
        //$cmd = self::setWorkingDirectoryCommand($repoDirectory) . 'git tag --points-at HEAD 2>&1'; // this will simply return a blank string
        $cmd = self::setWorkingDirectoryCommand($repoDirectory) . 'git describe --tags --exact-match 2>&1'; // this will actually make the process fail
        $t = exec($cmd, $o, $r);

        if ($r != 0) {
            throw new Exception(sprintf('git error [%d]: %s', $r, end($o)));
        }

        return trim($t);
    }

    private static function setWorkingDirectoryCommand(?string $directory=null): string
    {
        if ($directory === null) {
            // no directory set, no need to change anything, return an empty string
            return '';
        }

        return 'cd ' . $directory . '; ';
    }
}