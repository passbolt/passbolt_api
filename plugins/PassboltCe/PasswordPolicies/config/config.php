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
 * @since         4.2.0
 */

return [
    'passbolt' => [
        'plugins' => [
            'passwordPolicies' => [
                'version' => '1.0.0',
                'enabled' => true,
                /*
                 * 'defaultPasswordGenerator' => 'password'
                 *
                 * The default password generator type, by default 'password', however it is customizable.
                 *
                 * EE administrators can redefine it via the Password Policy administration page in the application.
                 *
                 * While CE & EE administrators can redefine it:
                 * - By adding an entry under the key (passbolt.plugins.passwordPolicies.defaultPasswordGenerator) in
                 *   the passbolt.php config file;
                 * - By setting the environment variable: PASSBOLT_PLUGINS_PASSWORD_POLICIES_DEFAULT_PASSWORD_GENERATOR_TYPE
                 */
            ],
        ],
    ],
];
