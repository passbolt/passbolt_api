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
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;

class MetadataKeyDeleteService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    public const AFTER_METADATA_KEY_DELETE_SUCCESS_EVENT_NAME = 'MetadataKey.afterKeyDelete.success';

    /**
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param string $id key uuid
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException if user is not an administrator
     * @throws \Cake\Http\Exception\NotFoundException if the key does not exist or is already deleted
     * @throws \Cake\Http\Exception\BadRequestException if the key id format is Invalid or some items are still using the key
     */
    public function delete(UserAccessControl $uac, string $id): void
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

        // Assert the key is not already deleted
        if ($metadataKey->isDeleted()) {
            throw new NotFoundException(__('The metadata key has already been deleted.'));
        }

        // Assert they key was previously marked as expired
        if (!$metadataKey->isExpired()) {
            throw new BadRequestException(__('The metadata key should be marked as expired first.'));
        }

        // Assert the key is not used by folders, resources, tags, etc.
        if ((new MetadataKeyAssertUsageService())->isKeyInUse($metadataKey->get('id'))) {
            $msg = __('The metadata key is still in use, migrate the remaining items to the new key first.');
            throw new BadRequestException($msg);
        }

        // Patch the key deleted field with the current time
        $options['accessibleFields'] = ['deleted' => true, 'modified_by' => true];
        $patch = ['deleted' => FrozenTime::now(), 'modified_by' => $uac->getId()];
        $metadataKey = $metadataKeysTable->patchEntity($metadataKey, $patch, $options);
        if ($metadataKey->getErrors()) {
            $msg = __('The metadata key could not be deleted.');
            throw new CustomValidationException($msg, $metadataKey->getErrors());
        }

        // Save and delete the private key
        try {
            $metadataKeysTable->getConnection()->transactional(function () use ($metadataKeysTable, $metadataKey) {
                $metadataKeysTable->saveOrFail($metadataKey, ['atomic' => false]);
                $this->fetchTable('Passbolt/Metadata.MetadataPrivateKeys')->deleteAll([
                    'metadata_key_id' => $metadataKey->get('id'),
                ]);
            });
        } catch (\Exception $exception) {
            $msg = __('The metadata key could not be deleted.');
            throw new InternalErrorException($msg, 500, $exception);
        }

        // Dispatch even that will be used to trigger email notification
        $this->dispatchEvent(
            static::AFTER_METADATA_KEY_DELETE_SUCCESS_EVENT_NAME,
            compact('metadataKey', 'uac'),
            $this
        );
    }
}
