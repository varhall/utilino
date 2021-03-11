<?php

namespace Tests\Utilino\Utils;

use Tester\Assert;
use Tester\TestCase;
use Varhall\Utilino\Utils\Path;

require __DIR__ . '/../../bootstrap.php';

class PathTest extends TestCase
{
    public function testSingle()
    {
        Assert::equal('foo', Path::combine('foo'));
    }

    public function testRelative()
    {
        $expected = implode(DIRECTORY_SEPARATOR, [ 'foo', 'bar', 'baz' ]);
        Assert::equal($expected, Path::combine('foo', 'bar', 'baz'));
    }

    public function testWindowsAbsolute()
    {
        $expected = implode(DIRECTORY_SEPARATOR, [ 'C:\\foo', 'bar' ]);
        Assert::equal($expected, Path::combine('C:\\foo', 'bar'));
    }

    public function testUnixAbsolute()
    {
        $expected = implode(DIRECTORY_SEPARATOR, [ '/foo', 'bar' ]);
        Assert::equal($expected, Path::combine('/foo', 'bar'));
    }
}

(new PathTest())->run();