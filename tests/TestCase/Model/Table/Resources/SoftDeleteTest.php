<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class SoftDeleteTest extends AppTestCase
{
    use FormatValidationTrait;

    public $Resources;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers',
        'app.Base/Resources', 'app.Base/Favorites', 'app.Base/Secrets',
        'app.Base/Permissions'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::getTableLocator()->get('Resources', $config);
    }

    public function tearDown()
    {
        unset($this->Resources);

        parent::tearDown();
    }

    public function testSoftDeleteSuccess()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resource = $this->Resources->get($resourceId);
        $this->assertFalse($resource->deleted);
        $this->Resources->softDelete($userId, $resource);
        $this->assertEmpty($resource->getErrors());

        // Check that the resource is well soft deleted.
        $resource = $this->Resources->get($resourceId);
        $this->assertTrue($resource->deleted);
        // No favorites in db.
        $favorites = $this->Resources->getAssociation('Favorites')
            ->find()->where(['Favorites.foreign_key' => $resource->id])->toArray();
        $this->assertEmpty($favorites);
        // No permissions in db.
        $permissions = $this->Resources->getAssociation('Permissions')
            ->find()->where(['Permissions.aco_foreign_key' => $resource->id])->toArray();
        $this->assertEmpty($permissions);
        // No secrets in db.
        $secrets = $this->Resources->getAssociation('Secrets')
            ->find()->where(['Secrets.resource_id' => $resource->id])->toArray();
        $this->assertEmpty($secrets);
    }

    public function testSoftDeleteErrorNotValidUserIdParameter()
    {
        $userId = 'not-valid-uuid';
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resource = $this->Resources->get($resourceId);
        try {
            $this->Resources->softDelete($userId, $resource);
        } catch (\InvalidArgumentException $e) {
            return $this->assertTrue(true);
        }
    }

    public function testSoftDeleteErrorResourceIsSoftDeleted()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $resource = $this->Resources->get($resourceId);
        $this->Resources->softDelete($userId, $resource);
        $errors = $resource->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['deleted']['is_not_soft_deleted']);
    }

    public function testSoftDeleteErrorAccessDenied()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $resourceId = UuidFactory::uuid('resource.id.april');
        $resource = $this->Resources->get($resourceId);
        $this->Resources->softDelete($userId, $resource);
        $errors = $resource->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['id']['has_access']);
    }

    public function testSoftDeleteErrorAccessDenied_ReadAccess()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $resource = $this->Resources->get($resourceId);
        $this->Resources->softDelete($userId, $resource);
        $errors = $resource->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['id']['has_access']);
    }
}
