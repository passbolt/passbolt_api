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
 * @since         5.2.0
 */

return [
    'passbolt' => [
        'plugins' => [
            'userKeyPolicies' => [
                'version' => '1.0.0',
                'enabled' => true,
                'settingsVisibility' => [
                    'whiteListPublic' => [
                        'enabled',
                    ],
                ],
                /**
                 * Set below configuration in passbolt.php or via the environment variables to override the API default.
                 * See UserKeyPoliciesPlugin.php file for available env variables.
                 * See `UserKeyPoliciesSettingsDto::createFromDefault()` for default values.
                 */
                // 'preferred_key_type' => 'rsa', // Allowed values: "rsa", "curve"
                // 'preferred_key_size' => 3072, // For RSA allowed values are 3072, 4096. Otherwise, `null`.
                // 'preferred_key_curve' => null, // For Curve type, allowed values is "curve25519_legacy+ed25519_legacy". Otherwise, `null`.
            ],
        ],
    ],
];
