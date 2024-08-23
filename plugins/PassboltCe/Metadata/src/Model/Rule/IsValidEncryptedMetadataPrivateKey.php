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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Model\Rule;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

class IsValidEncryptedMetadataPrivateKey
{
    /**
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        $userId = $entity->get('user_id');

        try {
            [$fingerprint, $armoredKey] = $this->getKeyDetails($userId);
        } catch (RecordNotFoundException $e) {
            return false;
        }

        if (!is_string($fingerprint)) {
            return false;
        }

        $gpg = OpenPGPBackendFactory::get();
        try {
            $gpg->importKeyIntoKeyring($armoredKey);
            $gpg->setVerifyKeyFromFingerprint($fingerprint);
            $gpg->verify($entity->get('data'));

            return true;
        } catch (\Exception $exception) {
            Log::error('IsValidEncryptedMetadataPrivateKey: ' . $exception->getMessage());
            // consider it fail
        }

        return false;
    }

    /**
     * @param string|null $userId User identifier.
     * @return array
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When user is not found.
     */
    private function getKeyDetails(?string $userId): array
    {
        if (is_null($userId)) {
            $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
            $armoredKey = file_get_contents(Configure::read('passbolt.gpg.serverKey.public'));
        } else {
            $usersTable = TableRegistry::getTableLocator()->get('Users');
            /** @var \App\Model\Entity\User $user */
            $user = $usersTable->find()
                ->where(['Users.id' => $userId])
                ->contain(['Gpgkeys'])
                ->firstOrFail();

            $fingerprint = $user->gpgkey->fingerprint;
            $armoredKey = $user->gpgkey->armored_key;
        }

        return [$fingerprint, $armoredKey];
    }
}
