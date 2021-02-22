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
namespace Passbolt\DirectorySync\Test\Utility\Traits;

use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use Passbolt\DirectorySync\Utility\Alias;

trait MockDirectoryTrait
{
    protected function mockDirectoryIgnore($id, $model)
    {
        if (!isset($created)) {
            $created = '2018-07-20 06:31:57';
        }
        $entry = [
            'id' => $id,
            'foreign_model' => $model,
            'created' => $created,
        ];
        $ignore = $this->action->DirectoryEntries->DirectoryIgnore->newEntity($entry, ['validate' => false]);
        $save = $this->action->DirectoryEntries->DirectoryIgnore->save($ignore, ['checkRules' => false]);
        if (!$save) {
            throw new \InvalidArgumentException('Could not save directory sync ignore for mock');
        }

        return $entry;
    }

    protected function mockOrphanDirectoryEntryUser($data)
    {
        $data['foreign_key'] = 'null';

        return $this->mockDirectoryEntryUser($data);
    }

    protected function mockDirectoryEntryUser($data)
    {
        if (!isset($data['fname'])) {
            throw new \InvalidArgumentException('A mocked directory entry should have at least a first name');
        }
        if (!isset($data['lname'])) {
            $data['lname'] = null;
        }
        if (!isset($data['dirCreated'])) {
            $data['dirCreated'] = '2018-07-20 06:31:57';
        }
        if (!isset($data['dirModified'])) {
            $data['dirModified'] = '2018-07-20 06:31:57';
        }
        if (!isset($data['created'])) {
            $data['created'] = '2018-07-20 06:31:57';
        }
        if (!isset($data['modified'])) {
            $data['modified'] = '2018-07-20 06:31:57';
        }
        if (!isset($data['dn'])) {
            $data['dn'] = 'CN=' . ucfirst($data['fname']);
            if (isset($data['lname'])) {
                $data['dn'] .= ' ' . ucfirst($data['lname']);
            }
            $data['dn'] .= ',OU=PassboltUsers,DC=passbolt,DC=local';
        }
        if (!isset($data['foreign_key'])) {
            $data['foreign_key'] = UuidFactory::uuid('user.id.' . $data['fname']);
        }
        if ($data['foreign_key'] === 'null') {
            $data['foreign_key'] = null;
        }
        $entry = [
            'id' => UuidFactory::uuid('ldap.user.id.' . $data['fname']),
            'foreign_model' => 'Users',
            'foreign_key' => $data['foreign_key'],
            'directory_name' => $data['dn'],
            'directory_created' => $data['dirCreated'],
            'directory_modified' => $data['dirModified'],
            'created' => $data['created'],
            'modified' => $data['modified'],
        ];
        $entry = $this->action->DirectoryEntries->newEntity($entry, [
            'validate' => false,
            'accessibleFields' => [
                'id' => true,
                'foreign_model' => true,
                'foreign_key' => true,
                'directory_name' => true,
                'directory_created' => true,
                'directory_modified' => true,
                'created' => true,
                'modified' => true,
            ],
        ]);
        $save = $this->action->DirectoryEntries->save($entry, ['checkRules' => false]);
        if (!$save) {
            throw new \InvalidArgumentException('Could not save directory entry for mock');
        }

        return $entry;
    }

    protected function mockDirectoryEntryUser_old($fname, $lastname, $dirCreated = null, $dirModified = null, $created = null, $modified = null)
    {
        if (!isset($dirCreated)) {
            $dirCreated = '2018-07-20 06:31:57';
        }
        if (!isset($dirModified)) {
            $dirModified = '2018-07-20 06:31:57';
        }
        if (!isset($created)) {
            $created = '2018-07-20 06:31:57';
        }
        if (!isset($modified)) {
            $modified = '2018-07-20 06:31:57';
        }
        $entry = [
            'id' => UuidFactory::uuid('ldap.user.id.' . $fname),
            'foreign_model' => 'Users',
            'foreign_key' => UuidFactory::uuid('user.id.' . $fname),
            'directory_name' => 'CN=' . ucfirst($fname) . ' ' . ucfirst($lastname) . ',OU=PassboltUsers,DC=passbolt,DC=local',
            'directory_created' => $dirCreated,
            'directory_modified' => $dirModified,
            'created' => $created,
            'modified' => $modified,
        ];
        $entry = $this->action->DirectoryEntries->newEntity($entry, [
            'validate' => false,
            'accessibleFields' => [
                'id' => true,
                'foreign_model' => true,
                'foreign_key' => true,
                'directory_name' => true,
                'directory_created' => true,
                'directory_modified' => true,
                'created' => true,
                'modified' => true,
            ],
        ]);
        $save = $this->action->DirectoryEntries->save($entry, ['checkRules' => false]);
        if (!$save) {
            throw new \InvalidArgumentException('Could not save directory entry for mock');
        }

        return $entry;
    }

