<?php

declare(strict_types=1);

use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions(__DIR__ . '/container_bindings.php');
// echo 'in container.php' . PHP_EOL;
return $containerBuilder->build();
