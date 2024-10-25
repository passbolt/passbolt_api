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
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use Passbolt\Metadata\Model\Entity\MetadataPrivateKey;
use Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable;

class MetadataPrivateKeysCreateService
{
    use LocatorAwareTrait;

    /**
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param string $metadataKeyId uuid
     * @param array $data user provided data
     * @return \Passbolt\Metadata\Model\Entity\MetadataPrivateKey
     * @throws \Cake\Http\Exception\BadRequestException if the data is invalid
     * @throws \App\Error\Exception\ValidationException if the data does not validate
     * @throws \Cake\Http\Exception\InternalErrorException if data could not be saved because of an internal issue
     * @throws \Cake\Http\Exception\NotFoundException if the key was not found
     */
    public function create(UserAccessControl $uac, string $metadataKeyId, array $data): MetadataPrivateKey
    {
        $this->assertRequestSanity($uac, $metadataKeyId, $data);

        /** @var \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable $metadataPrivateKeysTable */
        $metadataPrivateKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataPrivateKeys');

        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $metadataPrivateKey */
        $metadataPrivateKey = $metadataPrivateKeysTable->newEntity([
            'metadata_key_id' => $metadataKeyId,
            'user_id' => $data['user_id'],
            'data' => $data['data'],
            'created_by' => $uac->getId(),
            'modified_by' => $uac->getId(),
        ], [
            'accessibleFields' => [
                'metadata_key_id' => true,
                'user_id' => true,
                'data' => true,
                'created_by' => true,
                'modified_by' => true,
            ],
        ]);
        if (!empty($metadataPrivateKey->getErrors())) {
            $this->handleValidationErrors($metadataPrivateKey, $metadataPrivateKeysTable);
        }

        $created = $metadataPrivateKeysTable->save($metadataPrivateKey);
        if ($created === false) {
            if (!empty($metadataPrivateKey->getErrors())) {
                $this->handleValidationErrors($metadataPrivateKey, $metadataPrivateKeysTable);
            }
            $msg = __('The metadata private key could not be updated.') . ' ';
            $msg .= __('Please try again later.');
            throw new InternalErrorException($msg);
        }

        return $created;
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

    /**
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param string $metadataKeyId key id
     * @param array $data user provided data
     * @return void
     */
    public function assertRequestSanity(UserAccessControl $uac, string $metadataKeyId, array $data): void
    {
        $uac->assertIsAdmin();
        if (!Validation::uuid($metadataKeyId)) {
            throw new BadRequestException(__('The request data is invalid.'));
        }
        if (isset($data['user_id']) && !Validation::uuid($data['user_id'])) {
            throw new BadRequestException(__('The request data is invalid.'));
        }
        if (!isset($data['data']) || !is_string($data['data'])) {
            throw new BadRequestException(__('The request data is invalid.'));
        }

        // Assert metadata key exist
        /** @var \Passbolt\Metadata\Model\Table\MetadataKeysTable $metadataKeysTable */
        $metadataKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataKeys');
        try {
            $metadataKeysTable
                ->find()
                ->where(['id' => $metadataKeyId, 'deleted IS' => null])
                ->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The metadata key does not exist or has been deleted.'));
        }

        // Assert private key does not already exist for the user/server
        /** @var \Passbolt\Metadata\Model\Table\MetadataPrivateKeysTable $metadataPrivateKeysTable */
        $metadataPrivateKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataPrivateKeys');
        $metadataPrivateKey = $metadataPrivateKeysTable->find()
            ->where(['metadata_key_id' => $metadataKeyId])
            ->where(function (QueryExpression $exp) use ($data) {
                if (isset($data['user_id'])) {
                    return $exp->eq('user_id', $data['user_id']);
                }

                return $exp->isNull('user_id');
            })
            ->first();
        if (!empty($metadataPrivateKey)) {
            throw new BadRequestException(__('The metadata key is already shared with the user.'));
        }
    }
}
