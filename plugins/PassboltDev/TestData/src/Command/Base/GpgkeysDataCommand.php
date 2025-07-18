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
 * @since         2.0.0
 */
namespace Passbolt\TestData\Command\Base;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Passbolt\TestData\Lib\DataCommand;
use Passbolt\TestData\Service\GetGpgkeyPathService;

class GpgkeysDataCommand extends DataCommand
{
    public string $entityName = 'Gpgkeys';

    /**
     * Get the public key of a user.
     *
     * @param string $userId uuid
     * @return string|false ascii armored key
     */
    protected function _getUserKey(string $userId): false|string
    {
        $gpgkeyPath = (new GetGpgkeyPathService())->get($userId);

        return file_get_contents($gpgkeyPath);
    }

    /**
     * Return the Gpgkeys data
     *
     * @return array
     */
    public function getData(): array
    {
        $usersTable = $this->fetchTable('Users');
        $users = $usersTable->find('all');
        $Gpg = OpenPGPBackendFactory::get();
        $keys = [];
        // users to skip, like if they have not completed the setup for example
        $skip = [UuidFactory::uuid('user.id.ruth')];

        foreach ($users as $user) {
            if (!in_array($user->get('id'), $skip)) {
                $keyRaw = $this->_getUserKey($user->get('id'));
                $info = $Gpg->getKeyInfo($keyRaw);
                $keys[] = [
                    'id' => UuidFactory::uuid('gpgkey.id.' . $user->get('id')),
                    'user_id' => $user->get('id'),
                    'armored_key' => $keyRaw,
                    'bits' => $info['bits'],
                    'uid' => $info['uid'],
                    'key_id' => $info['key_id'],
                    'fingerprint' => $info['fingerprint'],
                    'type' => $info['type'],
                    'expires' => !empty($info['expires']) ? date('Y-m-d H:i:s', $info['expires']) : null,
                    'key_created' => date('Y-m-d H:i:s', $info['key_created']),
                    'deleted' => 0,
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                ];
            }
        }

        return $keys;
    }
}
