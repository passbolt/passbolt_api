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
namespace PassboltTestData\Shell\Task;

use App\Utility\Common;
use App\Utility\Gpg;
use Cake\ORM\TableRegistry;

class GpgkeysDataTask extends DataTask
{
    public $entityName = 'Gpgkeys';

    public static $testKeysPath = ROOT . DS . 'plugins' . DS . 'PassboltTestData' . DS . 'src' .
        DS . 'Shell' . DS . 'Task' . DS . 'gpg' . DS;

    /**
     * Get path of the key for the given user.
     *
     * @param $userId
     * @return string
     */
    public function getGpgkeyPath($userId) {
        $Users = TableRegistry::get('Users');
        $user = $Users->find('all')->where(['id' => $userId])->first();
        $prefix = $user->username;
        $uprefix = explode('@', $prefix);
        if (file_exists(GpgkeysDataTask::$testKeysPath . $uprefix[0] . '_public.key')) {
            $keyFileName = GpgkeysDataTask::$testKeysPath . $uprefix[0] . '_public.key';
        } else {
            $keyFileName = GpgkeysDataTask::$testKeysPath . 'passbolt_dummy_key.asc';
        }
        return $keyFileName;
    }

    /**
     * Get the public key of a user.
     *
     * @param $userId
     * @return string
     */
    protected function _getUserKey($userId) {
        $key = file_get_contents($this->getGpgkeyPath($userId));
        return $key;
    }

    /**
     * Return the Gpgkeys data
     *
     * @return array
     */
    protected function _getData()
    {
        $Users = TableRegistry::get('Users');
        $users = $Users->find('all');
        $Gpg = new Gpg();
        $keys = [];

        foreach($users as $user) {
            $keyRaw = $this->_getUserKey($user->id);
            $info = $Gpg->getKeyInfo($keyRaw);
            $keys[] = [
                'id' => Common::uuid(),
                'user_id' => $user->id,
                'armored_key' => $keyRaw,
                'bits' => $info['bits'],
                'uid' => $info['uid'],
                'key_id' => $info['key_id'],
                'fingerprint' => $info['fingerprint'],
                'type' => $info['type'],
                'expires' => !empty($info['expires']) ? date('Y-m-d H:i:s', $info['expires']) : null,
                'key_created' => date('Y-m-d H:i:s', $info['key_created']),
                'deleted' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ];
        }
        return $keys;
    }
}
