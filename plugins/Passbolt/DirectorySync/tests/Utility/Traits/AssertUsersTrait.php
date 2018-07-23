<?php
namespace Passbolt\DirectorySync\Test\Utility\Traits;
use Cake\ORM\TableRegistry;

trait AssertUsersTrait
{
    public function assertUserExist($id, $where = null)
    {
        $where['id'] = $id;
        $Users = TableRegistry::getTableLocator()->get('Users');
        $results = $Users->find()->where($where)->all()->toArray();
        $this->assertEquals(count($results), 1);
    }
}