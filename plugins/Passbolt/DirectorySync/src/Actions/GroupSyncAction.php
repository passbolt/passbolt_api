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
namespace Passbolt\DirectorySync\Actions;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Model\Entity\DirectoryEntry;

class GroupSyncAction extends SyncAction
{
    /**
     * @var \Cake\ORM\Table
     */
    public $Groups;

    /**
     * @var \Cake\ORM\Table
     */
    public $Users;

    /**
     * GroupSyncAction constructor.
     *
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct();
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * Execute groups sync.
     */
    public function execute() {
        $directoryEntries = $this->directory->getGroups();
        if (!isset($directoryEntries) || empty($directoryEntries)) {
            // Directory is empty nothing to do
            return;
        }

        // Get first admin.
        // TODO: find a solution. It should not be this user that creates groups.
        // Should we have a ldap user ????
        $firstAdmin = $this->Users->findFirstAdmin();

        // Find all the entities
        $syncedEntries = $this->DirectoryEntries->find()
            ->select()
            ->where(['DirectoryEntries.id IN' => Hash::extract($directoryEntries, '{n}.id')])
            ->contain('Groups')
            ->all()
            ->toArray();

        $ignoredEntries = Hash::extract($syncedEntries, '{n}[status='. DirectoryEntry::STATUS_IGNORE.'].id');

        foreach($directoryEntries as $i => $data) {
            if (in_array($data['id'], $ignoredEntries)) {
                // entry is marked as to be ignored
                continue;
            }
            if (in_array($data['id'], $syncedEntries)) {
                // entry is already synced
                // TODO edit group
            }

            $data['group'] = $this->manageGroupUsers($data['group']);

            try {
                $g = $this->Groups->create($data['group'], new UserAccessControl(Role::ADMIN, $firstAdmin->id));
                $result['success'] = $g;
            } catch(ValidationException $exception) {
                pr("Group {$data['group']['name']} could not be saved: " . $exception->getMessage());
                $entity = $exception->getEntity();
                pr($entity->getErrors());
            } catch (\Exception $exception) {
                pr($exception->getMessage());
            }
        }

        return true;
    }

    // Dummy function for now. Will be improved later.
    public function manageGroupUsers($group) {
        // TODO: optimize this and do not execute it at every call.
        $defaultGroupAdmin = $this->getDefaultGroupAdmin();

        $group['groups_users'][] = [
            'user_id' => $defaultGroupAdmin->id,
            'is_admin' => true,
        ];
        return $group;
    }

    /**
     * Get default group administrator
     */
    public function getDefaultGroupAdmin() {
        $groupAdmin = Configure::read('passbolt.plugins.directorySync.defaultGroupAdminUser');
        if (!empty($groupAdmin)) {
            // Get groupAdmin from database.
            $groupAdmin =
                $this->Users->find()
                    ->where([
                      'Users.deleted' => false,
                      'Users.active' => true,
                      'Users.username' => $groupAdmin
                    ])
                    ->first();
            if (!empty($groupAdmin)) {
                return $groupAdmin;
            }
        }

        // If can't find corresponding config user, return first admin.
        return $this->Users->findFirstAdmin();
    }

}