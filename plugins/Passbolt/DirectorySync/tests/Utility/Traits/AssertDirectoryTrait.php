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
namespace Passbolt\DirectorySync\Test\Utility\Traits;

use Cake\ORM\TableRegistry;
use Passbolt\DirectorySync\Utility\Alias;

trait AssertDirectoryTrait
{
    /**
     * @return void
     */
    public function assertDirectoryEntryEmpty()
    {
        $de = $this->action->DirectoryEntries->find()->all()->toArray();
        $this->assertEmpty($de, __('Directory Entries should be empty, {0} found', count($de)));
    }

    /**
     * @return void
     */
    public function assertOneDirectoryEntry()
    {
        $syncEntry = $this->action->DirectoryEntries->find()->all()->toArray();
        $this->assertEquals(count($syncEntry), 1);
    }

    /**
     * @param int $count
     * @return void
     */
    public function assertDirectoryEntryCount(int $count)
    {
        $syncEntry = $this->action->DirectoryEntries->find()->all()->toArray();
        $this->assertEquals(count($syncEntry), $count);
    }

    /**
     * @param array $where
     * @return entry
     */
    public function assertDirectoryEntryExists(array $where)
    {
        $syncEntry = $this->action->DirectoryEntries->find()->where($where)->all()->toArray();
        $this->assertEquals(1, count($syncEntry));

        return $syncEntry;
    }

    /**
     * @param array $where
     * @return entry
     */
    public function assertOrphanDirectoryEntryExists($id)
    {
        $syncEntry = $this->action->DirectoryEntries->find()->where(['id' => $id, 'foreign_key IS NULL'])->all()->toArray();
        $this->assertEquals(1, count($syncEntry));

        return $syncEntry;
    }

    /**
     * @param array $where
     * @return entry
     */
    public function assertDirectoryEntryExistsForUser($where)
    {
        $Users = TableRegistry::get('Users');
        $u = $Users->find()->where($where)->first();

        $where = ['foreign_model' => Alias::MODEL_USERS, 'foreign_key' => $u->id];
        $syncEntry = $this->action->DirectoryEntries->find()->where($where)->all()->toArray();
        $this->assertEquals(1, count($syncEntry));

        return $syncEntry;
    }

    /**
     * @param array $where
     * @return entry
     */
    public function assertDirectoryEntryExistsForGroup($where)
    {
        $Groups = TableRegistry::get('Groups');
        $g = $Groups->find()->where($where)->first();

        $where = ['foreign_model' => Alias::MODEL_GROUPS, 'foreign_key' => $g->id];
        $syncEntry = $this->action->DirectoryEntries->find()->where($where)->all()->toArray();
        $this->assertEquals(1, count($syncEntry));

        return $syncEntry;
    }

    /**
     * @param array $where
     * @return entry
     */
    public function assertDirectoryEntryNotExists(array $where)
    {
        $syncEntry = $this->action->DirectoryEntries->find()->where($where)->all()->toArray();
        $this->assertEmpty($syncEntry);

        return $syncEntry;
    }

    /**
     * @return void
     */
    public function assertDirectoryIgnoreEmpty()
    {
        $di = $this->action->DirectoryEntries->DirectoryIgnore->find()->all()->toArray();
        $this->assertEmpty($di, __('Directory ignore list should be empty, {0} found. {1}', count($di)));
    }

    /**
     *
     */
    public function assertDirectoryIgnoreNotEmpty()
    {
        $di = $this->action->DirectoryEntries->DirectoryIgnore->find()->all()->toArray();
        $this->assertTrue(count($di) > 0, __('Directory ignore list should not be empty'));
    }

    /**
     * @param array $where
     * @return void
     */
    public function assertDirectoryIgnoreExist(array $where = [])
    {
        $di = $this->action->DirectoryEntries->DirectoryIgnore->find()->where($where)->all()->toArray();
        $this->assertNotEmpty($di, __('Directory ignore list should not be empty.'));
    }

    /**
     * @param array $where
     * @return void
     */
    public function assertDirectoryIgnoreDoesNotExist(array $where = [])
    {
        $di = $this->action->DirectoryEntries->DirectoryIgnore->find()->where($where)->all()->toArray();
        $this->assertEmpty($di, __('Directory ignore list should not be empty.'));
    }

    /**
     * @param $model
     * @param $id
     */
    public function assertDirectoryIgnoreContains($model, $id)
    {
        $di = $this->action->DirectoryEntries->DirectoryIgnore
            ->find()
            ->where(['foreign_model' => $model, 'id' => $id])
            ->all()
            ->toArray();
        $this->assertNotEmpty($di);
    }
}
