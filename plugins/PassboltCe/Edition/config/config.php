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
use Cake\Core\Configure;

return [
    'passbolt' => [
        'plugins' => [
            'edition' => [
                'version' => '1.0.0',
                'disableLogoutUsersOnEditionChangeMiddleware' => filter_var(
                    env('PASSBOLT_PLUGINS_EDITION_DISABLE_LOGOUT_USERS_ON_EDITION_CHANGE_MIDDLEWARE', false),
                    FILTER_VALIDATE_BOOLEAN,
                ),
                'subscriptionKey' => [
                    'public' => Configure::read(
                        'passbolt.plugins.edition.subscriptionKey.public',
                        __DIR__ . DS . 'subscription_public.key',
                    ),
                ],
            ],
        ],
    ],
];
