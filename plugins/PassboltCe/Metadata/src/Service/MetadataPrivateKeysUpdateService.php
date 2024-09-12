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

use App\Error\Exception\ValidationException;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use Passbolt\Metadata\Model\Entity\MetadataPrivateKey;
use Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable;

class MetadataPrivateKeysUpdateService
{
    use LocatorAwareTrait;

    /**
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param string $privateKeyId uuid
     * @param array $data user provided data
     * @throws \Cake\Http\Exception\BadRequestException if the data is invalid
     * @throws \Cake\Http\Exception\NotFoundException if the record is not found or does not belong to the user
     * @throws \App\Error\Exception\ValidationException if the data does not validate
     * @throws \Cake\Http\Exception\InternalErrorException if data could not be saved because of an internal issue
     * @return \Passbolt\Metadata\Model\Entity\MetadataPrivateKey
     */
    public function update(UserAccessControl $uac, string $privateKeyId, array $data): MetadataPrivateKey
    {
        if (!isset($data['data']) || !is_string($data['data'])) {
            throw new BadRequestException(__('The request data is invalid.'));
        }
        if (!Validation::uuid($privateKeyId)) {
            throw new BadRequestException(__('The request data is invalid.'));
        }

        /** @var \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable $metadataPrivateKeysTable */
        $metadataPrivateKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataPrivateKeys');
        try {
            /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $metadataPrivateKey */
            $metadataPrivateKey = $metadataPrivateKeysTable
                ->find()
                ->where(['user_id' => $uac->getId(), 'id ' => $privateKeyId])
                ->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The metadata private key does not exist or has been deleted.'));
        }

        if ($metadataPrivateKey->modified_by === $uac->getId()) {
            throw new BadRequestException(__('The metadata private key was already edited by the user.'));
        }

        $metadataPrivateKeysTable->patchEntity($metadataPrivateKey, [
            'data' => $data['data'],
            'modified_by' => $uac->getId(),
        ], [
            'accessibleFields' => [
                'data' => true,
                'modified_by' => true,
            ],
        ]);
        if (!empty($metadataPrivateKey->getErrors())) {
            $this->handleValidationErrors($metadataPrivateKey, $metadataPrivateKeysTable);
        }

        $updated = $metadataPrivateKeysTable->save($metadataPrivateKey);
        if ($updated === false) {
            if (!empty($metadataPrivateKey->getErrors())) {
                $this->handleValidationErrors($metadataPrivateKey, $metadataPrivateKeysTable);
            }
            $msg = __('The metadata private key could not be updated.') . ' ';
            $msg .= __('Please try again later.');
            throw new InternalErrorException($msg);
        }

        return $updated;
    }

    /**
     * Handle validation or build rules failure
     *
     * @param \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $entity that is failing the validation
     * @param \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable $table table
     * @throws \App\Error\Exception\ValidationException
     * @return void
     */
    public function handleValidationErrors(MetadataPrivateKey $entity, MetadataPrivateKeysTable $table): void
    {
        if (Configure::read('debug')) {
            Log::error(json_encode($entity->getErrors()));
        }
        $msg = __('The metadata private key could not be validated.');
        throw new ValidationException($msg, $entity, $table);
    }
}
