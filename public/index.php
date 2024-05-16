<?php

declare(strict_types = 1);
require ('../vendor/autoload.php');

use App\Enum\AppEnvironment;
use Slim\App;
use G_H_Projects\G_h_projects_include;

$x = new G_h_projects_include();
$x->echo();
// echo 'asdf';

$container = require __DIR__ . '/../bootstrap.php';
$app = $container->get(App::class);
// $app->get
// $app->setBasePath('/path/to/your/public');
// $app->add(new Slim\Middleware\StaticMiddleware());

$app->run();

