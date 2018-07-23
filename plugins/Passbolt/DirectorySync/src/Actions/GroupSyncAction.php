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
     * @var array|mixed
     */
    public $DirectoryIgnore;

    /**
     * @var array|mixed
     */
    public $directoryData;

    /**
     * @var array|mixed
     */
    public $groupIdsToIgnore;

    /**
     * @var array|mixed
     */
    public $directoryIdsToIgnore;

    /**
     * @var array|mixed
     */
    public $defaultAdmin;

    /**
     * @var array|mixed
     */
    public $defaultGroupAdmin;

    /**
     * GroupSyncAction constructor.
     *
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct();
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->DirectoryIgnore = TableRegistry::getTableLocator()->get('DirectoryIgnore');
        $this->directoryData = $this->directory->getGroups();

        // Find all groups to ignore.
        $this->groupIdsToIgnore = Hash::extract($this->DirectoryIgnore->find()
           ->select(['id'])
           ->where(['foreign_model' => 'Group'])
           ->all()
           ->toArray(), '{n}.id');

        // Find all the entries to ignore.
        $this->directoryIdsToIgnore = Hash::extract($this->DirectoryIgnore->find()
            ->select(['id'])
            ->where(['foreign_model' => 'DirectoryEntry'])
            ->all()
            ->toArray(), '{n}.id');

        $this->defaultAdmin = $this->getDefaultAdmin();
        if (empty($this->defaultAdmin)) {
            throw new \Exception('Configuration issue. A default admin user cannot be found.');
        }

        $this->defaultGroupAdmin = $this->getDefaultGroupAdmin();
        if (empty($this->defaultGroupAdmin)) {
            throw new \Exception('Configuration issue. A default group admin user cannot be found.');
        }

    }

    /**
     * Execute groups sync.
     */
    public function execute() {
//        if (Configure::read('passbolt.plugins.directorySync.jobs.groups.delete')) {
//            $this->processDeletedEntries();
//        }
//        return;

        if (!isset($this->directoryData) || empty($this->directoryData)) {
            // Directory is empty nothing to do
            return;
        }

        $added = $this->processCreatedEntries();

        return true;
    }

    public function processCreatedEntries() {
        // Find all the entries
        $syncedEntries = $this->DirectoryEntries->find()
            ->select()
            ->where(['DirectoryEntries.foreign_model' => 'Group'])
            ->all()
            ->toArray();

        $res = [
            'added' => [],
            'errors' => [],
        ];

        foreach($this->directoryData as $i => $data) {
            // TODO: add condition, if it is in the array but doesn't have an id saved?

            $isSynced = in_array($data['id'], $syncedEntries);
            if (!$isSynced) {
                $data['group'] = $this->manageGroupUsers($data['group']);

                try {
                    $g = $this->Groups->create($data['group'], new UserAccessControl(Role::ADMIN, $this->defaultAdmin->id));
                    $res['added'][] = $g;
                    pr ("added {$data['group']['name']}");
                } catch(ValidationException $exception) {
                    pr("Group {$data['group']['name']} could not be saved: " . $exception->getMessage());
                    $entity = $exception->getEntity();
                    $res['errors'][] = $entity;
                    pr($entity->getErrors());
                } catch (\Exception $exception) {
                    pr($exception->getMessage());
                    $res['errors'] = $exception->getMessage();
                }

                if (!empty ($g)) {
                    $directoryEntry = [
                        'id' => $data['id'],
                        'foreign_model' => 'Group',
                        'foreign_key' => $g->id,
                        'directory_name' => $data['directory_name'],
                        'directory_created' => $data['directory_created']->date,
                        'directory_modified' => $data['directory_modified']->date,
                        'status' => DirectoryEntry::STATUS_SUCCESS,
                    ];
                    try {
                        $this->DirectoryEntries->create($directoryEntry);
                    }catch(ValidationException $exception) {
                        $entity = $exception->getEntity();
                        pr($entity->getErrors());
                    } catch(\Exception $e) {
                        pr($exception->getMessage());
                    }
                }
            }
        }

        return $res;
    }

    // Dummy function for now. Will be improved later.
    public function manageGroupUsers($group) {
        $group['groups_users'][] = [
            'user_id' => $this->defaultGroupAdmin->id,
            'is_admin' => true,
        ];
        return $group;
    }

    /**
     * Get default group administrator
     * @return array|\Cake\Datasource\EntityInterface|mixed|null
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

    /**
     * Get default admin.
     * @return array|\Cake\Datasource\EntityInterface|mixed|null
     */
    public function getDefaultAdmin() {
        $defaultUser = Configure::read('passbolt.plugins.directorySync.defaultUser');
        if (!empty($defaultUser)) {
            // Get default user from database.
            $defaultUser =
                $this->Users->find()
                    ->where([
                        'Users.deleted' => false,
                        'Users.active' => true,
                        'Users.username' => $defaultUser,
                        'Users.role_id' => $this->Users->Roles->getIdByName(Role::ADMIN),
                    ])
                    ->first();
            if (!empty($defaultUser)) {
                return $defaultUser;
            }
        }

        // If can't find corresponding config user, return first admin.
        return $this->Users->findFirstAdmin();
    }
}