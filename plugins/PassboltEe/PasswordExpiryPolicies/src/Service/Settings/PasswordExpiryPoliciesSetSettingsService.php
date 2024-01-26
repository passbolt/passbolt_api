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

namespace Passbolt\PasswordExpiryPolicies\Service\Settings;

use Passbolt\PasswordExpiry\Form\PasswordExpirySettingsForm;
use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;
use Passbolt\PasswordExpiry\Model\Entity\PasswordExpirySetting;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpirySetSettingsService;
use Passbolt\PasswordExpiryPolicies\Form\PasswordExpiryPoliciesSettingsForm;
use Passbolt\PasswordExpiryPolicies\Model\Dto\PasswordExpiryPoliciesSettingsDto;

class PasswordExpiryPoliciesSetSettingsService extends PasswordExpirySetSettingsService
{
    /**
     * @inheritDoc
     */
    protected function getForm(): PasswordExpirySettingsForm
    {
        return new PasswordExpiryPoliciesSettingsForm();
    }

    /**
     * @inheritDoc
     */
    protected function createDTOFromEntity(
        PasswordExpirySetting $passwordExpirySetting,
        PasswordExpirySettingsForm $form
    ): PasswordExpirySettingsDto {
        return PasswordExpiryPoliciesSettingsDto::createFromEntity($passwordExpirySetting, $form);
    }

    /**
     * @inheritDoc
     */
    protected function createDTOFromArray(array $data = []): PasswordExpirySettingsDto
    {
        return PasswordExpiryPoliciesSettingsDto::createFromArray($data);
    }
}
