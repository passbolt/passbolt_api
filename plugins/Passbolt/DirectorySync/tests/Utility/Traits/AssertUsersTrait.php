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
        $this->assertEquals(1, count($results), __('The user does not exist'));
    }

    public function assertUserNotExist($where = null)
    {
        $Users = TableRegistry::getTableLocator()->get('Users');
        $results = $Users->find()->where($where)->all()->toArray();
        $this->assertEquals(0, count($results), __('The user should not exist'));
    }
}