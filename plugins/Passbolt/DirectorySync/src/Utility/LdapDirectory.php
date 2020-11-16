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

use LdapTools\Configuration;
use LdapTools\Connection\LdapConnection;
use LdapTools\Event\Event;
use LdapTools\Event\LdapObjectSchemaEvent;
use LdapTools\LdapManager;
use LdapTools\Object\LdapObjectCollection;
use LdapTools\Object\LdapObjectType;
use LdapTools\Query\LdapQueryBuilder;
use Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults;

/**
 * Directory factory class
 *
 * @package App\Utility
 */
class LdapDirectory implements DirectoryInterface
{
    private $directorySettings;
    private $ldap;
    private $mappingRules;
    private $directoryType;
    private $directoryResults = null;

    /**
     * LdapDirectory constructor.
     *
     * @param \Passbolt\DirectorySync\Utility\DirectoryOrgSettings $settings The directory settings
     * @throws \Exception if connection cannot be established
     */
    public function __construct(DirectoryOrgSettings $settings)
    {
        $this->directorySettings = $settings;
        $ldapConfig = (new Configuration())->loadFromArray($this->directorySettings->getLdapSettings());

        // Load custom schema folders if they exist.
        // 1) first try to load the user custom schema folder if it exists in config/DirectorySync/schema
        // 2) if not, try to load the plugin schema folder in DirectorySync/config/schema
        // 3) if none of the above exists, stick to the default schema.
        if (file_exists(CONFIG . 'DirectorySync' . DS . 'schema')) {
            $ldapConfig->setSchemaFolder(CONFIG . 'DirectorySync' . DS . 'schema');
        } elseif (file_exists(PLUGINS . 'Passbolt' . DS . 'DirectorySync' . DS . 'config' . DS . 'schema')) {
            $ldapConfig->setSchemaFolder(PLUGINS . 'Passbolt' . DS . 'DirectorySync' . DS . 'config' . DS . 'schema');
        }

        $this->ldap = new LdapManager($ldapConfig);
        $this->directoryType = $this->getDirectoryType();
        $this->mappingRules = $this->getMappingRules();
        $this->directoryResults = new DirectoryResults($this->mappingRules, $this->directorySettings);

        $this->customizeSchema();
    }

    /**
     * Used to map fields and specify the object class names that we'll need.
     *
     * @return void
     */
    public function customizeSchema()
    {
        $this->ldap->getEventDispatcher()->addListener(
            Event::LDAP_SCHEMA_LOAD,
            function (LdapObjectSchemaEvent $event) {
                $schema = $event->getLdapObjectSchema();

                // Only modify the 'user' schema type, ignore the others for this listener...
                $objectType = $schema->getObjectType();
                if ($objectType !== LdapObjectType::GROUP && $objectType !== LdapObjectType::USER) {
                    return;
                }

                // Set custom object class if configured.
                $customClass = $this->directorySettings->getObjectClass($objectType);
                $connectionType = $this->ldap->getConnection()->getConfig()->getLdapType();
                if (isset($customClass) && $connectionType == LdapConnection::TYPE_OPENLDAP) {
                    $schema->setObjectClass($customClass);
                    $schema->getFilter()->setValue($customClass);
                }
            }
        );
    }

    /**
     * Get DN Full Path as per configuration.
     *
     * @param string $ldapObjectType ldap object type (user or group)
     * @return string
     */
    public function getDNFullPath(string $ldapObjectType)
    {
        $paths = [];
        $paths['additionalPath'] = $this->directorySettings->getObjectPath($ldapObjectType);
        $paths['baseDN'] = $this->ldap->getConnection()->getConfig()->getBaseDn();

        return ltrim(implode(',', $paths), ',');
    }

    /**
     * Get directory type.
     *
     * @return string
     */
    public function getDirectoryType()
    {
        if (isset($this->directoryType)) {
            return $this->directoryType;
        }

        $this->directoryType = $this->ldap->getConnection()->getConfig()->getLdapType();

        return $this->directoryType;
    }

    /**
     * Get mapping rules.
     *
     * @return mixed
     * @throws \Exception
     */
    public function getMappingRules()
    {
        $type = $this->getDirectoryType();
        if ($type !== LdapConnection::TYPE_AD && $type !== LdapConnection::TYPE_OPENLDAP) {
            throw new \Exception(__('Config error: the type of directory can be only ad or openldap'));
        }
        $mapping = $this->directorySettings->getFieldsMapping($type);

        return $mapping;
    }

    /**
     * Set directory results.
     *
     * @param \Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults $results results
     * @return void
     */
    public function setDirectoryResults(DirectoryResults $results)
    {
        $this->directoryResults = $results;
    }

    /**
     * Get directory results.
     *
     * @return \Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults|null
     */
    public function getDirectoryResults()
    {
        return $this->directoryResults;
    }

