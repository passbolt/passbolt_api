<?php
namespace Passbolt\DirectorySync\Test\Utility\Traits;
use Cake\ORM\TableRegistry;

trait AssertGroupsTrait
{
    public function assertGroupExist($id = null, $where = [])
    {
        if ($id !== null) {
            $where['id'] = $id;
        }
        $Groups = TableRegistry::getTableLocator()->get('Groups');
        $results = $Groups->find()->where($where)->all()->toArray();
        $this->assertEquals(count($results), 1);

        return $results[0];
    }

    public function assertGroupNotExist($id = null, $where = [])
    {
        if ($id !== null) {
            $where['id'] = $id;
        }
        $Groups = TableRegistry::getTableLocator()->get('Groups');
        $results = $Groups->find()->where($where)->all()->toArray();
        $this->assertEmpty($results);
    }
}