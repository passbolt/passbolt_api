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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Utility;

use LdapTools\Configuration;
use Cake\Core\Configure;

use LdapTools\LdapManager;
use LdapTools\Event\Event;
use LdapTools\Event\LdapObjectSchemaEvent;
use LdapTools\Object\LdapObjectType;
use LdapTools\Connection\LdapConnection;

/**
 * Directory factory class
 * @package App\Utility
 */
class LdapDirectory implements DirectoryInterface
{
    private $ldap;
    private $groups;
    private $users;
    private $mappingRules;

    /**
     * LdapDirectory constructor.
     * @throws \Exception if connection cannot be established
     */
    function __construct() {
        $config = Configure::read('passbolt.plugins.directorySync.ldap');
        $config = (new Configuration())->loadFromArray($config);
        $this->ldap = new LdapManager($config);
        $this->ldap->getConnection();
        $this->groups = [];
        $this->users = [];
        $this->mappingRules = $this->getMappingRules();

        $this->customizeSchema();
    }

    /**
     * Used to map fields and specify the object class names that we'll need.
     */
    function customizeSchema() {
        $this->ldap->getEventDispatcher()->addListener(Event::LDAP_SCHEMA_LOAD, function(LdapObjectSchemaEvent $event) {
            $schema = $event->getLdapObjectSchema();

            // Only modify the 'user' schema type, ignore the others for this listener...
            if ($schema->getObjectType() !== LdapObjectType::GROUP && $schema->getObjectType() !== LdapObjectType::USER) {
                return;
            }

            // Set custom object class if configured.
            $objectType = $schema->getObjectType();
            $customClass = Configure::read('passbolt.plugins.directorySync.' . $objectType . 'ObjectClass');
            if (isset($customClass)) {
                $schema->setObjectClass('posixGroup');
                $schema->getFilter()->setValue('posixGroup');
            }
        });
    }

    public function getFieldValue($object, $fieldName) {
        $mappingRules = $this->getMappingRules()[$object->getType()];
        $fieldEquivalent = $mappingRules[$fieldName];
        $call = 'get' . ucfirst($fieldEquivalent);
        return $object->{$call}();
    }

    function getMappingRules () {
        $type = $this->ldap->getConnection()->getConfig()->getLdapType();
        if ($type !== LdapConnection::TYPE_AD && $type !== LdapConnection::TYPE_OPENLDAP) {
            throw new \Exception(__('Config error: the type of directory can be only ad or openldap'));
        }
        $defaultMapping = Configure::read('passbolt.plugins.directorySync.fieldsMappingDefaults')[$type];
        $userMapping = Configure::read('passbolt.plugins.directorySync.fieldsMapping');
        $mapping = array_merge($defaultMapping, $userMapping);

        return $mapping;
    }

    /**
     * Return users
     */
    public function getUsers()
    {
        $mappingRules = $this->getMappingRules()[LdapObjectType::USER];
        $selectFields = array_values($mappingRules);


        $query = $this->ldap->buildLdapQuery();
        $users = $query
            ->select($selectFields)
            ->fromUsers()
            ->getLdapQuery()
            ->getResult();

        foreach ($users as $user) {
            $this->users[] = [
                'id' => $this->getFieldValue($user, 'id'),
                'directory_name' => $this->getFieldValue($user, 'id'),
                'directory_created' => $this->getFieldValue($user, 'created'),
                'directory_modified' => $this->getFieldValue($user, 'modified'),
                'user' => [
                    'username' => $this->getFieldValue($user, 'username'),
                    'profile' => [
                        'first_name' => $this->getFieldValue($user, 'firstname'),
                        'last_name' => $this->getFieldValue($user, 'lastname'),
                    ]
                ]
            ];
        }

        return $this->users;
    }

    /**
     * Get a list of groups
     */
    public function getGroups() {
        $mappingRules = $this->getMappingRules()[LdapObjectType::GROUP];
        $selectFields = array_values($mappingRules);

        $query = $this->ldap->buildLdapQuery();
        $groups = $query
            ->select($selectFields)
            ->fromGroups()
            ->getLdapQuery()
            ->getResult();

        foreach ($groups as $group) {
            $groups = $group->getGroups();
            $members = $this->getFieldValue($group, 'users');
            $this->groups[] = [
                'id' => $this->getFieldValue($group, 'id'),
                'directory_name' => $group->getDn(),
                'directory_created' => $this->getFieldValue($group, 'created'),
                'directory_modified' => $this->getFieldValue($group, 'modified'),
                'group' => [
                    'name' => $this->getFieldValue($group, 'name'),
                    'groups' => is_array($groups) ? $groups : [],
                    'users' => is_array($members) ? $members : [],
                ]
            ];
        }
        return $this->groups;
    }
}