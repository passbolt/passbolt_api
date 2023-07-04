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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Utility;

use Cake\Http\Exception\NotImplementedException;
use Cake\Utility\Hash;
use LdapRecord\Configuration\DomainConfiguration;
use LdapRecord\Connection;
use LdapRecord\Container;
use LdapRecord\Ldap;
use LdapRecord\LdapRecordException;
use LdapRecord\Models\Collection;
use LdapRecord\Query\Builder;
use LdapRecord\Query\Filter\Parser;
use LdapRecord\Query\Filter\ParserException;
use Passbolt\DirectorySync\Form\LdapConfigurationForm;
use Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults;

/**
 * Directory factory class
 *
 * @package App\Utility
 */
class LdapDirectory implements DirectoryInterface
{
    /**
     * @var \Passbolt\DirectorySync\Utility\DirectoryOrgSettings
     */
    private $directorySettings;

    /**
     * @var mixed
     */
    private $mappingRules;

    /**
     * @var string[]|null
     */
    private $directoryTypes;

    /**
     * @var string|null
     */
    private $defaultDomain;

    /**
     * @var \Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults
     */
    private $directoryResults;

    /**
     * LdapDirectory constructor.
     *
     * @param \Passbolt\DirectorySync\Utility\DirectoryOrgSettings $settings The directory settings
     * @param bool $initializeContainer Whether or not initialize container with LDAP connection
     * @throws \Exception if connection cannot be established
     */
    public function __construct(DirectoryOrgSettings $settings, bool $initializeContainer = true)
    {
        $this->directorySettings = $settings;

        $ldapSettings = $this->directorySettings->getLdapSettings();
        $this->defaultDomain = $this->directorySettings->getDefaultDomain();
        if ($initializeContainer) {
            $this->initializeContainer($ldapSettings);
        }
    }

    /**
     * Set directory type
     *
     * @param string $domain Domain to be applied
     * @param string $directoryType Directory Type (ad, openldap, etc)
     * @return void
     */
    public function setDirectoryType(string $domain, string $directoryType = DirectoryInterface::TYPE_AD): void
    {
        $this->directoryTypes[$domain] = $directoryType;
    }

    /**
     * Initializes container with default connection
     *
     * @param array $ldapSettings LDAP server (connection) settings. Note that this function will alter the settings.
     * @return void
     * @throws \Exception If directory type setting is wrong and mapping cannot be initialized.
     */
    public function initializeContainer(array $ldapSettings): void
    {
        DomainConfiguration::extend('domain');
        DomainConfiguration::extend('domain_name');
        DomainConfiguration::extend('ldap_type');
        DomainConfiguration::extend('use_sasl', false);
        DomainConfiguration::extend('sasl_options', []);
        DomainConfiguration::extend('lazy_bind', false);
        foreach ($ldapSettings as $domain => $settings) {
            $connectionSettings = $this->prepareSettings($domain, $settings);
            $connection = $this->getConnection($connectionSettings);
            Container::addConnection($connection, $domain);
        }
        Container::setDefaultConnection($this->defaultDomain);
        $this->initializeMapping();
    }

    /**
     * Initialize mapping and results
     *
     * @return void
     * @throws \Exception
     */
    public function initializeMapping(): void
    {
        $this->mappingRules = $this->getMappingRules();
        $this->directoryResults = new DirectoryResults($this->mappingRules, $this->directorySettings);
    }

    /**
     * @param array $ldapSettings LDAP Settings
     * @return \LdapRecord\Connection
     * @throws \RuntimeException If the connection could not be established.
     */
    protected function getConnection(array $ldapSettings): Connection
    {
        $ldap = new Ldap();
        if (Hash::get($ldapSettings, 'use_sasl', false)) {
            $ldap = new LdapSasl(Hash::get($ldapSettings, 'sasl_options', []));
        }
        $connection = new Connection($ldapSettings, $ldap);
        if (Hash::get($ldapSettings, 'lazy_bind', false) !== true) {
            try {
                $connection->connect();
            } catch (LdapRecordException $lre) {
                $error = $lre->getDetailedError();
                $errorMessage = $lre->getMessage();
                if ($error) {
                    $errorMessage = $error->getErrorMessage();
                }
                throw new \RuntimeException($errorMessage, 0, $lre);
            }
        }

        return $connection;
    }

    /**
     * Get DN Full Path as per configuration.
     *
     * @param string $ldapObjectType ldap object type (user or group)
     * @return string
     */
    public function getDNFullPath(string $ldapObjectType): string
    {
        $paths = [];
        $paths['additionalPath'] = $this->directorySettings->getObjectPath($ldapObjectType);
        $paths['baseDN'] = Container::getDefaultConnection()->getConfiguration()->get('base_dn');

        return ltrim(implode(',', $paths), ',');
    }

