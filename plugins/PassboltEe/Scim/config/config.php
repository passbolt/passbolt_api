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
 * @since         5.5.0
 */

use App\Utility\AuthToken\AuthTokenExpiryConfigValidator;
use Passbolt\Scim\Service\ScimTokenExpiryChecker;

return [
    'passbolt' => [
        'plugins' => [
            'scim' => [
                'version' => '1.0.0',
                'enabled' => true,
                'logScimRequests' => filter_var(env('PASSBOLT_PLUGINS_SCIM_LOG_SCIM_REQUESTS', false), FILTER_VALIDATE_BOOLEAN), // phpcs:ignore
                'security' => [
                    'csrfProtection' => [
                        'unlockedActions' => [
                            'ScimCreate' => 'create',
                            'ScimPatch' => 'patch',
                            'ScimPut' => 'put',
                            'ScimDelete' => 'delete',
                        ],
                    ],
                    'secretToken' => [
                        'cost' => filter_var(env('PASSBOLT_SCIM_SECURITY_SECRET_TOKEN_COST', '12'), FILTER_VALIDATE_INT), // phpcs:ignore
                        // Disable in the cloud
                        'legacyHashAllowed' => filter_var(env('PASSBOLT_SCIM_SECURITY_SECRET_TOKEN_LEGACY_HASH_ALLOWED', true), FILTER_VALIDATE_BOOLEAN), // phpcs:ignore
                        'expiry' => filter_var(env('PASSBOLT_SCIM_SECURITY_SECRET_TOKEN_EXPIRY', '1 year'), FILTER_CALLBACK, ['options' => new AuthTokenExpiryConfigValidator()]), // phpcs:ignore
                        // NOTE: Expiry will be enforced by default in the upcoming release.
                        'enforceExpiry' => filter_var(env('PASSBOLT_SCIM_SECURITY_SECRET_TOKEN_ENFORCE_EXPIRY', false), FILTER_VALIDATE_BOOLEAN), // phpcs:ignore
                        'warningWindow' => filter_var(
                            env('PASSBOLT_SCIM_SECURITY_SECRET_TOKEN_WARNING_WINDOW', ScimTokenExpiryChecker::DEFAULT_WARNING_WINDOW), // phpcs:ignore
                            FILTER_CALLBACK,
                            ['options' => new AuthTokenExpiryConfigValidator()],
                        ),
                    ],
                    'allowSuspendAdministrators' => filter_var(env('PASSBOLT_PLUGINS_SCIM_SECURITY_ALLOW_SUSPEND_ADMINISTRATORS', false), FILTER_VALIDATE_BOOLEAN), // phpcs:ignore
                    'allowDeleteAdministrators' => filter_var(env('PASSBOLT_PLUGINS_SCIM_SECURITY_ALLOW_DELETE_ADMINISTRATORS', false), FILTER_VALIDATE_BOOLEAN), // phpcs:ignore
                ],
            ],
        ],
    ],
];
