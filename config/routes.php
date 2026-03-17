<?php
declare(strict_types=1);

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

return function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', ['controller' => 'Articles'], function (RouteBuilder $builder): void {
        $builder->connect('/', ['action' => 'index']);
        $builder->connect('/item/{id}', ['action' => 'view'])
            ->setPatterns(['id' => Router::ID])
            ->setPass(['id']);
        $builder->connect('/search', ['action' => 'search']);
        $builder->post('/add-comment', ['action' => 'addComment']);
        $builder->connect('/category/{id}', ['action' => 'category'])
            ->setPatterns(['id' => Router::ID])
            ->setPass(['id']);
        $builder->connect('/tag/{alias}', ['action' => 'tag'])
            ->setPatterns(['alias' => '[a-z0-9-]+'])
            ->setPass(['alias']);
        $builder->connect('/user/{id}', ['action' => 'user'])
            ->setPatterns(['id' => Router::ID])
            ->setPass(['id']);
    });

    $routes->scope('/archive', ['controller' => 'Articles', 'action' => 'archive'], function (RouteBuilder $builder): void {
        $builder->connect('/');
        $builder->connect('/{year}/{month}/{day}')
            ->setPatterns(['year' => Router::YEAR, 'month' => Router::MONTH, 'day' => Router::DAY])
            ->setPass(['year', 'month', 'day']);
        $builder->connect('/{year}/{month}')
            ->setPatterns(['year' => Router::YEAR, 'month' => Router::MONTH])
            ->setPass(['year', 'month']);
        $builder->connect('/{year}')
            ->setPatterns(['year' => Router::YEAR])
            ->setPass(['year']);
    });
};