    /**
     * Get directory type name.
     *
     * @param ?string $domain Domain to get directory type name
     * @return string
     * @throws \InvalidArgumentException Invalid directory type for the given domain.
     */
    public function getDirectoryTypeName(?string $domain = null): string
    {
        $directoryTypeName = null;
        switch ($this->getDirectoryType($domain)) {
            case self::TYPE_OPENLDAP:
                $directoryTypeName = DirectoryInterface::TYPE_NAME_OPENLDAP;
                break;
            case self::TYPE_AD:
                $directoryTypeName = DirectoryInterface::TYPE_NAME_AD;
                break;
            case self::TYPE_FREEIPA:
                $directoryTypeName = DirectoryInterface::TYPE_NAME_FREEIPA;
                break;
            default:
                throw new \InvalidArgumentException(__('Invalid directory type for domain: {0}', $domain));
        }

        return $directoryTypeName;
    }

    /**
     * Get directory type.
     *
     * @param ?string $domain Domain to get directory type
     * @return string
     * @throws \InvalidArgumentException Directory type could not be found for domain
     */
    public function getDirectoryType(?string $domain = null): string
    {
        if (!$domain) {
            $domain = Container::getDefaultConnection()->getConfiguration()->get('domain') ?? $this->defaultDomain;
        }

        if (!isset($this->directoryTypes[$domain])) {
            throw new \InvalidArgumentException(__('Directory type could not be found for domain: {0}', $domain));
        }

        return $this->directoryTypes[$domain];
    }

    /**
     * Get mapping rules.
     *
     * @return array|null
     * @throws \Exception If the directory type is not supported.
     */
    public function getMappingRules(): ?array
    {
        $type = $this->getDirectoryType();
        if ($type !== static::TYPE_AD && $type !== static::TYPE_OPENLDAP) {
            throw new \Exception(__(
                'The directory type should be one of the following: {0}.',
                implode(', ', LdapConfigurationForm::SUPPORTED_DIRECTORY_TYPE)
            ));
        }
        $mapping = $this->directorySettings->getFieldsMapping();

        return $mapping;
    }

    /**
     * Set directory results.
     *
     * @param \Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults $results results
     * @return void
     */
    public function setDirectoryResults(DirectoryResults $results): void
    {
        $this->directoryResults = $results;
    }

    /**
     * Get directory results.
     *
     * @return \Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults
     */
    public function getDirectoryResults(): DirectoryResults
    {
        return $this->directoryResults;
    }

    /**
     * Get directory results with filtered applied (as per filters defined in the config).
     *
     * @return \Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults directory results
     * @throws \Exception
     */
    public function getFilteredDirectoryResults(): DirectoryResults
    {
        $directoryResults = $this->fetchDirectoryData();
        $users = $directoryResults->getUsersAsArray();
        $groups = $directoryResults->getGroupsAsArray();

        //TODO LDAP-PROJECT Check if this can be done at query - Next Version
        $usersFromGroup = $this->directorySettings->getUsersParentGroup();
        if (!empty($usersFromGroup)) {
            $filteredUsers = $directoryResults
                ->getRecursivelyFromParentGroup(DirectoryInterface::ENTRY_TYPE_USER, $usersFromGroup);
            $users = $filteredUsers->getUsersAsArray();
        }

        $groupsFromGroup = $this->directorySettings->getGroupsParentGroup();
        if (!empty($groupsFromGroup)) {
            $filteredGroups = $directoryResults
                ->getRecursivelyFromParentGroup(DirectoryInterface::ENTRY_TYPE_GROUP, $groupsFromGroup);
            $groups = $filteredGroups->getGroupsAsArray();
        }

        $directoryResults = new DirectoryResults($this->mappingRules, $this->directorySettings);
        $directoryResults->initializeWithEntries($users, $groups);

        return $directoryResults;
    }

    /**
     * Fetch and initialize all users that are in the provided DN.
     *
     * @return \LdapRecord\Query\Builder query corresponding to the list of users.
     * @throws \InvalidArgumentException If an error occurred while parsing the enabledUsersOnly filter
     */
    private function _fetchAndInitializeUsersQuery(): Builder
    {
        $usersQuery = $this->_fetchAndInitializeQuery(self::ENTRY_TYPE_USER);
        $enabledUsersOnly = $this->directorySettings->getEnabledUsersOnly();
        $directoryType = $this->getDirectoryType();
        if ($directoryType === DirectoryInterface::TYPE_AD && $enabledUsersOnly) {
            try {
                $filter = Parser::parse(DirectoryInterface::AD_ENABLED_USERS_FILTER);
                $usersQuery->rawFilter(Parser::assemble($filter));
            } catch (ParserException $pe) {
                throw new \InvalidArgumentException(
                    'An error has occurred parsing enabledUsersOnly filter: ' . $pe->getMessage(),
                    $pe->getCode(),
                    $pe
                );
            }
        }

        return $this->_customizeUsersQuery($usersQuery);
    }

