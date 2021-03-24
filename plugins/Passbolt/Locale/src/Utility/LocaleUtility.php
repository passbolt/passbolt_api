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
namespace Passbolt\Locale\Utility;

use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

class LocaleUtility
{
    public const SETTING_PROPERTY = 'locale';

    /**
     * @var string|null
     */
    public static $organisationLocale;

    /**
     * Read in configuration the locales available
     *
     * @return array
     */
    public static function getAvailableLocales(): array
    {
        return Configure::readOrFail('passbolt.plugins.locale.options');
    }

    /**
     * Detect if a local is found in the array keys of the available locales.
     * Both underscored and dashed notation are considered valid.
     *
     * @param string|null $locale Locale to validate
     * @return bool
     */
    public static function localeIsValid(?string $locale = ''): bool
    {
        return in_array(self::dasherizeLocale($locale), array_keys(self::getAvailableLocales()));
    }

    /**
     * Underscore and set the locale.
     *
     * @param string $locale Locale is set.
     * @return void
     */
    public static function setLocale(string $locale): void
    {
        $locale = self::underscoreLocale($locale);
        I18n::setLocale($locale);
    }

    /**
     * Set the locale if valid.
     *
     * @param string|null $locale The locale to set.
     * @return void
     */
    public static function setLocaleIfValid(?string $locale = ''): void
    {
        if (empty($locale)) {
            return;
        }
        if (self::localeIsValid($locale)) {
            self::setLocale($locale);
        } else {
            Log::warning(__d('log', 'The locale {0} is not valid or not supported.', $locale));
        }
    }

    /**
     * Read the organization locale, or return the default locale.
     * The Organization Locale is considered as constant during
     * the whole process.
     *
     * @return string
     */
    public static function getOrganizationLocale(): string
    {
        // Check if the locale has already been fetched.
        if (!empty(self::$organisationLocale)) {
            return self::$organisationLocale;
        }

        try {
            $setting = TableRegistry::getTableLocator()
                ->get('OrganizationSettings')
                ->getByProperty(self::SETTING_PROPERTY);
        } catch (\Exception $e) {
            // Do nothing if the table is not found.
            $setting = null;
        }

        if ($setting) {
            self::$organisationLocale = $setting->get('value');
        } else {
            self::$organisationLocale = LocaleUtility::dasherizeLocale(I18n::getDefaultLocale());
        }

        return self::$organisationLocale;
    }

    /**
     * Unset the organization locale stored in living memory.
     *
     * @return  void
     */
    public static function clearOrganisationLocale(): void
    {
        self::$organisationLocale = null;
    }

    /**
     * @param string|null $locale Locale to dasherize
     * @return string
     */
    public static function dasherizeLocale(?string $locale = ''): string
    {
        return str_replace('_', '-', $locale);
    }

    /**
     * @param string|null $locale Locale to underscore
     * @return string
     */
    public static function underscoreLocale(?string $locale = ''): string
    {
        return str_replace('-', '_', $locale);
    }
}
