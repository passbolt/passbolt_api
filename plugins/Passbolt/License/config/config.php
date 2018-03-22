<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
use \Cake\Core\Configure;

$license = env(
    'PASSBOLT_PLUGINS_LICENSE_LICENSE',
    Configure::read(
        'passbolt.plugins.license.license',
        CONFIG . 'license'
    )
);

$licensePublicKey = env(
    'PASSBOLT_PLUGINS_LICENSE_LICENSEKEY_PUBLIC',
    Configure::read(
        'passbolt.plugins.license.licenseKey.public',
        __DIR__ . DS . 'license_public.key'
    )
);

return [
    'passbolt' => [
        'plugins' => [
            'license' => [
                'version' => '2.0.0',
                'license' => $license,
                'licenseKey' => [
                    'public' => $licensePublicKey
                ]
            ]
        ]
    ]
];
