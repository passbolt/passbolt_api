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
 * @since         3.6.0
 */
use App\Command\CleanupCommand;
use Cake\Core\Configure;

Configure::load('Passbolt/AccountRecovery.config', 'default', true);

// Add cleanup tasks jobs.
if (PHP_SAPI === 'cli') {
    $cleanups = [
        'Passbolt/AccountRecovery.AccountRecoveryPrivateKeys' => [
            'Hard Deleted Users',
            'Soft Deleted Users',
        ],
        'Passbolt/AccountRecovery.AccountRecoveryRequests' => [
            'Hard Deleted Users',
            // We keep soft deleted users requests in the trail
        ],
        'Passbolt/AccountRecovery.AccountRecoveryUserSettings' => [
            'Hard Deleted Users',
            'Soft Deleted Users',
        ],
        'Passbolt/AccountRecovery.AccountRecoveryResponses' => [
            'Hard Deleted AccountRecoveryRequests',
        ],
        'Passbolt/AccountRecovery.AccountRecoveryPrivateKeyPasswords' => [
            'Hard Deleted AccountRecoveryPrivateKeys',
        ],
    ];
    CleanupCommand::addCleanups($cleanups);
}
