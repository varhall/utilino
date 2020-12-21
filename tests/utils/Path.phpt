<?php

namespace Varhall\Utilino\Tests\Utils;

use Tester\Assert;
use Varhall\Utilino\Utils\Path;

require __DIR__ . '/../bootstrap.php';

$s = DIRECTORY_SEPARATOR;

function run(...$parts) {
    return Path::combine(...$parts);
}

test('Single', function() use ($s) {
    Assert::equal('foo', run('foo'));
});

test('Relative', function() use ($s) {
    Assert::equal("foo{$s}bar{$s}baz", run('foo', 'bar', 'baz'));
});

test('Windows absolute', function() use ($s) {
    Assert::equal("C:\\foo{$s}bar", run('C:\\foo', 'bar'));
});

test('Unix absolute', function() use ($s) {
    Assert::equal("/foo{$s}bar", run('/foo', 'bar'));
});