    /**
     * Set specific objectClass for LDAP object and return query
     *
     * @param string $entryType Entry type (user, group)
     * @return \LdapRecord\Query\Model\Builder
     * @throws \RuntimeException If the entryType corresponding Ldap object class could not be found.
     * @throws \LdapRecord\Configuration\ConfigurationException When domain config key does not exist
     */
    protected function getQuery(string $entryType): Builder
    {
        $domain = Container::getDefaultConnection()->getConfiguration()->get('domain');
        $directoryType = $this->getDirectoryType($domain);
        $directoryTypeName = $this->getDirectoryTypeName($domain);
        $className = "\LdapRecord\Models\\$directoryTypeName\\" . ucfirst($entryType);
        $objectClass = $this->directorySettings->getObjectClass($entryType);
        if (!class_exists($className)) {
            throw new \RuntimeException(__('LDAP Object class could not be found: {0}', $className));
        }
        /**
         * Every LdapRecord model class has default objectClasses declared.
         * We override the property if objectClass setting exist
         * `top` is always needed because is the root node of any object.
         **/
        if ($directoryType !== DirectoryInterface::TYPE_AD && $objectClass) {
            $className::$objectClasses = ['top', $objectClass];
        }

        return $className::query();
    }

    /**
     * Get query from LDAP Object, ensure created and modified are selected and set baseDn for query
     *
     * @param string $entryType Entry type (user, group)
     * @return \LdapRecord\Query\Model\Builder
     */
    protected function _fetchAndInitializeQuery(string $entryType): Builder
    {
        // Get fields that we are interested in from field mappings
        // This is reduce the response payload from LDAP server to prevent exceeding memory,
        // and improve query performance.
        $fields = array_values($this->mappingRules[$this->getDirectoryType()][$entryType]);

        $query = $this->getQuery($entryType)->select($fields);

        return $query->setBaseDn($this->getDNFullPath($entryType));
    }

    /**
     * Fetch and initialize all groups that are in the provided DN.
     *
     * @return \LdapRecord\Query\Builder query corresponding to groups entry.
     * @throws \Exception
     */
    private function _fetchAndInitializeGroupsQuery(): Builder
    {
        $groupsQuery = $this->_fetchAndInitializeQuery(self::ENTRY_TYPE_GROUP);

        return $this->_customizeGroupsQuery($groupsQuery);
    }

    /**
     * Fetch directory data and cache it.
     *
     * @return \Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults
     * @throws \Exception
     */
    public function fetchDirectoryData(): DirectoryResults
    {
        // Fetch directory data for all domains.
        $domains = array_keys(Container::allConnections());
        $ldapGroups = new Collection();
        $ldapUsers = new Collection();

        if ($this->directoryResults->isEmpty()) {
            foreach ($domains as $domain) {
                Container::setDefaultConnection($domain);
                $directoryType = $this->getDirectoryType($domain);
                $tmpGroups = $this->_fetchAndInitializeGroupsQuery()->paginate();
                foreach ($tmpGroups as $tmpGroup) {
                    $tmpGroup->addAttributeValue('objectType', DirectoryInterface::ENTRY_TYPE_GROUP);
                    $tmpGroup->addAttributeValue('directoryType', $directoryType);
                    $ldapGroups->add($tmpGroup);
                }

                $tmpUsers = $this->_fetchAndInitializeUsersQuery()->paginate();
                foreach ($tmpUsers as $tmpUser) {
                    $tmpUser->addAttributeValue('objectType', DirectoryInterface::ENTRY_TYPE_USER);
                    $tmpUser->addAttributeValue('directoryType', $directoryType);
                    $ldapUsers->add($tmpUser);
                }
            }

            $this->directoryResults->initializeWithLdapResults($ldapUsers, $ldapGroups);
        }

        return $this->directoryResults;
    }

    /**
     * Get users and filter them according to configured rules.
     *
     * @return array list of users formatted as entries.
     * @throws \Exception
     */
    public function getUsers(): array
    {
        $directoryResults = $this->getFilteredDirectoryResults();
        $users = $directoryResults->getUsersAsArray();

        return $users;
    }

    /**
     * Get a list of groups and filter them according to the configured filters.
     *
     * @return array list of groups formatted as entries.
     * @throws \Exception
     */
    public function getGroups(): array
    {
        $directoryResults = $this->getFilteredDirectoryResults();
        $groups = $directoryResults->getGroupsAsArray();

        return $groups;
    }

