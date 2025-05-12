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
 * @since         5.1.1
 */

return [
    'passbolt' => [
        'plugins' => [
            'UserGpgKeyPolicies' => [
                'version' => '1.0.0',
                'enabled' => true,
                'settingsVisibility' => [
                    'whiteListPublic' => [
                        'enabled',
                    ],
                ],
                /**
                 * Preferred key type.
                 * Set it in passbolt.php or via the environment variable PASSBOLT_PLUGINS_USER_GPG_KEY_POLICIES_PREFERRED_KEY_TYPE
                 * to override the API default.
                 * Allowed values: "RSA", "EdDSA"
                 * As of v5.1.0, the default is set to "RSA".
                 */
                // 'preferred_key_type' => 'rsa',
            ],
        ],
    ],
];
