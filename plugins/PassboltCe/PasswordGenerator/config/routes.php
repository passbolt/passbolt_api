<?php
declare(strict_types=1);

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
 * @since         3.3.0
 */
use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */

$routes->plugin('Passbolt/PasswordGenerator', ['path' => '/'], function (RouteBuilder $routes) {
    $routes->setExtensions(['json']);

    /**
     * @deprecated with v5.0, the legacy password generator settings entry point is replaced by the more complete
     * password policies settings entry point.
     */
    $routes->redirect('/password-generator/settings', '/password-policies/settings.json');
});
