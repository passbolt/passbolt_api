<?php
declare(strict_types=1);

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
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\DirectorySyncPlugin;
use Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryEntry;

class DirectoryOrgSettings
{
    use LocatorAwareTrait;

    /**
     * The organisation settings property name.
     *
     * @var string
     */
    public const ORG_SETTINGS_PROPERTY = 'directorySync';

    /**
     * Select server constants in multi-servers config
     */
    public const SERVER_SELECTION_ORDER = 'order';
    public const SERVER_SELECTION_RANDOM = 'random';
    /**
     * Bind formats
     */
    public const BIND_FORMATS = [
        DirectoryInterface::TYPE_AD => '%username%@%domainname%',
        DirectoryInterface::TYPE_OPENLDAP => '%username%',
        DirectoryInterface::TYPE_FREEIPA => '%username%',
    ];
    /**
     * @var array
     */
    protected $settings;

    /**
     * @var \App\Model\Table\OrganizationSettingsTable
     */
    public $OrganizationSettings;

    /**
     * DirectoryOrgSettings constructor.
     *
     * @param array|null $settings settings
     */
    public function __construct(?array $settings = [])
    {
        /** @phpstan-ignore-next-line */
        $this->OrganizationSettings = $this->fetchTable('OrganizationSettings');

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
     * @return \Passbolt\DirectorySync\Utility\DirectoryOrgSettings
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
        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
        $OrganizationSettings = TableRegistry::getTableLocator()->get('OrganizationSettings');
        $data = $OrganizationSettings->getFirstSettingOrFail(self::ORG_SETTINGS_PROPERTY);
        $settings = json_decode($data->value, true);
        $domains = Hash::get($settings, 'ldap.domains', []);
        foreach ($domains as $domain => $properties) {
            $password = Hash::get($settings, "ldap.domains.$domain.password", '');
            if (!empty($password)) {
                $settings = Hash::insert($settings, "ldap.domains.$domain.password", self::decrypt($password));
            }
        }

        if (!empty($settings)) {
            $settings['source'] = 'db';
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
        $data = require $path;

        $settings = Hash::get($data, 'passbolt.plugins.directorySync', []);
        $settings['source'] = 'file';

        return $settings;
    }

    /**
     * Load the directory settings from the config file
     *
     * @return array
     */
    public static function getDefaultSettings()
    {
        $path = DirectorySyncPlugin::PLUGIN_CONFIG_PATH . 'config.php';
        if (!\file_exists($path)) {
            return [];
        }
        $data = require $path;

        return Hash::get($data, 'passbolt.plugins.directorySync', []);
    }

    /**
     * Disable the ldap integration.
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @return void
     */
    public static function disable($uac)
    {
        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettings */
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
        return !empty($this->settings) && !empty($this->settings['enabled']);
    }

    /**
     * Retrieve custom object class.
     *
     * @param string $objectType The type of object to retrieve the class for
     * @return string|null
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
        return Hash::get($this->settings, 'defaultUser');
    }

    /**
     * Get the default group admin user.
     *
     * @return string
     */
    public function getDefaultGroupAdminUser()
    {
        return Hash::get($this->settings, 'defaultGroupAdminUser');
    }

    /**
     * Get the default parent group for users.
     *
     * @return string
     */
    public function getUsersParentGroup()
    {
        return Hash::get($this->settings, 'usersParentGroup');
    }

    /**
     * Get the default parent group for groups.
     *
     * @return string
     */
    public function getGroupsParentGroup()
    {
        return Hash::get($this->settings, 'groupsParentGroup');
    }

    /**
     * Get the value of enabledUsersOnly.
     *
     * @return bool|string|null
     */
    public function getEnabledUsersOnly()
    {
        return Hash::get($this->settings, 'enabledUsersOnly');
    }

    /**
     * Get fields mapping.
     *
     * @param string $type directory type
     * @return array|null
     */
    public function getFieldsMapping(?string $type = null): ?array
    {
        if ($type === null) {
            return Hash::get($this->settings, 'fieldsMapping');
        }

        return Hash::get($this->settings, "fieldsMapping.$type");
    }

    /**
     * Get fields fallback.
     *
     * @param string|null $type Directory type
     * @return array|null
     */
    public function getFieldFallbacks(?string $type = null): ?array
    {
        if ($type === null) {
            return Hash::get($this->settings, 'fieldFallbacks');
        }

        return Hash::get($this->settings, "fieldFallbacks.{$type}");
    }

    /**
     * Get the ldap configuration
     *
     * @return array
     */
    public function getLdapSettings(): array
    {
        $ldapSettings = Hash::get($this->settings, 'ldap.domains');

        return $ldapSettings;
    }

    /**
     * Get default domain if set or first domain from config
     *
     * @return array|\ArrayAccess|mixed
     */
    public function getDefaultDomain()
    {
        return Hash::get(
            $this->settings,
            'ldap.default_domain',
            collection(array_keys($this->settings['ldap']['domains']))->first()
        );
    }

    /**
     * Check that a sync operation is enabled for a given object type
     *
     * @param string $objectType The type of object to retrieve the information for
     * @param string $operation The type of operation
     * @return bool
     */
    public function isSyncOperationEnabled(string $objectType, string $operation): bool
    {
        $objectType = strtolower($objectType);

        return Hash::get($this->settings, "jobs.$objectType.$operation", false);
    }

    /**
     * Get useEmailPrefixSuffix.
     *
     * @return mixed
     */
    public function getUseEmailPrefixSuffix()
    {
        return Hash::get($this->settings, 'useEmailPrefixSuffix');
    }

    /**
     * Get EmailPrefix.
     *
     * @return mixed
     */
    public function getEmailPrefix()
    {
        return Hash::get($this->settings, 'emailPrefix');
    }

    /**
     * Get EmailSuffix.
     *
     * @return mixed
     */
    public function getEmailSuffix()
    {
        return Hash::get($this->settings, 'emailSuffix');
    }

    /**
     * Get UserCustomFilters
     *
     * @return mixed (should be string)
     */
    public function getUserCustomFilters()
    {
        return Hash::get($this->settings, 'userCustomFilters');
    }

    /**
     * Get GroupCustomFilters
     *
     * @return mixed (should be string)
     */
    public function getGroupCustomFilters()
    {
        return Hash::get($this->settings, 'groupCustomFilters');
    }

    /**
     * Get Source. (file or db).
     *
     * @return mixed
     */
    public function getSource()
    {
        return Hash::get($this->settings, 'source');
    }

    /**
     * Get the settings.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->settings;
    }

    /**
     * Update the settings
     *
     * @param array $settings The new settings
     * @return void
     */
    public function set($settings): void
    {
        $this->settings = $settings;
    }

    /**
     * Persist the settings in database.
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @return void
     */
    public function save(UserAccessControl $uac): void
    {
        $settings = new \ArrayObject($this->settings);
        $settings = $settings->getArrayCopy();
        $domains = Hash::get($settings, 'ldap.domains', []);
        foreach ($domains as $domain => $properties) {
            $password = Hash::get($settings, "ldap.domains.$domain.password", '');
            if (!empty($password)) {
                $settings = Hash::insert($settings, "ldap.domains.$domain.password", self::encrypt($password));
            }
        }
        $data = json_encode($settings);
        $this->OrganizationSettings->createOrUpdateSetting(self::ORG_SETTINGS_PROPERTY, $data, $uac);
    }

    /**
     * Encrypt a data for the server OpenPGP key.
     *
     * @param string $data The message to encrypt
     * @return string
     */
    protected static function encrypt(string $data): string
    {
        $gpgConfig = Configure::read('passbolt.gpg');
        $fingerprint = $gpgConfig['serverKey']['fingerprint'];
        $passphrase = $gpgConfig['serverKey']['passphrase'];
        $gpg = OpenPGPBackendFactory::get();

        try {
            $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
            $gpg->setEncryptKeyFromFingerprint($fingerprint);
        } catch (\Exception $exception) {
            try {
                // Try again by importing key into keyring
                $gpg->importServerKeyInKeyring();
                $gpg->setSignKeyFromFingerprint($fingerprint, $passphrase);
                $gpg->setEncryptKeyFromFingerprint($fingerprint);
            } catch (\Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to decrypt.') . ' ';
                $msg .= $exception->getMessage();

                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        return $gpg->encrypt($data, true);
    }

    /**
     * Decrypt the organization settings
     *
     * @param string $data The message to decrypt
     * @return string
     */
    protected static function decrypt(string $data): string
    {
        $gpgConfig = Configure::read('passbolt.gpg');
        $keyid = $gpgConfig['serverKey']['fingerprint'];
        $passphrase = $gpgConfig['serverKey']['passphrase'];
        $gpg = OpenPGPBackendFactory::get();

        try {
            $gpg->setDecryptKeyFromFingerprint($keyid, $passphrase);
        } catch (\Exception $exception) {
            try {
                $gpg->importServerKeyInKeyring();
                $gpg->setDecryptKeyFromFingerprint($keyid, $passphrase);
            } catch (\Exception $exception) {
                $msg = __('The OpenPGP server key defined in the config cannot be used to decrypt.') . ' ';
                $msg .= $exception->getMessage();
                throw new InternalErrorException($msg, 500, $exception);
            }
        }

        return $gpg->decrypt($data);
    }

    /**
     * Format username using bind_format setting
     *
     * @param string $username The username to replace
     * @param string $domain The domain to replace
     * @param string $bindFormat The string with placeholders
     * @return string
     */
    public static function formatUsername(string $username, string $domain, string $bindFormat): string
    {
        if (DirectoryEntry::isValidDn($username)) {
            return $username;
        }
        $params = [
            '/%username%/i',
            '/%domainname%/i',
        ];
        $replacements = [
            $username,
            $domain,
        ];

        return preg_replace($params, $replacements, $bindFormat);
    }

    /**
     * Get password for domain
     *
     * @param string $domain Domain name
     * @return string
     */
    public function getPassword(string $domain = 'org_domain'): string
    {
        return Hash::get($this->settings, "ldap.domains.$domain.password", '');
    }
}
