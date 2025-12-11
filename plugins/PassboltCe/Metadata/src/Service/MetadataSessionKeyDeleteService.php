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

use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;

class MetadataSessionKeyDeleteService
{
    use LocatorAwareTrait;

    /**
     * Delete the given metadata session key.
     *
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @param string $id The metadata session key identifier.
     * @return void
     */
    public function delete(UserAccessControl $uac, string $id): void
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The metadata session key identifier should be a UUID.'));
        }

        /** @var \Passbolt\Metadata\Model\Table\MetadataSessionKeysTable $metadataSessionKeysTable */
        $metadataSessionKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataSessionKeys');

        try {
            /** @var \Passbolt\Metadata\Model\Entity\MetadataSessionKey $metadataSessionKey */
            $metadataSessionKey = $metadataSessionKeysTable
                ->find()
                ->where(['id' => $id, 'user_id' => $uac->getId()])
                ->firstOrFail();
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The metadata session key does not exist or does not belong to this user.')); // phpcs:ignore
        }

        if ($metadataSessionKeysTable->delete($metadataSessionKey)) {
            return;
        }

        // In scenarios where requests are sent twice delete can fail.
        // Check for the record again and if it doesn't exist then throw 404. If present and delete fail then throw a 500.
        $exists = $metadataSessionKeysTable
            ->find()
            ->select(['id'])
            ->where(['id' => $id, 'user_id' => $uac->getId()])
            ->first();

        $exists
            ? throw new InternalErrorException(__('The metadata session key could not be deleted.'))
            : throw new NotFoundException(__('The metadata session key does not exist or does not belong to this user.')); // phpcs:ignore
    }
}
