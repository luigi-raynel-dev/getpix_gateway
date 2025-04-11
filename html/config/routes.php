<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');


Router::addGroup('/api', function () {
    Router::get('/ping', function () {
        return 'pong';
    });

    Router::post('/sign-in', 'App\Controller\AuthController@signIn');
    Router::post('/sign-up', 'App\Controller\AuthController@signUp');
    Router::post('/refresh-token', 'App\Controller\AuthController@refreshToken');

    Router::addGroup(
        '/users',
        function () {
            Router::get('/me', 'App\Controller\UserController@me');
        },
        ['middleware' => [\App\Middleware\AuthMiddleware::class]]
    );

    Router::addGroup(
        '/pixKeys',
        function () {
            Router::get('', 'App\Controller\PixKeyController@index');
            Router::get('/{id}', 'App\Controller\PixKeyController@show');
            Router::post('', 'App\Controller\PixKeyController@store');
            Router::put('/{id}', 'App\Controller\PixKeyController@update');
            Router::delete('/{id}', 'App\Controller\PixKeyController@destroy');
        },
        ['middleware' => [\App\Middleware\AuthMiddleware::class]]
    );
});
