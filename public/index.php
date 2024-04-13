<?php

declare(strict_types = 1);

use App\Config;
use App\Enum\AppEnvironment;
use Slim\App;
// xdebug_info();
// exit;
$container = require __DIR__ . '/../bootstrap.php';
$container->get(App::class)->run();
// setcookie("user", "John Doe", time() + 3600, "/");

