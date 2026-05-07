<?php
declare(strict_types=1);

namespace Passbolt\DirectorySync\Test\Utility\Traits;

use Cake\ORM\TableRegistry;

trait AssertDirectoryRelationsTrait
{
    public function assertDirectoryRelationExist(?string $id = null, ?array $where = [])
    {
        if ($id !== null) {
            $where['id'] = $id;
        }
        $Relations = TableRegistry::getTableLocator()->get('DirectoryRelations');
        $results = $Relations->find()->where($where)->all()->toArray();
        $this->assertEquals(count($results), 1);

        return $results[0];
    }

    public function assertDirectoryRelationNotExist(?string $id = null, ?array $where = [])
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
