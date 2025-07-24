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
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Form\MetadataTypesSettingsForm;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;

class MetadataTypesSettingsAssertService
{
    use LocatorAwareTrait;

    /**
     * Validates the setting and return them
     *
     * @param array $data untrusted input
     * @return \Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto dto
     * @throws \App\Error\Exception\FormValidationException if the data does not validate
     * @throws \App\Error\Exception\CustomValidationException if not active metadata key is found in DB and v5 is enabled
     */
    public function assert(array $data): MetadataTypesSettingsDto
    {
        $form = new MetadataTypesSettingsForm();
        if (!$form->execute($data)) {
            throw new FormValidationException(__('Could not validate the settings.'), $form);
        }

        $dto = new MetadataTypesSettingsDto($form->getData());

        // TODO "Build rules"
        // Admin select a default resource version but all resource types are deleted for this version

        return $dto;
    }

    /**
     * @param \Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto $dto DTO
     * @return void
     * @throws \App\Error\Exception\CustomValidationException if no active metadata key is found in DB and v5 is enabled
     */
    public function assertThatAnActiveMetadataKeyExistsWhenV5IsEnabled(MetadataTypesSettingsDto $dto): void
    {
        if (!$dto->isV5Enabled()) {
            return;
        }

        $metadataKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataKeys');

        $activeMetadataKey = $metadataKeysTable->find()
            ->where(function (QueryExpression $exp) {
                return $exp->isNull('deleted');
            })
            ->where(function (QueryExpression $exp) {
                return $exp->isNull('expired');
            })->first();

        if (!$activeMetadataKey) {
            $msg = __('Unable to save the metadata settings.') . ' ' .
                __('An active metadata key could not be found, create a key first.');
            throw new CustomValidationException($msg);
        }
    }
}
