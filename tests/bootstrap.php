<?php

use Ninjify\Nunjuck\Environment;

if (@!include __DIR__ . '/../vendor/autoload.php') {
    echo 'Install Nette Tester using `composer update --dev`';
    exit(1);
}

// Configure environment
Environment::setupTester();
Environment::setupTimezone();
Environment::setupVariables(__DIR__);

define('FIXTURES_DIR', __DIR__ . '/fixtures');

function dump(...$args)
{
    foreach ($args as $arg) {
        var_dump($arg);
    }
}

function dumpe(...$args)
{
    dump(...$args);
    \Tester\Assert::fail('Dump variable');
    die();
}