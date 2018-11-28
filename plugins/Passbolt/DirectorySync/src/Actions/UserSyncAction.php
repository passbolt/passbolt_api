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

use Passbolt\DirectorySync\Actions\Traits\GroupUsersSyncTrait;
use Passbolt\DirectorySync\Actions\Traits\SyncAddTrait;
use Passbolt\DirectorySync\Actions\Traits\SyncDeleteTrait;
use Passbolt\DirectorySync\Actions\Traits\SyncTrait;
use Passbolt\DirectorySync\Utility\Alias;

class UserSyncAction extends SyncAction
{
    use GroupUsersSyncTrait;
    use SyncAddTrait;
    use SyncDeleteTrait;
    use SyncTrait;

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
     * @return void
     */
    public function beforeExecute()
    {
        parent::beforeExecute();
        $this->initialize(self::ENTITY_TYPE);
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
     * Get user from data.
     * @param array $data data
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    protected function getUserFromData(array $data)
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
