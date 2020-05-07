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
namespace App\Test\Lib\Model;

use App\Model\Entity\Secret;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

trait SecretsModelTrait
{
    /**
     * Add a dummy secret.
     *
     * @param array $data The secret data
     * @param array $options The entity options
     * @return Secret
     */
    public function addSecret($data = [], $options = [])
    {
        $secretsTable = TableRegistry::getTableLocator()->get('Secrets');

        $secret = $secretsTable->findByResourceIdAndUserId($data['resource_id'], $data['user_id'])->first();
        if (!empty($secret)) {
            return $secret;
        }

        $secret = self::getDummySecretEntity($data, $options);
        $secretsTable->saveOrFail($secret, ['checkRules' => false]);

        return $secret;
    }

    /**
     * Get a new secret entity
     *
     * @param array $data The secret data.
     * @param array $options The new entity options.
     * @return Secret
     */
    public function getDummySecretEntity($data = [], $options = [])
    {
        $secretsTable = TableRegistry::getTableLocator()->get('Secrets');
        $defaultOptions = [
            'validate' => false,
            'accessibleFields' => [
                '*' => true,
            ],
        ];

        $data = self::getDummySecretData($data);
        $options = array_merge($defaultOptions, $options);

        return $secretsTable->newEntity($data, $options);
    }

    /**
     * Encrypt a message for a user.
     * @param string $userId The user to encrypt for
     * @return mixed
     */
    public static function encryptMessageFor(string $userId, string $message)
    {
        $gpg = OpenPGPBackendFactory::get();
        $gpgkeysTable = TableRegistry::getTableLocator()->get('Gpgkeys');
        $gpgKey = $gpgkeysTable->find()->where(['user_id' => $userId])->first();
        $gpg->importKeyIntoKeyring($gpgKey->armored_key);
        $gpg->setEncryptKeyFromFingerprint($gpgKey->fingerprint);

        return $gpg->encrypt($message);
    }

    /**
     * Get a dummy secret with test data.
     * The comment returned passes a default validation.
     *
     * @param array $data Custom data that will be merged with the default content.
     * @return array Comment data
     */
    public static function getDummySecretData($data = [])
    {
        $entityContent = [
            'resource_id' => UuidFactory::uuid('resource.id.april'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'data' => '-----BEGIN PGP MESSAGE-----

hQIMA1P90Qk1JHA+ARAAu3oaLzv/BfeukST6tYAkAID+xbt5dhsv4lxL3oSbo8Nm
qmJQSVe6wmh8nZJjeHN4L7iCq8FEZpdCwrDbX1qIuqBFFO3vx6BJFOURG0JbI/E/
nXtvck00RvxTB1Y30OUbGp21jjEILyuELhWpf11+AQelybY4XKyM8UxGjSncDqaS
X7/yXspCByywci1VfzK7D6+zfcyLy29wQm9Ci5j6I4QqhvlKQPTxl6tWrJh+EyLP
SLZjO8ofc00fbc7mUIH5taDg6Br2VLG/x29HhKCPYdOVzSz3BpUCcUcPgn98mCV0
Qh7ZPE1NNmCWXID5hryuSF71IiAYhxae9u77pOAbVe0PwFgMY6kke/hJQkO6IYJ/
/Q3aL/xHTlY2XtPbpV1in6soc0wJBuoROrwN0AdtvEJOnomclNEH5BPwLjZ1shCr
vuk0zJjj9WcqQiVNEuErs4d7rLc+dB7md+97S8Gtcf8lrlZMH9ooI2UnvxC8HRqX
KzcgW17YF44VtD2TLMymvpnjPV9gruYnmpkQG/1ihnDOWe6xWlFH6jZf5eE4IEVn
osx/D6inZHHMXWbZu9hMiQloKKZ0s8yxTFw9C1wFwaIxRtvJ84qc17rJs7mfcC2n
sG7jLzQBV/GVWtR4hVebstP+q05Sib+sKwLOTZhzWNPKruBsdaBCUTxcmI6qwDHS
QQFgGx0K1xQj2rKiP2j0cDHyGsWIlOITN+4r6Ohx23qRhVo0txPWVOYLpC8JnlfQ
W3AI8+rWjK8MGH2T88hCYI/6
=uahb
-----END PGP MESSAGE-----',
        ];
        $entityContent = array_merge($entityContent, $data);

        return $entityContent;
    }

    /**
     * Asserts that an object has all the attributes a secret should have.
     *
     * @param object $secret
     */
    protected function assertSecretAttributes($secret)
    {
        $attributes = ['id', 'user_id', 'resource_id', 'data', 'created', 'modified'];
        $this->assertObjectHasAttributes($attributes, $secret);
    }

    /**
     * Assert a secret exists
     * @param $resourceId
     * @param $userId
     */
    protected function assertSecretExists($resourceId, $userId)
    {
        $secretsTable = TableRegistry::getTableLocator()->get('Secrets');
        $secret = $secretsTable->find()->where(['resource_id' => $resourceId, 'user_id' => $userId])->first();
        $this->assertNotEmpty($secret);
    }

    /**
     * Assert a secret does not exist
     * @param $resourceId
     * @param $userId
     */
    protected function assertSecretNotExist($resourceId, $userId)
    {
        $secretsTable = TableRegistry::getTableLocator()->get('Secrets');
        $secret = $secretsTable->find()->where(['resource_id' => $resourceId, 'user_id' => $userId])->first();
        $this->assertEmpty($secret);
    }
}
