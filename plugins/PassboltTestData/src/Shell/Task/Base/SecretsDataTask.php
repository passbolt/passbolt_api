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
namespace PassboltTestData\Shell\Task\Base;;

use App\Utility\Common;
use PassboltTestData\Lib\DataTask;

class SecretsDataTask extends DataTask
{
    public $entityName = 'Secrets';

    protected function _getFixedPasswords() {
        return [
            Common::uuid('resource.id.apache') => '_upjvh-p@wAHP18D}OmY05M',
            Common::uuid('resource.id.april') => 'z"(-1s]3&Itdno:vPt',
            Common::uuid('resource.id.bower') => 'CL]m]x(o{sA#QW',
            Common::uuid('resource.id.centos') => 'this_23-04',
            Common::uuid('resource.id.enlightenment') => 'azertyuiop',
        ];
    }

    protected function _getDummyPasswords() {
        return [
            'testpassword',
            '123456',
            'qwerty',
            '111111',
            'iloveyou',
            'adbobe123',
            'admin',
            'letmein',
            'monkey',
            'adobe',
            'sunshine',
            'princess',
            'azerty',
            'trustno1',
            'iamgod',
            'love',
            'god',
            'business',
            'passbolt',
            'enova',
            'kevisthebest'];
    }

    protected function _getPassword($resourceId) {
        static $passwords = [];

        // The resource password has already been determined.
        if (isset($passwords[$resourceId])) {
            return $passwords[$resourceId];
        }

        // If a password has been fixed for a resource.
        $fixedPasswords = $this->_getFixedPasswords();
        if (isset($fixedPasswords[$resourceId])) {
            $password = $fixedPasswords[$resourceId];
        }
        // Else randomly pick up one.
        else {
            $dummyPasswords = $this->_getDummyPasswords();
            $password = $dummyPasswords[array_rand($dummyPasswords)];
        }

        // Store the resource/password association for the next use.
        $passwords[$resourceId] = $password;
        return $password;
    }

    protected function _encrypt($text, $user)
    {
        // Retrieve the user key.
        $GpgkeyTask = $this->Tasks->load('PassboltTestData.Base/GpgkeysData');
        $GpgkeyTask->params = $this->params;
        $gpgkeyPath = $GpgkeyTask->getGpgkeyPath($user->id);

        // Import the user public key.
        exec('gpg --import ' . $gpgkeyPath . ' > /dev/null 2>&1');

        // Encrypt the text.
        $command = "echo -n " . escapeshellarg($text) . " | gpg --encrypt -r " . $user->username . " -a --trust-model always";
        exec($command, $output);

        // Return the armored message.
        return  implode("\n", $output);
    }

    protected function _getData()
    {
        $secrets = [];

        $this->loadModel('Users');
        $this->loadModel('Resources');

        $users = $this->Users->find('index', ['role' => Common::uuid('role.id.admin')]);
        // @todo encrypt only the secret a user is authorized to access.
        $resources = $this->Resources->findIndex();
        foreach($users as $user) {
            foreach($resources as $resource) {
                $password = $this->_getPassword($resource->id);
                $armoredPassword = $this->_encrypt($password, $user);
                $secrets[] = [
                    'id' => Common::uuid(),
                    'user_id' => $user->id,
                    'resource_id' => $resource->id,
                    'data' => $armoredPassword,
                    'created_by' => $user->id
                ];
            }
        }

        return $secrets;
    }
}
