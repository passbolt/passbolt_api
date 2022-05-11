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
 * @since         3.0.0
 */
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Passbolt/EmailDigest', ['path' => '/seleniumtests'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    /**
     * Return a preview of the next emails batch
     *
     * @uses \Passbolt\EmailDigest\Controller\EmailDigest\PreviewNextEmailsBatchController::preview()
     */
    $routes->connect(
        '/showLastBatch',
        ['prefix' => 'EmailDigest', 'controller' => 'PreviewNextEmailsBatch', 'action' => 'preview']
    )
    ->setMethods(['GET']);
});
