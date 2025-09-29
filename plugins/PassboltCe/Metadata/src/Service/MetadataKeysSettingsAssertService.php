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
use Passbolt\Metadata\Form\MetadataKeysSettingsForm;
use Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto;

class MetadataKeysSettingsAssertService
{
    /**
     * Validates the setting and return them
     *
     * @param array $data untrusted input
     * @param bool $validateWithMetadataPrivateKeys flag to know if metadata private key validation should be performed
     * @return \Passbolt\Metadata\Model\Dto\MetadataKeysSettingsDto dto
     * @throws \App\Error\Exception\FormValidationException if the data does not validate
     */
    public function assert(array $data, bool $validateWithMetadataPrivateKeys = false): MetadataKeysSettingsDto
    {
        $form = new MetadataKeysSettingsForm();
        $validate = $validateWithMetadataPrivateKeys ? 'withMetadataPrivateKeys' : 'default';
        if (!$form->execute($data, compact('validate'))) {
            throw new FormValidationException(__('Could not validate the settings.'), $form);
        }

        // TODO build rules
        // if ZERO_KNOWLEDGE_KEY_SHARE && metadata private key exist in settings
        //  then metadata private key must be available for the server

        return new MetadataKeysSettingsDto($form->getData());
    }
}
