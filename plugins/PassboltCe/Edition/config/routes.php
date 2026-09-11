<?php

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         5.13.0
 */
use Cake\Routing\RouteBuilder;
use Passbolt\Edition\Middleware\EditionDowngradeDisabledMiddleware;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Passbolt/Edition', ['path' => '/edition'], function (RouteBuilder $routes): void {
    $routes->setExtensions(['json']);
    $routes->registerMiddleware(
        EditionDowngradeDisabledMiddleware::class,
        new EditionDowngradeDisabledMiddleware(),
    );

    /**
     * @uses \Passbolt\Edition\Controller\EditionSubscriptionsCreateController::create()
     */
    $routes->connect('/subscription/key', [
        'controller' => 'EditionSubscriptionsCreate',
        'action' => 'create',
    ])->setMethods(['POST']);

    $routes
        ->connect('/subscription/key', [
            'controller' => 'EditionSubscriptionsDelete',
            'action' => 'delete',
        ])
        ->setMethods(['DELETE'])
        ->setMiddleware([EditionDowngradeDisabledMiddleware::class]);
});