    protected function mockDirectoryGroupData(?string $name = null, ?array $options = [])
    {
        if ($name === null) {
            $name = '';
        }
        $created = $options['created'] ?? '2018-07-09 03:56:42.000000';
        $modified = $options['modified'] ?? '2018-07-09 03:56:42.000000';
        $id = $options['id'] ?? 'ldap.group.id.' . strtolower($name);
        $cn = $options['cn'] ?? $name;
        $dn = $options['dn'] ?? 'CN=' . ucfirst($cn) . ',OU=PassboltUsers,DC=passbolt,DC=local';
        $group = [
            'id' => UuidFactory::uuid($id),
            'directory_name' => $dn,
            'directory_created' => new FrozenTime($created),
            'directory_modified' => new FrozenTime($modified),
            'group' => [
                'name' => strtolower($cn),
                'groups' => [],
                'users' => $options['group_users'] ?? [],
            ],
        ];
        $this->saveMockDirectoryGroupData($group);

        return $group;
    }

    protected function mockOrphanDirectoryEntryGroup($name, $dirCreated = null, $dirModified = null, $created = null, $modified = null)
    {
        return $this->mockDirectoryEntryGroup($name, $dirCreated, $dirModified, $created, $modified, 'null');
    }

    protected function mockDirectoryEntryGroup($name, $dirCreated = null, $dirModified = null, $created = null, $modified = null, $foreignKey = null)
    {
        if (!isset($dirCreated)) {
            $dirCreated = '2018-07-20 06:31:57';
        }
        if (!isset($dirModified)) {
            $dirModified = '2018-07-20 06:31:57';
        }
        if (!isset($created)) {
            $created = '2018-07-20 06:31:57';
        }
        if (!isset($modified)) {
            $modified = '2018-07-20 06:31:57';
        }

        $defaultForeignKey = UuidFactory::uuid('group.id.' . $name);

        $entry = [
            'id' => UuidFactory::uuid('ldap.group.id.' . $name),
            'foreign_model' => Alias::MODEL_GROUPS,
            'foreign_key' => $foreignKey === 'null' ? null : $defaultForeignKey,
            'directory_name' => substr('CN=' . ucfirst($name) . ',OU=PassboltUsers,DC=passbolt,DC=local', 0, 255),
            'directory_created' => $dirCreated,
            'directory_modified' => $dirModified,
            'created' => $created,
            'modified' => $modified,
        ];
        $this->saveMockDirectoryEntry($entry);

        return $entry;
    }

    public function mockDirectoryRelationGroupUser($groupAlias, $userAlias)
    {
        $relation = [
            'id' => UuidFactory::uuid("group_user.id.$groupAlias-$userAlias"),
            'parent_key' => UuidFactory::uuid('ldap.group.id.' . $groupAlias),
            'child_key' => UuidFactory::uuid('ldap.user.id.' . $userAlias),
        ];

        return $this->action->DirectoryRelations->create($relation);
    }

    private function saveMockDirectoryEntry($data)
    {
        $entry = $this->action->DirectoryEntries->newEntity($data, [
            'validate' => false,
            'accessibleFields' => [
                'id' => true,
                'foreign_model' => true,
                'foreign_key' => true,
                'directory_name' => true,
                'directory_created' => true,
                'directory_modified' => true,
                'created' => true,
                'modified' => true,
            ],
        ]);
        $this->action->DirectoryEntries->save($entry, ['checkRules' => false]);
    }

    public function saveMockDirectoryGroupData($group)
    {
        $groups = $this->action->getDirectory()->getGroups();
        $groups[] = $group;
        $this->action->getDirectory()->setGroups($groups);
    }

    protected function mockDirectoryUserData($fname = null, $lname = null, $username = null, $created = null, $modified = null)
    {
        $fname = $fname ?? '';
        $lname = $lname ?? '';
        $username = $username ?? '';
        if (!isset($created)) {
            $created = '2018-07-09 03:56:42.000000';
        }
        if (!isset($modified)) {
            $modified = '2018-07-09 03:56:42.000000';
        }
        $id = 'ldap.user.id.' . strtolower($fname);
        $name = 'CN=' . ucfirst($fname) . ' ' . ucfirst($lname) . ',OU=PassboltUsers,DC=passbolt,DC=local';
        $user = [
            'id' => UuidFactory::uuid($id),
            'directory_name' => $name,
            'directory_created' => new FrozenTime($modified),
            'directory_modified' => new FrozenTime($created),
            'user' => [
                'username' => strtolower($username),
                'profile' => [
                    'first_name' => ucfirst($fname),
                    'last_name' => ucfirst($lname),
                ],
            ],
        ];
        $users = $this->action->getDirectory()->getUsers();
        $users[] = $user;
        $this->action->getDirectory()->setUsers($users);

        return $user;
    }
}
