<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.6.0
 */
namespace Passbolt\DirectorySync\Utility;

use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class DirectoryOrgSettings
{
    /**
     * The organisation settings property name.
     * @var string
     */
    const ORG_SETTINGS_PROPERTY = 'directorySync';

    /**
     * @var array
     */
    protected $settings;

    /**
     * DirectoryOrgSettings constructor.
     *
     * @param array $settings settings
     */
    public function __construct(array $settings = [])
    {
        $this->OrganizationSetting = TableRegistry::getTableLocator()->get('OrganizationSettings');

        // If settings is not empty, we merge with the plugin default settings.
        // It is important to leave settings empty if no settings are set. This permits
        // to check when no settings have been set at all.
        if (!empty($settings)) {
            $pluginDefaultSettings = self::getDefaultSettings();
            $settings = Hash::merge($pluginDefaultSettings, $settings);
        }
        $this->settings = $settings;
    }

    /**
     * Get Directory Organization Settings
     *
     * @return DirectoryOrgSettings
     */
    public static function get()
    {
        try {
            $settings = self::loadSettingsFromDatabase();
        } catch (RecordNotFoundException $e) {
            $settings = self::loadSettingsFromFile();
        }

        return new DirectoryOrgSettings($settings);
    }

    /**
     * Load the directory settings from the database
     *
     * @return array
     */
    private static function loadSettingsFromDatabase()
    {
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $data = $OrganizationSettings->getFirstSettingOrFail(self::ORG_SETTINGS_PROPERTY);
        $settings = json_decode($data->value, true);
        $password = Hash::get($settings, 'ldap.domains.org_domain.password', '');
        if (!empty($password)) {
            $settings = Hash::insert($settings, 'ldap.domains.org_domain.password', self::decrypt($password));
        }

        return $settings;
    }

    /**
     * Load the directory settings from the config file
     *
     * @return array
     */
    private static function loadSettingsFromFile()
    {
        $path = CONFIG . DS . 'ldap.php';
        if (!\file_exists($path)) {
            return [];
        }
        $data = require($path);

        return Hash::get($data, 'passbolt.plugins.directorySync', []);
    }

    /**
     * Load the directory settings from the config file
     *
     * @return array
     */
    private static function getDefaultSettings()
    {
        $path = PLUGINS . 'Passbolt' . DS . 'DirectorySync' . DS . 'config' . DS . 'config.php';
        if (!\file_exists($path)) {
            return [];
        }
        $data = require($path);

        return Hash::get($data, 'passbolt.plugins.directorySync', []);
    }

    /**
     * Disable the ldap integration.
     *
     * @param UserAccessControl $uac user access control
     * @return void
     */
    public static function disable($uac)
    {
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $OrganizationSettings->deleteSetting(self::ORG_SETTINGS_PROPERTY, $uac);
    }

    /**
     * Check that the directory sync is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return !empty($this->settings);
    }

    /**
     * Retrieve custom object class.
     *
     * @param string $objectType The type of object to retrieve the class for
     * @return string
     */
    public function getObjectClass(string $objectType)
    {
        return Hash::get($this->settings, "{$objectType}ObjectClass");
    }

    /**
     * Retrieve custom object path.
     *
     * @param string $objectType The type of object to retrieve the path for
     * @return string
     */
    public function getObjectPath(string $objectType)
    {
        return Hash::get($this->settings, "{$objectType}Path");
    }

    /**
     * Get the default user.
     *
     * @return string
     */
    public function getDefaultUser()
    {
        return Hash::get($this->settings, "defaultUser");
    }

    /**
     * Get the default group admin user.
     *
     * @return string
     */
    public function getDefaultGroupAdminUser()
    {
        return Hash::get($this->settings, "defaultGroupAdminUser");
    }

    /**
     * Get the default parent group for users.
     *
     * @return string
     */
    public function getUsersParentGroup()
    {
        return Hash::get($this->settings, "usersParentGroup");
    }

    /**
     * Get the default parent group for groups.
     *
     * @return string
     */
    public function getGroupsParentGroup()
    {
        return Hash::get($this->settings, "groupsParentGroup");
    }

    /**
     * Get the value of enabledUsersOnly.
     *
     * @return bool|string
     */
    public function getEnabledUsersOnly()
    {
        return Hash::get($this->settings, "enabledUsersOnly");
    }

    /**
     * Get fields mapping.
     * @param string $type directory type
     *
     * @return bool|string
     */
    public function getFieldsMapping(string $type = null)
    {
        if ($type === null) {
            return Hash::get($this->settings, "fieldsMapping");
        }

        return Hash::get($this->settings, "fieldsMapping.$type");
    }

    /**
     * Get the ldap configuration
     *
     * @return array
     */
    public function getLdapSettings()
    {
        return Hash::get($this->settings, "ldap");
    }

    /**
     * Check that a sync operation is enabled for a given object type
     *
     * @param string $objectType The type of object to retrieve the information for
     * @param string $operation The type of operation
     * @return bool
     */
    public function isSyncOperationEnabled($objectType, $operation)
    {
        return Hash::get($this->settings, "jobs.$objectType.$operation", false);
    }

    /**
     * Get useEmailPrefixSuffix.
     *
     * @return mixed
     */
    public function getUseEmailPrefixSuffix()
    {
        return Hash::get($this->settings, "useEmailPrefixSuffix");
    }

    /**
     * Get EmailPrefix.
     *
     * @return mixed
     */
    public function getEmailPrefix()
    {
        return Hash::get($this->settings, "emailPrefix");
    }

    /**
     * Get EmailSuffix.
     *
     * @return mixed
     */
    public function getEmailSuffix()
    {
        return Hash::get($this->settings, "emailSuffix");
    }

    /**
     * Get UserCustomFilters
     * @return mixed (should be callable)
     */
    public function getUserCustomFilters()
    {
        return Hash::get($this->settings, "userCustomFilters");
    }

    /**
     * Get GroupCustomFilters
     * @return mixed (should be callable)
     */
    public function getGroupCustomFilters()
    {
        return Hash::get($this->settings, "groupCustomFilters");
    }

    /**
     * Get the settings.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->settings;
    }

    /**
     * Update the settings
     *
     * @param array $settings The new settings
     * @return void
     */
    public function set($settings)
    {
        $this->settings = $settings;
    }

    /**
     * Persist the settings in database.
     *
     * @param UserAccessControl $uac user access control
     * @return void
     */
    public function save(UserAccessControl $uac)
    {
        $settings = new \ArrayObject($this->settings);
        $settings = $settings->getArrayCopy();
        $password = Hash::get($settings, 'ldap.domains.org_domain.password', '');
        if (!empty($password)) {
            $settings = Hash::insert($settings, 'ldap.domains.org_domain.password', self::encrypt($password));
        }
        $data = json_encode($settings);
        $this->OrganizationSetting->createOrUpdateSetting(self::ORG_SETTINGS_PROPERTY, $data, $uac);
    }

    /**
     * Encrypt a data for the server gpg key.
     *
     * @param string $data The message to encrypt
     * @return string
     */
    protected static function encrypt(string $data)
    {
        $gpgConfig = Configure::read('passbolt.gpg');
        $keyid = $gpgConfig['serverKey']['fingerprint'];
        $passphrase = $gpgConfig['serverKey']['passphrase'];
        $gpg = OpenPGPBackendFactory::get();
        $gpg->setSignKeyFromFingerprint($keyid, $passphrase);
        $gpg->setEncryptKeyFromFingerprint($keyid);

        return $gpg->encrypt($data, true);
    }

    /**
     * Decrypt the organization settings
     *
     * @param string $data The message to decrypt
     * @return string
     */
    protected static function decrypt(string $data)
    {
        $gpgConfig = Configure::read('passbolt.gpg');
        $keyid = $gpgConfig['serverKey']['fingerprint'];
        $passphrase = $gpgConfig['serverKey']['passphrase'];
        $gpg = OpenPGPBackendFactory::get();
        $gpg->setDecryptKeyFromFingerprint($keyid, $passphrase);

        return $gpg->decrypt($data);
    }
}
