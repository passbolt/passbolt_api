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
return [
    'passbolt' => [
        'plugins' => [
            'emailDigest' => [
                'version' => '1.0.0',
                'enabled' => env('PASSBOLT_PLUGINS_EMAIL_DIGEST_ENABLED', true),
                'batchSizeLimit' => filter_var(
                    env('PASSBOLT_PLUGINS_EMAIL_DIGEST_BATCH_SIZE_LIMIT', '100'),
                    FILTER_VALIDATE_INT
                ),
            ],
        ],
    ],
];
