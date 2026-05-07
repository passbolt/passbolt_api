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
 * @since         4.3.0
 */

namespace Passbolt\UserPassphrasePolicies\Service;

use App\Error\Exception\FormValidationException;
use App\Utility\ExtendedUserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\UserPassphrasePolicies\Form\UserPassphrasePoliciesSettingsForm;
use Passbolt\UserPassphrasePolicies\Model\Dto\UserPassphrasePoliciesSettingsDto;

class UserPassphrasePoliciesSetSettingsService
{
    use LocatorAwareTrait;
    use EventDispatcherTrait;

    /**
     * Event name. Fired after user passphrase policies settings has been saved.
     *
     * @var string
     */
    public const EVENT_SETTINGS_UPDATED = 'Service.UserPassphrasePoliciesSetSettings.updated';

    /**
     * Create user passphrase policy settings if not already present in DB or update the settings value if already exists.
     *
     * @param \App\Utility\ExtendedUserAccessControl $uac Extended user access control.
     * @param array $requestData Request data.
     * @return \Passbolt\UserPassphrasePolicies\Model\Dto\UserPassphrasePoliciesSettingsDto
     * @throws \Exception
     */
    public function createOrUpdate(ExtendedUserAccessControl $uac, array $requestData): UserPassphrasePoliciesSettingsDto // phpcs:ignore
    {
        if (!$uac->isAdmin()) {
            throw new ForbiddenException(
                __('Only administrators are allowed to create/update user passphrase policies settings.')
            );
        }

        $form = new UserPassphrasePoliciesSettingsForm();
        if (!$form->execute($requestData)) {
            throw new FormValidationException(
                __('Could not validate the user passphrase policies settings.'),
                $form
            );
        }

        /** @var \Passbolt\UserPassphrasePolicies\Model\Dto\UserPassphrasePoliciesSettingsDto $settingsDto */
        $settingsDto = UserPassphrasePoliciesSettingsDto::createFromArray($form->getData());

        /** @var \Passbolt\UserPassphrasePolicies\Model\Table\UserPassphrasePoliciesSettingsTable $userPassphrasePoliciesSettingsTable */
        $userPassphrasePoliciesSettingsTable = $this->fetchTable('Passbolt/UserPassphrasePolicies.UserPassphrasePoliciesSettings'); // phpcs:ignore

        /** @var \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting $userPassphrasePoliciesSetting */
        $userPassphrasePoliciesSetting = $userPassphrasePoliciesSettingsTable->createOrUpdateSetting(
            $userPassphrasePoliciesSettingsTable->getProperty(),
            $settingsDto->toOrganizationSettingValueArray(),
            $uac
        );

        $createdUpdatedSettingsDto = UserPassphrasePoliciesSettingsDto::createFromEntity($userPassphrasePoliciesSetting); // phpcs:ignore

        /** Dispatch settings updated event. */
        $this->dispatchEvent(self::EVENT_SETTINGS_UPDATED, [
            'userPassphrasePoliciesSetting' => $createdUpdatedSettingsDto,
            'uac' => $uac,
        ]);

        return $createdUpdatedSettingsDto;
    }
}
