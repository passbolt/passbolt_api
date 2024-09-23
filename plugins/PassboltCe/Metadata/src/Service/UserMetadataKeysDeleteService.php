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
namespace Passbolt\Metadata\Service;

use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

class UserMetadataKeysDeleteService
{
    use LocatorAwareTrait;

    /**
     * Delete user metadata private & session keys.
     *
     * @param string $userId User identifier.
     * @return void
     */
    public function delete(string $userId): void
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user identifier should be a UUID.'));
        }

        $this->deleteMetadataPrivateKeys($userId);
        $this->deleteMetadataSessionKeys($userId);
    }

    /**
     * @param string $userId User identifier.
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException If data is not deleted.
     */
    private function deleteMetadataPrivateKeys(string $userId): void
    {
        /** @var \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable $metadataPrivateKeysTable */
        $metadataPrivateKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataPrivateKeys');

        $metadataPrivateKeys = $metadataPrivateKeysTable
            ->find()
            ->select(['id'])
            ->where(['user_id' => $userId])
            ->disableHydration()
            ->toArray();
        if (empty($metadataPrivateKeys)) {
            // Nothing to delete
            return;
        }

        $metadataPrivateKeysIds = Hash::extract($metadataPrivateKeys, '{n}.id');
        $result = $metadataPrivateKeysTable->deleteAll(['id IN' => $metadataPrivateKeysIds]);
        if ($result <= 0) {
            throw new InternalErrorException(__('The user metadata private keys could not be deleted.'));
        }
    }

    /**
     * @param string $userId User identifier.
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException If data is not deleted.
     */
    private function deleteMetadataSessionKeys(string $userId): void
    {
        /** @var \Passbolt\Metadata\Model\Table\MetadataSessionKeysTable $metadataSessionKeysTable */
        $metadataSessionKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataSessionKeys');

        $metadataSessionKeys = $metadataSessionKeysTable
            ->find()
            ->select(['id'])
            ->where(['user_id' => $userId])
            ->disableHydration()
            ->toArray();
        if (empty($metadataSessionKeys)) {
            // Nothing to delete
            return;
        }

        $metadataPrivateKeysIds = Hash::extract($metadataSessionKeys, '{n}.id');
        $result = $metadataSessionKeysTable->deleteAll(['id IN' => $metadataPrivateKeysIds]);
        if ($result <= 0) {
            throw new InternalErrorException(__('The user metadata session keys could not be deleted.'));
        }
    }
}
