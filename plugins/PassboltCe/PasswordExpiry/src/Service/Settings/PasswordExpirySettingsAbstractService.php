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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiry\Service\Settings;

use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\PasswordExpiry\Form\PasswordExpirySettingsForm;
use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;
use Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting;

abstract class PasswordExpirySettingsAbstractService
{
    use LocatorAwareTrait;

    protected PasswordExpirySettingsForm $form;

    /**
     * Get the settings form used by set and get settings services
     *
     * @return \Passbolt\PasswordExpiry\Form\PasswordExpirySettingsForm
     */
    protected function getForm(): PasswordExpirySettingsForm
    {
        return new PasswordExpirySettingsForm();
    }

    /**
     * @inheritDoc
     */
    protected function createDTOFromEntity(
        PasswordExpirySetting $passwordExpirySetting,
        PasswordExpirySettingsForm $form
    ): PasswordExpirySettingsDto {
        return PasswordExpirySettingsDto::createFromEntity($passwordExpirySetting, $form);
    }

    /**
     * @inheritDoc
     */
    protected function createDTOFromArray(array $data = []): PasswordExpirySettingsDto
    {
        return PasswordExpirySettingsDto::createFromArray($data);
    }
}
