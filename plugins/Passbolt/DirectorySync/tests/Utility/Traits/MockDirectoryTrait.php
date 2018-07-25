<?php
namespace Passbolt\DirectorySync\Test\Utility\Traits;
use Cake\I18n\FrozenTime;
use App\Utility\UuidFactory;
use Psr\Log\InvalidArgumentException;

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
            'created' => $created
        ];
        $ignore = $this->action->DirectoryEntries->DirectoryIgnore->newEntity($entry, ['validate' => false]);
        $save = $this->action->DirectoryEntries->DirectoryIgnore->save($ignore, ['checkRules' => false]);
        if (!$save) {
            throw new InvalidArgumentException('Could not save directory sync ignore for mock');
        }
        return $entry;
    }

    protected function mockDirectoryEntryUser($fname, $lastname, $status, $dirCreated = null, $dirModified = null, $created = null, $modified = null)
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
            'directory_name' => 'CN='. ucfirst($fname) . ' ' . ucfirst($lastname) . ',OU=PassboltUsers,DC=passbolt,DC=local',
            'directory_created' => $dirCreated,
            'directory_modified' => $dirModified,
            'status' => $status,
            'created' => $created,
            'modified' => $modified
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
                'status' => true,
                'created' => true,
                'modified' => true
            ]
        ]);
        $save = $this->action->DirectoryEntries->save($entry, ['checkRules' => false]);
        if (!$save) {
            throw new InvalidArgumentException('Could not save directory entry for mock');
        }
        return $entry;
    }

    protected function mockDirectoryGroupData($name = null, $groupUsers = [], $created = null, $modified = null)
    {
        if (!isset($created)) {
            $created = '2018-07-09 03:56:42.000000';
        }
        if (!isset($modified)) {
            $modified = '2018-07-09 03:56:42.000000';
        }
        $id = 'ldap.group.id.' . strtolower($name);
        $dn = 'CN=' . ucfirst($name) . ',OU=PassboltUsers,DC=passbolt,DC=local';
        $group = [
            'id' => UuidFactory::uuid($id),
            'directory_name' => $dn,
            'directory_created' => new FrozenTime($created),
            'directory_modified' => new FrozenTime($modified),
            'group' => [
                'name' => strtolower($name),
                'groups' => [],
                'users' => [],
            ]
        ];
        $this->saveMockDirectoryGroupData($group);
        return $group;
    }

    protected function mockDirectoryEntryGroup($name, $status, $dirCreated = null, $dirModified = null, $created = null, $modified = null, $foreignKey = null)
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

        $defaultForeignKey = $foreignKey === false ? null : UuidFactory::uuid('group.id.' . $name);

        $entry = [
            'id' => UuidFactory::uuid('ldap.group.id.' . $name),
            'foreign_model' => 'Groups',
            'foreign_key' => $foreignKey !== null ? $foreignKey : $defaultForeignKey,
            'directory_name' => 'CN='. ucfirst($name) . ',OU=PassboltUsers,DC=passbolt,DC=local',
            'directory_created' => $dirCreated,
            'directory_modified' => $dirModified,
            'status' => $status,
            'created' => $created,
            'modified' => $modified
        ];
        $this->saveMockDirectoryEntry($entry);
        return $entry;
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
                'status' => true,
                'created' => true,
                'modified' => true
            ]
        ]);
        $this->action->DirectoryEntries->save($entry, ['checkRules' => false]);
    }

    private function saveMockDirectoryGroupData($group)
    {
        $groups = $this->action->getDirectory()->getGroups();
        $groups[] = $group;
        $this->action->getDirectory()->setGroups($groups);
    }

    protected function mockDirectoryUserData($fname = null, $lname = null, $username = null, $created = null, $modified = null)
    {
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
                    'last_name' => ucfirst($lname)
                ]
            ]
        ];
        $users = $this->action->getDirectory()->getUsers();
        $users[] = $user;
        $this->action->getDirectory()->setUsers($users);
        return $user;
    }
}