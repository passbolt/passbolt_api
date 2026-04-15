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
 * @since         4.2.0
 */

namespace Passbolt\PasswordPoliciesUpdate\Service;

use App\Error\Exception\FormValidationException;
use App\Utility\ExtendedUserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\PasswordPolicies\Form\PasswordPoliciesSettingsForm;
use Passbolt\PasswordPoliciesUpdate\Model\Dto\PasswordPoliciesUpdateSettingsDto;

class PasswordPoliciesUpdateSetSettingsService
{
    use LocatorAwareTrait;
    use EventDispatcherTrait;

    /**
     * Event name. Fired after passwords policies settings has been saved.
     *
     * @var string
     */
    public const EVENT_SETTINGS_UPDATED = 'Service.PasswordPoliciesSetSettings.updated';

    /**
     * Create passwords policies settings if not present already in DB or updates the settings value if already exists.
     *
     * @param \App\Utility\ExtendedUserAccessControl $uac Extended user access control.
     * @param array $requestData Request data.
     * @return \Passbolt\PasswordPoliciesUpdate\Model\Dto\PasswordPoliciesUpdateSettingsDto
     */
    public function createOrUpdate(ExtendedUserAccessControl $uac, array $requestData): PasswordPoliciesUpdateSettingsDto // phpcs:ignore
    {
        if (!$uac->isAdmin()) {
            throw new ForbiddenException(
                __('Only administrators are allowed to create/update password policies settings.')
            );
        }

        $form = new PasswordPoliciesSettingsForm();
        if (!$form->execute($requestData)) {
            throw new FormValidationException(__('Could not validate the password policies settings.'), $form);
        }

        /** @var \Passbolt\PasswordPoliciesUpdate\Model\Dto\PasswordPoliciesUpdateSettingsDto $settingsDto */
        $settingsDto = PasswordPoliciesUpdateSettingsDto::createFromArray($form->getData());

        /** @var \Passbolt\PasswordPoliciesUpdate\Model\Table\PasswordPoliciesSettingsTable $passwordPoliciesSettingsTable */
        $passwordPoliciesSettingsTable = $this->fetchTable('Passbolt/PasswordPoliciesUpdate.PasswordPoliciesSettings'); // phpcs:ignore

        /** @var \Passbolt\PasswordPoliciesUpdate\Model\Entity\PasswordPoliciesSetting $passwordPoliciesSetting */
        $passwordPoliciesSetting = $passwordPoliciesSettingsTable->createOrUpdateSetting(
            $passwordPoliciesSettingsTable->getProperty(),
            $settingsDto->toOrganizationSettingValueArray(),
            $uac
        );

        $createdUpdatedSettingsDto = PasswordPoliciesUpdateSettingsDto::createFromEntity($passwordPoliciesSetting);

        /** Dispatch settings updated event. */
        $this->dispatchEvent(self::EVENT_SETTINGS_UPDATED, [
            'passwordPoliciesSetting' => $createdUpdatedSettingsDto,
            'uac' => $uac,
        ]);

        return $createdUpdatedSettingsDto;
    }
}
