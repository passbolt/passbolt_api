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
 * @since         2.10.0
 */

namespace Passbolt\EmailNotificationSettings\Utility;

use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventManager;
use Cake\Http\Exception\InternalErrorException;
use Cake\Utility\Hash;
use Passbolt\EmailNotificationSettings\Form\EmailNotificationSettingsForm;
use Passbolt\EmailNotificationSettings\Utility\NotificationSettingsSource\ConfigEmailNotificationSettingsSource;
use Passbolt\EmailNotificationSettings\Utility\NotificationSettingsSource\DbEmailNotificationSettingsSource;
use Passbolt\EmailNotificationSettings\Utility\NotificationSettingsSource\DefaultEmailNotificationSettingsSource;
use const ARRAY_FILTER_USE_KEY;

class EmailNotificationSettings
{
    public const NAMESPACE = 'emailNotification';

    private static ?array $settings = null;

    private static ?ConfigEmailNotificationSettingsSource $configSettingsSource = null;

    private static ?DbEmailNotificationSettingsSource $dbSettingsSource = null;

    private static ?DefaultEmailNotificationSettingsSource $defaultSettingsSource = null;

    /**
     * Flush the cache version of the settings.
     *
     * @return void
     */
    public static function flushCache()
    {
        static::$configSettingsSource = null;
        static::$defaultSettingsSource = null;
        static::$settings = null;
        self::$dbSettingsSource = null;
    }

    /**
     * Get the current setting for a given $config
     * Use in order the configuration stored in:
     * 1. database
     * 2. configuration file
     * 3. the defaults in this function
     *
     * @param ?string $key (optional) Key to lookup. If not provided, return all the settings.
     * @return mixed
     */
    public static function get(?string $key = null)
    {
        // Before making any lookups, check if the key is valid
        if ($key && !static::isConfigKeyValid($key)) {
            throw new InternalErrorException("The key $key is not a valid email notification setting.");
        }

        if (is_null(static::$settings)) {
            static::$settings = static::getSettings();
        }

        if (!empty($key)) {
            return Hash::get(static::$settings, $key);
        }

        return static::$settings;
    }

    /**
     * Get the settings.
     *
     * @return array
     */
    protected static function getSettings(): array
    {
        $settings = static::getSettingsFromConfig();
        $settings['sources'] = [
            'database' => false,
            'file' => static::isDefaultSettingsAreOverridden(),
        ];

        try {
            $dbSettings = static::getSettingsFromDb();
            $settings['sources']['database'] = true;
            $settings = array_replace_recursive($settings, $dbSettings);
        } catch (RecordNotFoundException $exception) {
        }

        return $settings;
    }

    /**
     * Get notification settings from the config/default.php and config/passbolt.php.
     *
     * @return array
     */
    protected static function getSettingsFromConfig(): array
    {
        return static::sanitizeSettings(static::getConfigSettingsSource()->read());
    }

    /**
     * Sanitize the provided settings and filter out the settings which does not exist
     *
     * @param array $settings Settings to sanitize
     * @return array
     */
    protected static function sanitizeSettings(array $settings)
    {
        $default = Hash::flatten(static::getSettingsFromDefault());
        $settings = Hash::flatten($settings);

        $filteredSettings = array_filter($settings, function ($key) use ($default) {
            return array_key_exists($key, $default);
        }, ARRAY_FILTER_USE_KEY);

        return Hash::expand($filteredSettings);
    }

    /**
     * @return \Passbolt\EmailNotificationSettings\Utility\NotificationSettingsSource\ConfigEmailNotificationSettingsSource
     */
    protected static function getConfigSettingsSource(): ConfigEmailNotificationSettingsSource
    {
        if (!isset(static::$configSettingsSource)) {
            static::$configSettingsSource = new ConfigEmailNotificationSettingsSource();
        }

        return static::$configSettingsSource;
    }

    /**
     * Get notification settings saved in the database.
     *
     * @return array $config setting if found and null otherwise
     * @throws \Cake\Datasource\Exception\RecordNotFoundException If a matching DB config doesn't exist
     * @throws \Cake\Http\Exception\InternalErrorException If the DB config is not valid json string
     */
    protected static function getSettingsFromDb(): array
    {
        return static::sanitizeSettings(static::getDbSettingsSource()->read());
    }

    /**
     * @return \Passbolt\EmailNotificationSettings\Utility\NotificationSettingsSource\DbEmailNotificationSettingsSource
     */
    protected static function getDbSettingsSource(): DbEmailNotificationSettingsSource
    {
        if (!isset(self::$dbSettingsSource)) {
            self::$dbSettingsSource = new DbEmailNotificationSettingsSource();
        }

        return self::$dbSettingsSource;
    }

    /**
     * Get notification settings from the default definition.
     *
     * @return array
     */
    protected static function getSettingsFromDefault(): array
    {
        return static::getDefaultSettingsSource()->read();
    }

    /**
     * @return \Passbolt\EmailNotificationSettings\Utility\NotificationSettingsSource\DefaultEmailNotificationSettingsSource
     */
    protected static function getDefaultSettingsSource(): DefaultEmailNotificationSettingsSource
    {
        if (!isset(static::$defaultSettingsSource)) {
            static::$defaultSettingsSource = DefaultEmailNotificationSettingsSource::fromCakeForm(
                new EmailNotificationSettingsForm(EventManager::instance())
            );
        }

        return static::$defaultSettingsSource;
    }

    /**
     * Checks that the settings coming from the file are the default or not.
     *
     * @return bool
     */
    protected static function isDefaultSettingsAreOverridden(): bool
    {
        $flatDefaultSettings = Hash::flatten(static::getSettingsFromDefault());

        $flatFileSettings = Hash::flatten(static::getSettingsFromConfig());

        $diff = array_diff_assoc($flatDefaultSettings, $flatFileSettings);

        return count($diff) !== 0;
    }

    /**
     * Save the new config to database
     *
     * @param array $configs The new config to save
     * @param \App\Utility\UserAccessControl $accessControl accessControl to use to save the configs
     * @param bool $force Force saving even if the key is invalid/not yet registered (useful for testing purposes)
     * @return void
     */
    public static function save(array $configs, UserAccessControl $accessControl, bool $force = false): void
    {
        // strip all non notification keys
        if ($force === false) {
            $configs = EmailNotificationSettingsForm::stripInvalidKeys($configs);
        }

        $configs = Hash::expand($configs);

        static::getDbSettingsSource()->write($configs, $accessControl);

        static::flushCache();
    }

    /**
     * Check if given $key is a valid notification setting
     * Supports both underscore and dot delimited names
     *
     * @param string $key The key to check.
     * @return bool
     */
    public static function isConfigKeyValid(string $key): bool
    {
        return Hash::check(static::getSettingsFromDefault(), static::underscoreToDottedFormat($key));
    }

    /**
     * Return a string normalized to the dotted format i.e: "email_settings_xxx" into "email.settings.xxx"
     *
     * @param string $key Key to normalize
     * @return string
     */
    public static function underscoreToDottedFormat(string $key): string
    {
        return str_replace('_', '.', $key);
    }
}