    /**
     * Customize users query as per configuration (if available).
     *
     * @param \LdapRecord\Query\Builder $query query
     * @return \LdapRecord\Query\Builder
     * @throws \InvalidArgumentException If userCustomFilter callback is used.
     * @throws \InvalidArgumentException If userCustomFilter cannot be parsed.
     */
    private function _customizeUsersQuery(Builder $query): Builder
    {
        $userCustomFilter = $this->directorySettings->getUserCustomFilters();
        if (is_callable($userCustomFilter)) {
            throw new \InvalidArgumentException(
                'Using callbacks for userCustomFilter is not supported anymore. Please use LDAP search filter instead.'
            );
        } elseif (is_string($userCustomFilter)) {
            try {
                $filter = Parser::parse($userCustomFilter);
                $query->rawFilter(Parser::assemble($filter));
            } catch (ParserException $pe) {
                throw new \InvalidArgumentException(
                    'An error has occurred parsing userCustomFilter: ' . $pe->getMessage()
                );
            }
        }

        return $query;
    }

    /**
     * Customize groups query as per configuration (if available).
     *
     * @param \LdapRecord\Query\Builder $query query
     * @return \LdapRecord\Query\Builder
     * @throws \InvalidArgumentException If groupCustomFilter callback is used.
     * @throws \InvalidArgumentException If groupCustomFilter cannot be parsed.
     */
    private function _customizeGroupsQuery(Builder $query): Builder
    {
        $groupCustomFilter = $this->directorySettings->getGroupCustomFilters();
        if (is_callable($groupCustomFilter)) {
            throw new \InvalidArgumentException(
                'Using callbacks for groupCustomFilter is not supported anymore. Please use LDAP search filter instead.'
            );
        } elseif (is_string($groupCustomFilter)) {
            try {
                $filter = Parser::parse($groupCustomFilter);
                $query->rawFilter(Parser::assemble($filter));
            } catch (ParserException $pe) {
                throw new \InvalidArgumentException(
                    'An error has occurred parsing groupCustomFilter: ' . $pe->getMessage()
                );
            }
        }

        return $query;
    }

    /**
     * Return filters used to retrieve users as a string, in ldapsearch format.
     *
     * @return string
     * @throws \Exception
     */
    public function getUserFiltersAsString(): string
    {
        $query = $this->_fetchAndInitializeUsersQuery();

        return $query->getUnescapedQuery();
    }

    /**
     * Return filters used to retrieve groups as a string, in ldapsearch format.
     *
     * @return string
     * @throws \Exception
     */
    public function getGroupFiltersAsString(): string
    {
        $query = $this->_fetchAndInitializeGroupsQuery();

        return $query->getUnescapedQuery();
    }

    /**
     * @inheritDoc
     */
    public function setUsers($users)
    {
        throw new NotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function setGroups($groups)
    {
        throw new NotImplementedException();
    }

    /**
     * Prepare settings for connection
     *
     * @param string $domain Domain name
     * @param array $settings Settings for domain
     * @return array Settings to initialize connection
     */
    protected function prepareSettings(string $domain, array $settings): array
    {
        $this->setDirectoryType($domain, $settings['ldap_type'] ?? DirectoryInterface::TYPE_AD);
        $settings['domain'] = $domain;
        $settings['use_ssl'] = (bool)($settings['use_ssl'] ?? false);
        $settings['use_tls'] = (bool)($settings['use_tls'] ?? false);
        $settings['use_sasl'] = (bool)($settings['use_sasl'] ?? false);
        $settings['sasl_options'] = $settings['sasl_options'] ?? ['sasl_mech' => DirectoryInterface::SASL_MECH_GSSAPI];
        if (isset($settings['servers'])) {
            deprecationWarning(
                'LDAP: `servers` key has been deprecated and it will be removed. Use `hosts` instead.'
            );
            $settings['hosts'] = $settings['servers'];
        }
        if (isset($settings['connect_timeout'])) {
            deprecationWarning(
                'LDAP: `connect_timeout` key has been deprecated and it will be removed. Use `timeout` instead.'
            );
            $settings['timeout'] = $settings['connect_timeout'];
        }
        $serverSelection = Hash::get($settings, 'server_selection');
        if ($serverSelection === DirectoryOrgSettings::SERVER_SELECTION_RANDOM) {
            shuffle($settings['hosts']);
        }
        if (!isset($settings['bind_format'])) {
            $settings['bind_format'] = DirectoryOrgSettings::BIND_FORMATS[$settings['ldap_type']];
        }
        if (!empty($settings['username'])) {
            $settings['username'] = DirectoryOrgSettings::formatUsername(
                $settings['username'],
                $settings['domain_name'],
                $settings['bind_format']
            );
        }

        $domainConfiguration = new DomainConfiguration();
        //Intersect settings with domain configuration keys to avoid unexpected key error from library
        return array_intersect_key($settings, $domainConfiguration->all());
    }
}
