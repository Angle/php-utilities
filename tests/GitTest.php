<?php declare(strict_types=1);

namespace Angle\Utilities\Tests;

use PHPUnit\Framework\TestCase;

use Angle\Utilities\GitUtility;

final class GitTest extends TestCase
{

    public function testLastCommitHash(): void
    {
        // Lookup the original directory
        $repository = realpath(dirname(__FILE__) . '/../');
        print "Repository Directory: " . $repository . PHP_EOL;

        $output = GitUtility::lastCommitHash($repository);

        print 'Last Commit Hash: ' . $output . PHP_EOL;

        $this->assertNotEmpty($output);
    }

    public function testLastCommitDate(): void
    {
        // Lookup the original directory
        $repository = realpath(dirname(__FILE__) . '/../');
        //print "Repository Directory: " . $repository . PHP_EOL;

        $output = GitUtility::lastCommitDate($repository);

        print 'Last Commit Date: ' . $output . PHP_EOL;

        $this->assertNotEmpty($output);
    }

    public function testBranch(): void
    {
        // Lookup the original directory
        $repository = realpath(dirname(__FILE__) . '/../');
        //print "Repository Directory: " . $repository . PHP_EOL;

        $output = GitUtility::branch($repository);

        print 'Branch: ' . $output . PHP_EOL;

        $this->assertNotEmpty($output);
    }

    public function testReleaseTag(): void
    {
        // Lookup the original directory
        $repository = realpath(dirname(__FILE__) . '/../');
        //print "Repository Directory: " . $repository . PHP_EOL;

        $output = GitUtility::releaseTag($repository);

        print 'Release Tag: ' . $output . PHP_EOL;

        $this->assertNotEmpty($output);
    }
}