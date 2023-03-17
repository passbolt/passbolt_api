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

class GetOrgLocaleService extends LocaleService
{
    public const DEFAULT_LOCALE = 'en-UK';

    /**
     * @var string|null
     */
    public static $organisationLocale;

    /**
     * Unset the organization locale stored in run time memory.
     *
     * @return  void
     */
    public static function clearOrganisationLocale(): void
    {
        self::$organisationLocale = null;
    }

    /**
     * Read the organization locale, or return the default locale.
     * The Organization Locale is considered as constant during
     * the whole process.
     *
     * @return string
     */
    public static function getLocale(): string
    {
        // Check if the locale has already been fetched.
        if (!empty(static::$organisationLocale)) {
            return static::$organisationLocale;
        }

        return (new self())->getOrganizationLocale();
    }

    /**
     * Read the organization locale, or return the default locale.
     *
     * @return string
     */
    protected function getOrganizationLocale(): string
    {
        /** @var \App\Model\Table\OrganizationSettingsTable $organizationSettingsTable */
        $organizationSettingsTable = $this->fetchTable('OrganizationSettings');

        try {
            $setting = $organizationSettingsTable->getByProperty(static::SETTING_PROPERTY);
        } catch (\Exception $e) {
            // Do nothing if the table is not found.
            // Since this service is required by the middleware
            // I would rather cover the unlikely case were a request
            // is sent while the schema is not in place yet.
            $setting = null;
        }

        if ($setting instanceof OrganizationSetting) {
            self::$organisationLocale = $setting->get('value');
        } else {
            self::$organisationLocale = self::DEFAULT_LOCALE;
        }

        return self::$organisationLocale;
    }
}
