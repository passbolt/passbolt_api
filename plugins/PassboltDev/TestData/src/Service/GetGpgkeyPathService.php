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
 * @since         4.5.0
 */

namespace Passbolt\TestData\Service;

use Cake\ORM\Locator\LocatorAwareTrait;

class GetGpgkeyPathService
{
    use LocatorAwareTrait;

    /**
     * Get path of the key for the given user.
     *
     * @param string $userId User uuid.
     * @return string
     */
    public function get(string $userId): string
    {
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable
            ->find()
            ->contain(['Profiles'])
            ->where(['Users.id' => $userId])
            ->first();
        $prefix = $user->username;
        $uprefix = explode('@', $prefix);
        $keyFileName = PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . $uprefix[0] . '_public.key';

        if (!file_exists($keyFileName)) {
            $keyFileName = PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . 'passbolt_dummy_key.asc';
            // Generate a new key.
            // This code can be useful when we need to generate keys.
            // By definition a gpg key should be unique and a owned by only one user.
            // $privateKeyPath = PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . $uprefix[0] . '_private.key';
            // $this->generateKey($user, $keyFileName, $privateKeyPath);
        }

        return $keyFileName;
    }
}
