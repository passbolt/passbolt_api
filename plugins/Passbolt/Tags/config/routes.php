<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin('Passbolt/Tags', ['path' => '/tags'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    $routes->connect('/', ['controller' => 'TagsIndex', 'action' => 'index'])
        ->setMethods(['GET']);
});
