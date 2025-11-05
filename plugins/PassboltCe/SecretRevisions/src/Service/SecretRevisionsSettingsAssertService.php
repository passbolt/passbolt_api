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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions\Service;

use App\Error\Exception\FormValidationException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\SecretRevisions\Form\SecretRevisionsSettingsForm;
use Passbolt\SecretRevisions\Model\Dto\SecretRevisionsSettingsDto;

class SecretRevisionsSettingsAssertService
{
    use LocatorAwareTrait;

    /**
     * Validate secret revision settings.
     *
     * @param array $data Data to assert.
     * @return \Passbolt\SecretRevisions\Model\Dto\SecretRevisionsSettingsDto DTO.
     * @throws \App\Error\Exception\FormValidationException Data does not validate.
     */
    public function assert(array $data): SecretRevisionsSettingsDto
    {
        $form = new SecretRevisionsSettingsForm();
        if (!$form->execute($data)) {
            throw new FormValidationException(__('Could not validate the settings.'), $form);
        }

        return SecretRevisionsSettingsDto::fromArray($form->getData());
    }
}
