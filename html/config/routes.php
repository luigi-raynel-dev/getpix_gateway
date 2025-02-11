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
    Router::get('/me', 'App\Controller\AuthController@me');
});
