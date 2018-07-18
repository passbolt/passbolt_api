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

use LdapTools\LdapManager;
use LdapTools\Configuration;
use Cake\Core\Configure;

/**
 * Directory factory class
 * @package App\Utility
 */
class LdapDirectory implements DirectoryInterface
{
    private $ldap;
    private $groups;
    private $users;

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
    }

    /**
     * Return users
     */
    public function getUsers()
    {
        $query = $this->ldap->buildLdapQuery();
        $users = $query
            ->select(['guid','firstname', 'lastname', 'groups', 'emailAddress', 'created', 'modified'])
            ->fromUsers()
            ->getLdapQuery()
            ->getResult();

        foreach ($users as $user) {
            $this->users[$user->getDn()] = [
                'username' => $user->getEmailAddress(),
                'profile' => [
                    'first_name' => $user->getFirstname(),
                    'last_name' => $user->getLastname(),
                ],
                'directory' => [
                    'id' => $user->getGuid(),
                    'name' => $user->getDn(),
                    'created' => $user->created,
                    'modified' => $user->modified,
                ]
            ];
        }
        return $this->users;
    }

    /**
     * Get a list of groups
     */
    public function getGroups() {
        $query = $this->ldap->buildLdapQuery();
        $groups = $query
            ->select(['guid', 'name', 'members', 'created', 'modified'])
            ->fromGroups()
            ->getLdapQuery()
            ->getResult();

        foreach ($groups as $group) {
            $this->groups[$group->getDn()] = [
                'name' => $group->getName(),
                'directory' => [
                    'id' => $group->getGuid(),
                    'external_id' => $group->getDn(),
                    'created' => $group->created,
                    'modified' => $group->modified,
                    'members' => $group->getMembers(),
                    'groups' => $group->getGroups()
                ],

            ];
        }
        return $this->groups;
    }
}