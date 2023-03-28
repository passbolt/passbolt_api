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
 * @since         3.2.0
 */

namespace Passbolt\Locale\Service;

use Passbolt\AccountSettings\Model\Entity\AccountSetting;

class SetUserLocaleService extends LocaleService
{
    /**
     * Validate and save the locale for a user in her account settings.
     *
     * @param string $userId Logged in user id.
     * @param string|null $locale Locale to save.
     * @return \Passbolt\AccountSettings\Model\Entity\AccountSetting
     * @throws \App\Error\Exception\ValidationException
     */
    public function save(string $userId, ?string $locale = ''): AccountSetting
    {
        $this->assertIsValidLocale($locale);

        /** @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable $accountSettingsTable */
        $accountSettingsTable = $this->fetchTable('Passbolt/AccountSettings.AccountSettings');

        return $accountSettingsTable->createOrUpdateSetting(
            $userId,
            static::SETTING_PROPERTY,
            $locale
        );
    }
}
