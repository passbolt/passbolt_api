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

use App\Error\Exception\FormValidationException;
use Passbolt\Metadata\Form\MetadataTypesSettingsForm;
use Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto;

class MetadataTypesSettingsAssertService
{
    /**
     * Validates the setting and return them
     *
     * @param array $data untrusted input
     * @return \Passbolt\Metadata\Model\Dto\MetadataTypesSettingsDto dto
     * @throws \App\Error\Exception\FormValidationException if the data does not validate
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
        // Admin selects v5 but metadata keys do not exist or are not available to all

        return $dto;
    }
}
