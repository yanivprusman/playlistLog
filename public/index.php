<?php

declare(strict_types = 1);
use App\Config;
use App\Enum\AppEnvironment;
use Slim\App;

$container = require __DIR__ . '/../bootstrap.php';
$container->get(App::class)->run();

