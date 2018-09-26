<?php
namespace Passbolt\DirectorySync\Test\Utility\Traits;

use Cake\ORM\TableRegistry;

trait AssertGroupUsersTrait
{
    public function assertGroupUserExist($id = null, $where = [])
    {
        if ($id !== null) {
            $where['id'] = $id;
        }
        $GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $results = $GroupsUsers->find()->where($where)->all()->toArray();
        $this->assertEquals(count($results), 1);

        return $results[0];
    }

    public function assertGroupUserNotExist($id = null, $where = [])
    {
        if ($id !== null) {
            $where['id'] = $id;
        }
        $GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $results = $GroupsUsers->find()->where($where)->all()->toArray();
        $this->assertEmpty($results);
    }
}
