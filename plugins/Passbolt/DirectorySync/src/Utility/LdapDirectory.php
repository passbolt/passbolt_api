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

use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use LdapTools\Configuration;
use LdapTools\Connection\LdapConnection;
use LdapTools\Event\Event;
use LdapTools\Event\LdapObjectSchemaEvent;
use LdapTools\LdapManager;
use LdapTools\Object\LdapObject;
use LdapTools\Object\LdapObjectType;

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
    public function __construct()
    {
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
     * @return void
     */
    public function customizeSchema()
    {
        $this->ldap->getEventDispatcher()->addListener(Event::LDAP_SCHEMA_LOAD, function (LdapObjectSchemaEvent $event) {
            $schema = $event->getLdapObjectSchema();

            // Only modify the 'user' schema type, ignore the others for this listener...
            if ($schema->getObjectType() !== LdapObjectType::GROUP && $schema->getObjectType() !== LdapObjectType::USER) {
                return;
            }

            // Set custom object class if configured.
            $objectType = $schema->getObjectType();
            $customClass = Configure::read('passbolt.plugins.directorySync.' . $objectType . 'ObjectClass');
            $connectionType = $this->ldap->getConnection()->getConfig()->getLdapType();
            if (isset($customClass) && $connectionType == LdapConnection::TYPE_OPENLDAP) {
                $schema->setObjectClass($customClass);
                $schema->getFilter()->setValue($customClass);
            }
        });
    }

    /**
     * Get DN Full Path as per configuration.
     * @param string $ldapObjectType ldap object type (user or group)
     *
     * @return string
     */
    public function getDNFullPath(string $ldapObjectType)
    {
        $paths = [];
        $paths['additionalPath'] = Configure::read('passbolt.plugins.directorySync.' . $ldapObjectType . 'Path');
        $paths['baseDN'] = $this->ldap->getConnection()->getConfig()->getBaseDn();

        return ltrim(implode(',', $paths), ',');
    }

    /**
     * Get field value.
     * @param LdapObject $object object
     * @param string $fieldName field name
     *
     * @return mixed
     */
    public function getFieldValue($object, $fieldName)
    {
        $mappingRules = $this->getMappingRules()[$object->getType()];
        $fieldEquivalent = $mappingRules[$fieldName];
        $call = 'get' . ucfirst($fieldEquivalent);

        return $object->{$call}();
    }

    /**
     * Get mapping rules.
     * @return mixed
     * @throws \Exception
     */
    public function getMappingRules()
    {
        $type = $this->ldap->getConnection()->getConfig()->getLdapType();
        if ($type !== LdapConnection::TYPE_AD && $type !== LdapConnection::TYPE_OPENLDAP) {
            throw new \Exception(__('Config error: the type of directory can be only ad or openldap'));
        }
        $mapping = Configure::read('passbolt.plugins.directorySync.fieldsMapping.' . $type);

        return $mapping;
    }

    /**
     * Return users
     * @return array
     */
    public function getUsers()
    {
        $mappingRules = $this->getMappingRules()[LdapObjectType::USER];
        $selectFields = array_values($mappingRules);

        $query = $this->ldap->buildLdapQuery();
        $users = $query
            ->setBaseDn($this->getDNFullPath(LdapObjectType::USER))
            ->select($selectFields)
            ->fromUsers()
            ->getLdapQuery()
            ->getResult();

        foreach ($users as $user) {
            $this->users[] = [
                'id' => $this->getFieldValue($user, 'id'),
                'directory_name' => $user->getDn(),
                'directory_created' => new FrozenTime($this->getFieldValue($user, 'created')),
                'directory_modified' => new FrozenTime($this->getFieldValue($user, 'modified')),
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
     * @return array
     */
    public function getGroups()
    {
        $mappingRules = $this->getMappingRules()[LdapObjectType::GROUP];
        $selectFields = array_values($mappingRules);

        $query = $this->ldap->buildLdapQuery();
        $groups = $query
            ->setBaseDn($this->getDNFullPath(LdapObjectType::GROUP))
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
                'directory_created' => new FrozenTime($this->getFieldValue($group, 'created')),
                'directory_modified' => new FrozenTime($this->getFieldValue($group, 'modified')),
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
