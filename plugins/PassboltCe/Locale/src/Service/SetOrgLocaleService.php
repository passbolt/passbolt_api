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

use App\Model\Entity\OrganizationSetting;
use App\Utility\UserAccessControl;

class SetOrgLocaleService extends LocaleService
{
    /**
     * Validate and save the locale for a user in her account settings.
     *
     * @param \App\Utility\UserAccessControl $admin Logged in admin.
     * @param string|null $locale Locale to save.
     * @return \App\Model\Entity\OrganizationSetting
     * @throws \App\Error\Exception\ValidationException
     */
    public function save(UserAccessControl $admin, ?string $locale = ''): OrganizationSetting
    {
        $this->assertIsValidLocale($locale);

        /** @var \App\Model\Table\OrganizationSettingsTable $organizationSettingsTable */
        $organizationSettingsTable = $this->fetchTable('OrganizationSettings');

        return $organizationSettingsTable->createOrUpdateSetting(
            static::SETTING_PROPERTY,
            $locale,
            $admin
        );
    }
}
