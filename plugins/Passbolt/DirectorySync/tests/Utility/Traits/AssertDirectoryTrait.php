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
     * @param string $status
     * @return void
     */
    public function assertOneDirectoryEntry(string $status)
    {
        $syncEntry = $this->action->DirectoryEntries->find()->all()->toArray();
        $this->assertEquals(count($syncEntry), 1);
        $this->assertEquals($syncEntry[0]['status'], $status);
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