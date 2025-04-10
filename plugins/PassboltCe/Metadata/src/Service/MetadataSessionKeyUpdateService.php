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

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\FormValidationException;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ConflictException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\DateTime;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use Exception;
use Passbolt\Metadata\Form\MetadataSessionKeyUpdateForm;
use Passbolt\Metadata\Model\Entity\MetadataSessionKey;

class MetadataSessionKeyUpdateService
{
    use LocatorAwareTrait;

    /**
     * Delete the given metadata session key.
     *
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @param string $id The metadata session key identifier.
     * @param array $data non-empty array of user provided data
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Http\Exception\NotFoundException
     * @throws \Cake\Http\Exception\ConflictException
     * @throws \App\Error\Exception\CustomValidationException
     * @return \Passbolt\Metadata\Model\Entity\MetadataSessionKey
     */
    public function update(UserAccessControl $uac, string $id, array $data): MetadataSessionKey
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The metadata session key identifier should be a UUID.'));
        }

        // 400 invalid user provided data, we expect [modified:<datetime>, data:<string>]
        $form = new MetadataSessionKeyUpdateForm();
        if (!$form->execute($data)) {
            throw new FormValidationException(__('Could not validate the data.'), $form);
        }
        $data = $form->getData();

        /** @var \Passbolt\Metadata\Model\Table\MetadataSessionKeysTable $metadataSessionKeysTable */
        $metadataSessionKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataSessionKeys');

        try {
            /** @var \Passbolt\Metadata\Model\Entity\MetadataSessionKey $metadataSessionKey */
            $metadataSessionKey = $metadataSessionKeysTable
                ->find()
                ->where(['id' => $id, 'user_id' => $uac->getId()])
                ->firstOrFail();
        } catch (RecordNotFoundException $e) {
            // 404 session key entry does not exist or not for current user_id
            throw new NotFoundException(__('The metadata session key does not exist or does not belong to this user.'));
        }

        // 400 no changes to be made
        if ($data['data'] === $metadataSessionKey->get('data')) {
            throw new BadRequestException(__('The metadata session key data is identical.'));
        }
        // 409 if the modified date is not equal to the persisted session key one
        $asserTime = (new DateTime($data['modified']))->equals($metadataSessionKey->get('modified'));
        if (!$asserTime) {
            throw new ConflictException(__('The metadata session key data has changed.'));
        }

        $metadataSessionKey = $metadataSessionKeysTable->patchEntity(
            $metadataSessionKey,
            ['data' => $data['data']],
            ['accessibleFields' => ['data' => true]]
        );

        try {
            /** @var \Passbolt\Metadata\Model\Entity\MetadataSessionKey $updatedEntity */
            $updatedEntity = $metadataSessionKeysTable->saveOrFail($metadataSessionKey);
        } catch (PersistenceFailedException $exception) { // @phpstan-ignore-line
            // 400 openpgp data does not validate, for example it's not for the current user
            throw new CustomValidationException(
                __('The metadata session key could not be saved.'),
                $exception->getEntity()->getErrors()
            );
        } catch (Exception $exception) {
            // 500 entry could not be deleted because of some internal error
            throw new InternalErrorException(
                __('Could not save the metadata session keys, please try again later.'),
                null,
                $exception
            );
        }

        return $updatedEntity;
    }
}
