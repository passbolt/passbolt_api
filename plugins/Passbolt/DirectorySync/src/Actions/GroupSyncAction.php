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

use Cake\Event\EventDispatcherTrait;
use Passbolt\DirectorySync\Actions\Traits\GroupUsersSyncTrait;
use Passbolt\DirectorySync\Actions\Traits\SyncAddTrait;
use Passbolt\DirectorySync\Actions\Traits\SyncDeleteTrait;
use Passbolt\DirectorySync\Actions\Traits\SyncTrait;
use Passbolt\DirectorySync\Utility\Alias;

class GroupSyncAction extends SyncAction
{
    use EventDispatcherTrait;
    use GroupUsersSyncTrait;
    use SyncAddTrait;
    use SyncDeleteTrait;
    use SyncTrait;

    /**
     * @var string entityType
     */
    const ENTITY_TYPE = Alias::MODEL_GROUPS;

    /**
     * @var array|mixed
     */
    public $groupsToIgnore;

    /**
     * @var array|mixed
     */
    public $defaultGroupAdmin;

    /**
     * GroupSyncAction constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * BeforeExecute.
     * @return void
     */
    public function beforeExecute()
    {
        parent::beforeExecute();
        $this->initialize(self::ENTITY_TYPE);
        $this->defaultGroupAdmin = $this->getDefaultGroupAdmin();
        if (empty($this->defaultGroupAdmin)) {
            $this->defaultGroupAdmin = $this->defaultAdmin;
        }
    }

    /**
     * Execute sync.
     *
     * @return void
     */
    protected function _execute()
    {
        $this->beforeExecute();
        $this->processEntriesToDelete();
        $this->processEntriesToCreate();
        $this->afterExecute();
    }

    /**
     * Get group from data.
     *
     * @param array $data data
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getGroupFromData(array $data)
    {
        // If not group already associated, find if there is a corresponding group in the database.
        $existingGroup = $this->Groups->find()
            ->select(['id', 'name', 'deleted', 'created', 'modified'])
            ->where(['name' => $data['group']['name']])
            ->order(['Groups.modified' => 'DESC'])
            ->first();
        if (!isset($existingGroup) || empty($existingGroup)) {
            $existingGroup = null;
        }

        return $existingGroup;
    }

    /**
     * Get default group administrator
     *
     * @return array|\Cake\Datasource\EntityInterface|mixed|null
     */
    public function getDefaultGroupAdmin()
    {
        $groupAdmin = $this->directoryOrgSettings->getDefaultGroupAdminUser();
        if (!empty($groupAdmin)) {
            // Get groupAdmin from database.
            $groupAdmin = $this->Users->find()
                ->where([
                    'Users.deleted' => false,
                    'Users.active' => true,
                    'Users.username' => $groupAdmin,
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
