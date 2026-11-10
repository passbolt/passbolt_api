<?php

use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->plugin('Passbolt/BrowserAuth', ['path' => '/browser-auth'], function (RouteBuilder $routes): void {
    $routes->setExtensions(['json']);
    $routes->connect('/authorize', ['controller' => 'BrowserAuth', 'action' => 'authorize'])->setMethods(['POST']);
    $routes->connect('/exchange', ['controller' => 'BrowserAuth', 'action' => 'exchange'])->setMethods(['POST']);
});
