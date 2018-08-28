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

use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Actions\Traits\GroupUsersSyncTrait;
use Passbolt\DirectorySync\Actions\Traits\SyncAddTrait;
use Passbolt\DirectorySync\Actions\Traits\SyncDeleteTrait;
use Passbolt\DirectorySync\Actions\Traits\SyncTrait;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\SyncAction;

class UserSyncAction extends SyncAction
{
    use SyncTrait;
    use SyncDeleteTrait;
    use SyncAddTrait;
    use GroupUsersSyncTrait;

    /**
     * @var string entityType
     */
    const ENTITY_TYPE = Alias::MODEL_USERS;

    /**
     * UserSyncAction constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Things to do after the constructor and before the sync job
     */
    public function beforeExecute()
    {
        parent::beforeExecute();
        $this->initialize(self::ENTITY_TYPE);
    }

    /**
     * Perform a sync
     * - Delete all users/groups that can be deleted
     * - Create all users/groups that can be created
     * - Generate report
     *
     * @return \Passbolt\DirectorySync\Utility\ActionReportCollection
     */
    public function execute()
    {
        $this->beforeExecute();
        $this->processEntriesToDelete();
        $this->processEntriesToCreate();
        $this->afterExecute();

        return $this->getSummary();
    }

    /**
     * @param $data
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    protected function getUserFromData($data)
    {
        $existingUser = $this->Users->find()
            ->select(['id', 'username', 'active', 'deleted', 'created', 'modified'])
            ->where(['username' => $data['user']['username']])
            ->order(['Users.modified' => 'DESC'])
            ->first();
        if (!isset($existingUser) || empty($existingUser)) {
            $existingUser = null;
        }

        return $existingUser;
    }
}
