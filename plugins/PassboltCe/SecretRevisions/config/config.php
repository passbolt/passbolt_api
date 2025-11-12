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
 * @since         5.7.0
 */
return [
    'passbolt' => [
        'plugins' => [
            'secretRevisions' => [
                'version' => '1.0.0',
                'maxRevisionsLimit' => filter_var(env('PASSBOLT_PLUGINS_SECRET_REVISIONS_MAX_REVISIONS_LIMIT', '10'), FILTER_VALIDATE_INT), // phpcs:ignore
                'enableAllowSharingRevisions' => filter_var(env('PASSBOLT_PLUGINS_SECRET_REVISIONS_ENABLE_ALLOW_SHARING_REVISIONS', false), FILTER_VALIDATE_BOOLEAN), // phpcs:ignore
                'settingsVisibility' => [
                    'whiteList' => [
                        'isInBeta',
                    ],
                ],
            ],
        ],
    ],
];
