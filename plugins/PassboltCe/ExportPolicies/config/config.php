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
 * @since         5.10.0
 */

return [
    'passbolt' => [
        'plugins' => [
            'exportPolicies' => [
                'version' => '1.0.0',
                'enabled' => true,
                'settingsVisibility' => [
                    'whiteListPublic' => [
                        'enabled',
                    ],
                ],
                /**
                 * Set below configuration in passbolt.php or via the environment variables to override the API default.
                 * See ExportPoliciesPlugin.php file for available env variables.
                 * See `ExportPoliciesSettingsDto::createFromDefault()` for default values.
                 */
                // 'allow_csv_format' => true,
            ],
        ],
    ],
];
