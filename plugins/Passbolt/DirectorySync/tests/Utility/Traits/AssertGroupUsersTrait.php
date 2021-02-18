<?php
declare(strict_types=1);

namespace Passbolt\DirectorySync\Test\Utility\Traits;

use Cake\ORM\TableRegistry;

trait AssertGroupUsersTrait
{
    public function assertGroupUserExist(?string $id = null, ?array $where = [])
    {
        if ($id !== null) {
            $where['id'] = $id;
        }
        $GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $results = $GroupsUsers->find()->where($where)->all()->toArray();
        $this->assertEquals(count($results), 1);

        return $results[0];
    }

    public function assertGroupUserNotExist(?string $id = null, ?array $where = [])
    {
        if ($id !== null) {
            $where['id'] = $id;
        }
        $GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $results = $GroupsUsers->find()->where($where)->all()->toArray();
        $this->assertEmpty($results);
    }
}
