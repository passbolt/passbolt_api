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

use App\Model\Table\OrganizationSettingsTable;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\EmailNotificationSettings\Form\EmailNotificationSettingsForm;

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
     * Default settings.
     *
     * @var array
     */
    private static $defaultSettings = [
        'show' => [
            'comment' => true,
            'description' => true,
            'secret' => true,
            'uri' => true,
            'username' => true,
        ],
        'send' => [
            'comment' => [
                'add' => true,
            ],
            'password' => [
                'create' => true,
                'share' => true,
                'update' => true,
                'delete' => true,
            ],
            'user' => [
                'create' => true,
                'recover' => true,
            ],
            'group' => [
                'delete' => true,
                'user' => [
                    'add' => true,
                    'delete' => true,
                    'update' => true,
                ],
                'manager' => [
                    'update' => true,
                ],
            ],
        ]
    ];

    /**
     * Flush the cache version of the settings.
     *
     * @return void
     */
    public static function flushCache()
    {
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
        $settings = static::getSettingsFromFile();
        $settingsOverridenByfile = static::checkSettingsAreOverriddenByFile();
        $settings['sources'] = [
            'database' => false,
            'file' => $settingsOverridenByfile
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
     * Get settings loaded from config/default.php and config/passbolt.php in the CakePHP config.
     *
     * @return array $config setting if found and null otherwise
     */
    protected static function getSettingsFromFile()
    {
        $fileConfigs = Configure::read('passbolt.email');

        if (isset($fileConfigs['validate'])) {
            unset($fileConfigs['validate']);
        }

        return $fileConfigs;
    }

    /**
     * Get config setting from the DB
     *
     * @return bool|null $config setting if found and null otherwise
     * @throws RecordNotFoundException If a matching DB config doesn't exist
     * @throws InternalErrorException If the DB config is not valid json string
     */
    protected static function getSettingsFromDb()
    {
        /** @var OrganizationSettingsTable $organizationSettings */
        $organizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $notificationSettingFromDb = $organizationSettings->getFirstSettingOrFail(static::NAMESPACE);
        $settings = \json_decode($notificationSettingFromDb->get('value'), true);

        // look for invalid structured string
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new InternalErrorException('The Email Notification Settings configs are invalid');
        }

        return $settings;
    }

    /**
     * Check that the settings coming from the file are the default.
     *
     * @return bool
     */
    protected static function checkSettingsAreOverriddenByFile()
    {
        $flatFileSettings = Hash::flatten(static::getSettingsFromFile());
        $flatDefaultSettings = Hash::flatten(static::$defaultSettings);
        $diff = array_diff_assoc($flatFileSettings, $flatDefaultSettings);

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

        $data = json_encode($configs);

        // look for invalid structured string
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new InternalErrorException('The Email Notification Settings configs are invalid');
        }

        /** @var OrganizationSettingsTable $organizationSettings */
        $organizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $organizationSettings->createOrUpdateSetting(EmailNotificationSettings::NAMESPACE, $data, $accessControl);

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
        // for lookups from a form, transform the $key to dot delimited format
        $key = str_replace('_', '.', $key);

        return Hash::check(static::getSettingsFromFile(), $key);
    }
}
