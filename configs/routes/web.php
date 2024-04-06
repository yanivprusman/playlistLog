<?php

declare(strict_types = 1);

use G_H_PROJECTS_INCLUDE\Controllers\CallMethodController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\YoutubeController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use Slim\App;

return function (App $app) {
    
    // $app->get('/', [HomeController::class, 'index'])->add(AuthMiddleware::class);
    $app->get('/', [YoutubeController::class, 'index']);
    $app->get('/login', [AuthController::class, 'loginView'])->add(GuestMiddleware::class);
    $app->get('/register', [AuthController::class, 'registerView'])->add(GuestMiddleware::class);
    $app->post('/login', [AuthController::class, 'logIn'])->add(GuestMiddleware::class);
    $app->post('/register', [AuthController::class, 'register'])->add(GuestMiddleware::class);
    $app->post('/logout', [AuthController::class, 'logOut'])->add(AuthMiddleware::class);
    $app->get('/include/{file}', function($request, $response, $args){
        $file = $args['file'];
        $file_path = __DIR__ . '/../../include/' . $file;
        
        if (file_exists($file_path)) {
            include $file_path;
            return $response;
        } else {
            // Handle file not found error
            $response->getBody()->write('File not found');
            return $response->withStatus(404);
        }
    });
    $app->post('/callMethod', [CallMethodController::class, 'index']);
    // $app->post('/CallMechod/{data}', [CallMethodController::class, 'index'])->add(AuthMiddleware::class);
};
