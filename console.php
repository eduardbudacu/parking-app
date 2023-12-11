#!/usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Temperworks\Codechallenge\Domain\Garage;
use Temperworks\Codechallenge\Presentation\Cli;

$garage = new Garage();
$app = new Application();
$app->add(new Cli($garage));
$app->run();
