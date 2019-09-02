<?php
namespace Passbolt\DirectorySync\Test\Utility\Traits;

use Cake\ORM\TableRegistry;

trait AssertDirectoryRelationsTrait
{
    public function assertDirectoryRelationExist($id = null, $where = [])
    {
        if ($id !== null) {
            $where['id'] = $id;
        }
        $Relations = TableRegistry::getTableLocator()->get('DirectoryRelations');
        $results = $Relations->find()->where($where)->all()->toArray();
        $this->assertEquals(count($results), 1);

        return $results[0];
    }

    public function assertDirectoryRelationNotExist($id = null, $where = [])
    {
        if ($id !== null) {
            $where['id'] = $id;
        }
        $Relations = TableRegistry::getTableLocator()->get('DirectoryRelations');
        $results = $Relations->find()->where($where)->all()->toArray();
        $this->assertEmpty($results);
    }

    public function assertDirectoryRelationEmpty()
    {
        $Relations = TableRegistry::getTableLocator()->get('DirectoryRelations');
        $results = $Relations->find()->all()->toArray();
        $this->assertEmpty($results);
    }

    public function assertDirectoryRelationNotEmpty()
    {
        $Relations = TableRegistry::getTableLocator()->get('DirectoryRelations');
        $results = $Relations->find()->all()->toArray();
        $this->assertNotEmpty($results);
    }
}
