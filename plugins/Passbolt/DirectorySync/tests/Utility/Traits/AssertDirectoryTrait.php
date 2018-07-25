<?php
namespace Passbolt\DirectorySync\Test\Utility\Traits;
use Cake\ORM\TableRegistry;

trait AssertDirectoryTrait
{
    public function assertDirectoryEntryEmpty()
    {
        $de = $this->action->DirectoryEntries->find()->all()->toArray();
        $this->assertEmpty($de, __('Directory Entries should be empty, {0} found', count($de)));
    }

    public function assertOneDirectoryEntry($status)
    {
        $syncEntry = $this->action->DirectoryEntries->find()->all()->toArray();
        $this->assertEquals(count($syncEntry), 1);
        $this->assertEquals($syncEntry[0]['status'], $status);
    }

    public function assertDirectoryEntryExist($id, $where = [])
    {
        $where['id'] = $id;
        $syncEntry = $this->action->DirectoryEntries->find()->where($where)->all()->toArray();
        $this->assertEquals(count($syncEntry), 1);
    }

    public function assertDirectoryIgnoreEmpty()
    {
        $di = $this->action->DirectoryEntries->DirectoryIgnore->find()->all()->toArray();
        $this->assertEmpty($di, __('Directory ignore list should be empty, {0} found', count($di)));
    }

    public function assertDirectoryIgnoreNotEmpty()
    {
        $di = $this->action->DirectoryEntries->DirectoryIgnore->find()->all()->toArray();
        $this->assertTrue(count($di) > 0, __('Directory ignore list should not be empty'));
    }

    public function assertDirectoryIgnoreContains($model, $id)
    {
        $di = $this->action->DirectoryEntries->DirectoryIgnore
            ->find()
            ->where(['foreign_model' => $model, 'id' => $id])
            ->all()
            ->toArray();
        $this->assertNotEmpty($di);
    }

    public function assertUserExist($id, $where = null)
    {
        $where['id'] = $id;
        $Users = TableRegistry::getTableLocator()->get('Users');
        $results = $Users->find()->where($where)->all()->toArray();
        $this->assertEquals(count($results), 1);
    }
}