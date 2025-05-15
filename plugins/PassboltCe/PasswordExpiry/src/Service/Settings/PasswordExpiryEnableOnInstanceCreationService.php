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
 * @since         5.1.0
 */

namespace Passbolt\PasswordExpiry\Service\Settings;

use App\Model\Entity\OrganizationSetting;
use App\Utility\UserAccessControl;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto;
use Passbolt\PasswordExpiry\Model\Table\PasswordExpirySettingsTable;

class PasswordExpiryEnableOnInstanceCreationService
{
    use LocatorAwareTrait;

    private PasswordExpirySettingsTable $passwordExpirySettingsTable;

    /**
     * PasswordExpiryEnableOnInstanceCreationService constructor
     */
    public function __construct()
    {
        $this->passwordExpirySettingsTable = $this->fetchTable('Passbolt/PasswordExpiry.PasswordExpirySettings');
    }

    /**
     * Inserts the default settings DB
     * if the passbolt instance is being created.
     *
     * @param \App\Utility\UserAccessControl $uac
     * @return ?\App\Model\Entity\OrganizationSetting
     */
    public function enableOnPassboltInstanceCreation(UserAccessControl $uac): ?OrganizationSetting
    {
        // If any active users other than the UAC are found in the DB, do not insert the settings in DB
        if (!$this->isPassboltInstanceNew()) {
            return null;
        }

        // Return silently if for any unexpected reasons the UAC is not admin
        if (!$uac->isAdmin()) {
            Log::warning('The user creating a new passbolt instance should be an administrator.');

            return null;
        }

        // Return silently if for any unexpected reason the settings are already in DB
        if ($this->isPasswordExpirySettingAlreadyEnabled()) {
            Log::warning('Password expiry settings already present in the database.');

            return null;
        }

        return $this->passwordExpirySettingsTable->createOrUpdateSetting(
            $this->passwordExpirySettingsTable->getProperty(),
            $this->getDTO()->getValue(),
            $uac
        );
    }

    /**
     * @return bool
     */
    private function isPassboltInstanceNew(): bool
    {
        return $this->fetchTable('Users')->find('active')->all()->count() === 1;
    }

    /**
     * @return bool
     */
    private function isPasswordExpirySettingAlreadyEnabled(): bool
    {
        $setting = $this->passwordExpirySettingsTable->find()->first();

        return !is_null($setting);
    }

    /**
     * @return \Passbolt\PasswordExpiry\Model\Dto\PasswordExpirySettingsDto
     */
    private function getDTO(): PasswordExpirySettingsDto
    {
        return PasswordExpirySettingsDto::createFromArray([
            PasswordExpirySettingsDto::AUTOMATIC_EXPIRY => true,
            PasswordExpirySettingsDto::AUTOMATIC_UPDATE => true,
        ]);
    }
}
