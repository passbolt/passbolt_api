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