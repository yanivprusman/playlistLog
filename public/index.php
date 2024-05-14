<?php

declare(strict_types = 1);
require ('../vendor/autoload.php');

use App\Enum\AppEnvironment;
use Slim\App;
use G_H_Projects\G_h_projects_include;

$x = new G_h_projects_include();
$x->echo();

// $container = require __DIR__ . '/../bootstrap.php';
// $container->get(App::class)->run();

