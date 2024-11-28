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
namespace Passbolt\Metadata\Service\MetadataKey;

use App\Error\Exception\CustomValidationException;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use Passbolt\Metadata\Model\Dto\MetadataKeyUpdateDto;

class MetadataKeyUpdateService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    public const AFTER_METADATA_KEY_UPDATE_SUCCESS_EVENT_NAME = 'MetadataKey.afterKeyUpdate.success';

    /**
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param string $id key uuid
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException if user is not an administrator
     * @throws \Cake\Http\Exception\NotFoundException if the key does not exist or is already expired
     * @throws \Cake\Http\Exception\BadRequestException if the key id format is Invalid or some items are still using the key
     */
    public function update(UserAccessControl $uac, string $id, MetadataKeyUpdateDto $dto): void
    {
        $uac->assertIsAdmin();

        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The metadata key ID should be a valid UUID.'));
        }

        $metadataKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataKeys');

        // Assert the key exist
        try {
            /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $metadataKey */
            $metadataKey = $metadataKeysTable->get($id);
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The metadata key does not exist or has been deleted.'), 404, $exception);
        }

        // Assert fingerprint is the same
        if ($metadataKey->fingerprint !== $dto->fingerprint) {
            throw new NotFoundException(__('The metadata key fingerprint is invalid.'));
        }

        // Assert the key is not already deleted
        if ($metadataKey->isDeleted()) {
            throw new NotFoundException(__('The metadata key has already been deleted.'));
        }

        // Assert they key was not previously marked as expired
        if ($metadataKey->isExpired()) {
            throw new BadRequestException(__('The metadata key is already marked as expired.'));
        }

        // Patch the key deleted field with the current time
        $options = [
            'accessibleFields' => [
                'fingerprint' => true, 'armored_key' => true, 'expired' => true, 'modified_by' => true,
            ],
            'validate' => 'update',
        ];
        $patch = [
            'fingerprint' => $dto->fingerprint,
            'armored_key' => $dto->armoredKey,
            'expired' => $dto->expired,
            'modified_by' => $uac->getId(),
        ];
        $metadataKey = $metadataKeysTable->patchEntity($metadataKey, $patch, $options);
        if ($metadataKey->getErrors()) {
            $msg = __('The metadata key could not be updated.');
            throw new CustomValidationException($msg, $metadataKey->getErrors());
        }
        try {
            $metadataKeysTable->saveOrFail($metadataKey);
        } catch (PersistenceFailedException $exception) {
            $msg = __('The metadata key could not be updated.');
            throw new InternalErrorException($msg, 500, $exception);
        }

        // Dispatch even that will be used to trigger email notification
        $this->dispatchEvent(
            static::AFTER_METADATA_KEY_UPDATE_SUCCESS_EVENT_NAME,
            compact('metadataKey', 'uac'),
            $this
        );
    }
}
