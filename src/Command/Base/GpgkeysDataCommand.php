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
 * @since         2.0.0
 */
namespace PassboltTestData\Command\Base;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataCommand;
use Passbolt\WebInstaller\Utility\Gpg as WebinstallerGpg;

class GpgkeysDataCommand extends DataCommand
{
    public $entityName = 'Gpgkeys';

    /**
     * Get path of the key for the given user.
     *
     * @param string $userId uuid
     * @return string filename
     */
    public function getGpgkeyPath(string $userId): string
    {
        $user = $this->loadModel('Users')->find('all')
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

    /**
     * Generate and export a user key.
     *
     * @param User $user The user entity to generate the key for
     * @param string $publicKeyPath The public key path
     * @param string $privateKeyPath The private key path
     * @return void
     */
    public function generateAndExportGpgKeys($user, $publicKeyPath, $privateKeyPath)
    {
        $gpgSettings = [
            'name' => $user->profile->first_name . ' ' . $user->profile->last_name,
            'email' => $user->username,
            'comment' => ''
        ];
        $fingerprint = WebinstallerGpg::generateKey($gpgSettings);
        WebinstallerGpg::exportPublicArmoredKey($fingerprint, $publicKeyPath);
        WebinstallerGpg::exportPrivateArmoredKey($fingerprint, $privateKeyPath, $user->username);
    }

    /**
     * Get the public key of a user.
     *
     * @param string $userId uuid
     * @return string ascii armored key
     */
    protected function _getUserKey($userId)
    {
        $key = file_get_contents($this->getGpgkeyPath($userId));

        return $key;
    }

    /**
     * Return the Gpgkeys data
     *
     * @return array
     */
    public function getData()
    {
        $users = $this->loadModel('Users')->find('all');
        $Gpg = OpenPGPBackendFactory::get();
        $keys = [];
        // users to skip, like if they have not completed the setup for example
        $skip = [UuidFactory::uuid('user.id.ruth')];

        foreach ($users as $user) {
            if (!in_array($user->id, $skip)) {
                $keyRaw = $this->_getUserKey($user->id);
                $info = $Gpg->getKeyInfo($keyRaw);
                $keys[] = [
                    'id' => UuidFactory::uuid('gpgkey.id.' . $user->id),
                    'user_id' => $user->id,
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
                    'modified' => date('Y-m-d H:i:s')
                ];
            }
        }

        return $keys;
    }
}