    /**
     * Get directory results with filtered applied (as per filters defined in the config).
     *
     * @return \Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults directory results
     * @throws \Exception
     */
    public function getFilteredDirectoryResults()
    {
        $directoryResults = $this->fetchDirectoryData();
        $users = $directoryResults->getUsersAsArray();
        $groups = $directoryResults->getGroupsAsArray();

        $usersFromGroup = $this->directorySettings->getUsersParentGroup();
        if (!empty($usersFromGroup)) {
            $filteredUsers = $directoryResults->getRecursivelyFromParentGroup(LdapObjectType::USER, $usersFromGroup);
            $users = $filteredUsers->getUsersAsArray();
        }

        $groupsFromGroup = $this->directorySettings->getGroupsParentGroup();
        if (!empty($groupsFromGroup)) {
            $filteredGroups = $directoryResults->getRecursivelyFromParentGroup(LdapObjectType::GROUP, $groupsFromGroup);
            $groups = $filteredGroups->getGroupsAsArray();
        }

        $directoryResults = new DirectoryResults($this->mappingRules, $this->directorySettings);
        $directoryResults->initializeWithEntries($users, $groups);

        return $directoryResults;
    }

    /**
     * Fetch and initialize all users that are in the provided DN.
     *
     * @return \LdapTools\Query\LdapQueryBuilder query corresponding to the list of users.
     * @throws \Exception
     */
    private function _fetchAndInitializeUsersQuery()
    {
        $enabledUsersOnly = $this->directorySettings->getEnabledUsersOnly();

        $query = $this->ldap->buildLdapQuery();
        $usersQuery = $query
            ->setBaseDn($this->getDNFullPath(LdapObjectType::USER))
            ->fromUsers();

        if (!empty($enabledUsersOnly) && $enabledUsersOnly == true) {
            $usersQuery->andWhere(['enabled' => true]);
        }

        $usersQuery = $this->_customizeUsersQuery($usersQuery);

        return $usersQuery;
    }

    /**
     * Fetch and initialize all groups that are in the provided DN.
     *
     * @return \LdapTools\Query\LdapQueryBuilder query corresponding to groups entry.
     * @throws \Exception
     */
    private function _fetchAndInitializeGroupsQuery()
    {
        $query = $this->ldap->buildLdapQuery();
        $groupsQuery = $query
            ->setBaseDn($this->getDNFullPath(LdapObjectType::GROUP))
            ->fromGroups();

        $groupsQuery = $this->_customizeGroupsQuery($groupsQuery);

        return $groupsQuery;
    }

    /**
     * Fetch directory data and cache it.
     *
     * @return \Passbolt\DirectorySync\Utility\DirectoryEntry\DirectoryResults|null
     * @throws \Exception
     */
    public function fetchDirectoryData()
    {
        // Fetch directory data for all domains.
        $domains = $this->ldap->getDomains();
        $ldapGroups = new LdapObjectCollection();
        $ldapUsers = new LdapObjectCollection();

        if ($this->directoryResults->isEmpty()) {
            foreach ($domains as $domain) {
                $this->ldap->switchDomain($domain);
                $tmpGroups = $this->_fetchAndInitializeGroupsQuery()->getLdapQuery()->getResult();
                if ($tmpGroups) {
                    foreach ($tmpGroups as $tmpGroup) {
                        $ldapGroups->add($tmpGroup);
                    }
                }

                $tmpUsers = $this->_fetchAndInitializeUsersQuery()->getLdapQuery()->getResult();
                if ($tmpUsers) {
                    foreach ($tmpUsers as $tmpUser) {
                        $ldapUsers->add($tmpUser);
                    }
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
    public function getUsers()
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
    public function getGroups()
    {
        $directoryResults = $this->getFilteredDirectoryResults();
        $groups = $directoryResults->getGroupsAsArray();

        return $groups;
    }

    /**
     * Customize users query as per configuration (if available).
     *
     * @param \LdapTools\Query\LdapQueryBuilder $query query
     * @return \LdapTools\Query\LdapQueryBuilder
     */
    private function _customizeUsersQuery(LdapQueryBuilder $query)
    {
        $f = $this->directorySettings->getUserCustomFilters();
        if (is_callable($f)) {
            $query = $f($query);
        }

        return $query;
    }

    /**
     * Customize groups query as per configuration (if available).
     *
     * @param \LdapTools\Query\LdapQueryBuilder $query query
     * @return \LdapTools\Query\LdapQueryBuilder
     */
    private function _customizeGroupsQuery(LdapQueryBuilder $query)
    {
        $f = $this->directorySettings->getGroupCustomFilters();
        if (is_callable($f)) {
            $query = $f($query);
        }

        return $query;
    }

    /**
     * Return filters used to retrieve users as a string, in ldapsearch format.
     *
     * @return string
     * @throws \Exception
     */
    public function getUserFiltersAsString()
    {
        $query = $this->_fetchAndInitializeUsersQuery();

        return $query->toLdapFilter();
    }

    /**
     * Return filters used to retrieve groups as a string, in ldapsearch format.
     *
     * @return string
     * @throws \Exception
     */
    public function getGroupFiltersAsString()
    {
        $query = $this->_fetchAndInitializeGroupsQuery();

        return $query->toLdapFilter();
    }
}
