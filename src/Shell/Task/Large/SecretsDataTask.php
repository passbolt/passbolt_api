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
namespace PassboltTestData\Shell\Task\Large;

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataTask;

class SecretsDataTask extends DataTask
{
    public $entityName = 'Secrets';
    protected $gpgkeys = [];

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $this->loadModel('Gpgkeys');
        $this->loadModel('Secrets');

        // Retrieve the key info.
        // As a default key can be shared among user, the encryption will require the key fingerprint.
        // As the key meta data are already stored in db, get the meta data from the db and avoid performance issue
        // by avoiding any gpg extra parsing.
        $gpgkeys = $this->Gpgkeys->find()->all();
        foreach ($gpgkeys as $gpgkey) {
            $this->gpgkeys[$gpgkey->user_id] = $gpgkey->fingerprint;
        }

        return parent::execute();
    }

    /**
     * Get encrypted secrets
     *
     * @return array
     */
    public function getData()
    {
        if (Configure::read('passbolt.gpg.putenv')) {
            putenv('GNUPGHOME=' . Configure::read('passbolt.gpg.keyring'));
        }

        $secrets = [];

        $this->loadModel('Users');
        $this->loadModel('Resources');

        $users = $this->Users->findIndex(Role::USER);
        foreach ($users as $user) {
            $resources = $this->Resources->findIndex($user->id);
            foreach ($resources as $resource) {
                $armoredPassword = $this->_encrypt('dummy password', $user);
                $secrets[] = [
                    'id' => UuidFactory::uuid("secret.id.{$resource->id}-{$user->id}"),
                    'user_id' => $user->id,
                    'resource_id' => $resource->id,
                    'data' => $armoredPassword,
                    'created_by' => $user->id,
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                ];
            }
        }

        return $secrets;
    }

    /**
     * Encrypt passwords
     *
     * @param string $text password
     * @param \Cake\ORM\Entity $user User
     * @return array
     */
    protected function _encrypt($text, $user)
    {
        static $keyImported = [];
        static $encrypted = [];
        $keyFingerprint = $this->gpgkeys[$user->id];

        // Import the user public key.
        if (!isset($keyImported[$keyFingerprint])) {
            // Retrieve the user key file.
            $GpgkeyTask = $this->Tasks->load('PassboltTestData.Base/GpgkeysData');
            $GpgkeyTask->params = $this->params;
            $gpgkeyPath = $GpgkeyTask->getGpgkeyPath($user->id);

            exec('gpg --import ' . $gpgkeyPath . ' > /dev/null 2>&1');
            $keyImported[$keyFingerprint] = true;
        }

        // Encrypt the text.
        if (!isset($encrypted[$keyFingerprint][$text])) {
            $command = "echo -n " . escapeshellarg($text) . " | gpg --encrypt -r " . $keyFingerprint . " -a --trust-model always";
            exec($command, $output);

            // Return the armored message.
            $encrypted[$keyFingerprint][$text] = implode("\n", $output);
        }

        return $encrypted[$keyFingerprint][$text];
    }
}
