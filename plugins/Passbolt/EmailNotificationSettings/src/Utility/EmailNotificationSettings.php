<?php
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
    const NAMESPACE = 'emailNotification';

    /**
     * The settings.
     *
     * @var array
     */
    private static $settings;

    /**
     * @var ConfigEmailNotificationSettingsSource
     */
    private static $configSettingsSource;

    /**
     * @var DbEmailNotificationSettingsSource
     */
    private static $dbSettingsSource;

    /**
     * @var DefaultEmailNotificationSettingsSource
     */
    private static $defaultSettingsSource;

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
    }

    /**
     * Get the current setting for a given $config
     * Use in order the configuration stored in:
     * 1. database
     * 2. configuration file
     * 3. the defaults in this function
     *
     * @param string $key (optional) Key to lookup. If not provided, return all the settings.
     * @return mixed
     */
    public static function get(string $key = null)
    {
        // Before making any lookups, check if the key is valid
        if ($key && !static::isConfigKeyValid($key)) {
            throw new InternalErrorException(__('The key {0} is not a valid email notification setting.', $key));
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
    protected static function getSettings()
    {
        $settings = static::getSettingsFromConfig();
        $settingsOverriddenByConfig = static::checkDefaultSettingsAreOverriddenByConfig();
        $settings['sources'] = [
            'database' => false,
            'file' => $settingsOverriddenByConfig,
        ];

        if (static::getDbSettingsSource()->isAvailable()) {
            try {
                $dbSettings = static::getSettingsFromDb();
                $settings['sources']['database'] = true;
                $settings = array_replace_recursive($settings, $dbSettings);
            } catch (RecordNotFoundException $exception) {
            }
        }

        return $settings;
    }

    /**
     * Get notification settings from the config/default.php and config/passbolt.php.
     *
     * @return array
     */
    protected static function getSettingsFromConfig()
    {
        return static::sanitizeSettings(static::getConfigSettingsSource()->read());
    }

    /**
     * Sanitize the provided settings and filter out the settings which does not exist
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
     * @return ConfigEmailNotificationSettingsSource
     */
    protected static function getConfigSettingsSource()
    {
        if (!static::$configSettingsSource) {
            static::$configSettingsSource = new ConfigEmailNotificationSettingsSource();
        }

        return static::$configSettingsSource;
    }

    /**
     * Get notification settings saved in the database.
     *
     * @return array $config setting if found and null otherwise
     * @throws RecordNotFoundException If a matching DB config doesn't exist
     * @throws InternalErrorException If the DB config is not valid json string
     */
    protected static function getSettingsFromDb()
    {
        return static::sanitizeSettings(static::getDbSettingsSource()->read());
    }

    /**
     * @return DbEmailNotificationSettingsSource
     */
    protected static function getDbSettingsSource()
    {
        if (!static::$dbSettingsSource) {
            static::$dbSettingsSource = new DbEmailNotificationSettingsSource();
        }

        return static::$dbSettingsSource;
    }

    /**
     * Get notification settings from the default definition.
     * @return array
     */
    protected static function getSettingsFromDefault()
    {
        return static::getDefaultSettingsSource()->read();
    }

    /**
     * @return DefaultEmailNotificationSettingsSource
     */
    protected static function getDefaultSettingsSource()
    {
        if (!static::$defaultSettingsSource) {
            static::$defaultSettingsSource = DefaultEmailNotificationSettingsSource::fromCakeForm(
                new EmailNotificationSettingsForm(EventManager::instance())
            );
        }

        return static::$defaultSettingsSource;
    }

    /**
     * Check that the settings coming from the file are the default.
     *
     * @return bool
     */
    protected static function checkDefaultSettingsAreOverriddenByConfig()
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
     * @param UserAccessControl $accessControl accessControl to use to save the configs
     * @return void
     */
    public static function save(array $configs, UserAccessControl $accessControl)
    {
        // strip all non notification keys
        $configs = EmailNotificationSettingsForm::stripInvalidKeys($configs);

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
    public static function isConfigKeyValid(string $key)
    {
        return Hash::check(static::getSettingsFromDefault(), static::underscoreToDottedFormat($key));
    }

    /**
     * Return a string normalized to the dotted format i.e: "email_settings_xxx" into "email.settings.xxx"
     * @param string $key Key to normalize
     * @return string
     */
    public static function underscoreToDottedFormat(string $key)
    {
        return str_replace('_', '.', $key);
    }
}
