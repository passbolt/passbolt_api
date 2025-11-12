<?php
declare(strict_types=1);

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

use App\Model\Entity\Permission;
use App\Model\Table\ResourcesTable;
use App\Test\Factory\FavoriteFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use Cake\ORM\TableRegistry;
use InvalidArgumentException;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

class SoftDeleteTest extends AppTestCase
{
    use FormatValidationTrait;

    public ResourcesTable $Resources;

    public function setUp(): void
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    public function tearDown(): void
    {
        unset($this->Resources);

        parent::tearDown();
    }

    public function testSoftDeleteSuccess()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        // Create several resources associated with this user for more randomness
        [$resource, $otherResource] = ResourceFactory::make(3)
            ->withPermissionsFor([$user])
            ->withSecretsFor([$user])
            ->withSecretRevisions()
            ->persist();
        FavoriteFactory::make()->setResource($resource)->persist();
        FavoriteFactory::make()->setResource($otherResource)->persist();

        $this->Resources->softDelete($user->id, $resource);
        $this->assertEmpty($resource->getErrors());

        // Check that the resource is well soft deleted.
        $resource = $this->Resources->get($resource->id);
        $this->assertTrue($resource->deleted);

        // Description, username and URI are empty
        $this->assertEmpty($resource->username);
        $this->assertEmpty($resource->uri);
        $this->assertEmpty($resource->description);

        // No favorites for this resource in db.
        $favorites = $this->Resources->getAssociation('Favorites')
            ->find()->where(['Favorites.foreign_key' => $resource->id])->toArray();
        $this->assertEmpty($favorites);
        $this->assertSame(1, FavoriteFactory::count());
        // No permissions for this resource in db.
        $permissions = $this->Resources->getAssociation('Permissions')
            ->find()->where(['Permissions.aco_foreign_key' => $resource->id])->toArray();
        $this->assertEmpty($permissions);
        $this->assertSame(2, PermissionFactory::count());
        // No secrets for this resource in db.
        $secrets = $this->Resources->getAssociation('Secrets')
            ->find()->where(['Secrets.resource_id' => $resource->id])->toArray();
        $this->assertEmpty($secrets);
        $this->assertSame(2, SecretFactory::count());
        // No secret revision for this resource in db.
        $secretRevisions = $this->Resources->SecretRevisions
            ->find()->where(['resource_id' => $resource->id])->toArray();
        $this->assertEmpty($secretRevisions);
        $this->assertSame(2, SecretRevisionFactory::count());
    }

    public function testSoftDeleteErrorNotValidUserIdParameter()
    {
        $userId = 'not-valid-uuid';
        /** @var \App\Model\Entity\User $resource */
        $resource = ResourceFactory::make()->persist();
        try {
            $this->Resources->softDelete($userId, $resource);
            $this->fail();
        } catch (InvalidArgumentException $e) {
            $this->assertSame('The user identifier should be a valid UUID.', $e->getMessage());
        }
    }

    public function testSoftDeleteErrorResourceIsSoftDeleted()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->deleted()
            ->withPermissionsFor([$user])
            ->persist();
        $this->Resources->softDelete($user->id, $resource);
        $errors = $resource->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['deleted']['is_not_soft_deleted']);
    }

    public function testSoftDeleteErrorAccessDenied()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();
        $this->Resources->softDelete($user->id, $resource);
        $errors = $resource->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['id']['has_access']);
    }

    public function testSoftDeleteErrorAccessDenied_ReadAccess()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user], Permission::READ)
            ->persist();
        $this->Resources->softDelete($user->id, $resource);
        $errors = $resource->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotEmpty($errors['id']['has_access']);
    }

    public function testSoftDeleteError_UpdateAccess_Success()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user], Permission::UPDATE)
            ->persist();
        $this->Resources->softDelete($user->id, $resource);

        // Check that the resource is well soft deleted.
        $resource = $this->Resources->get($resource->id);
        $this->assertTrue($resource->deleted);

        // Description, username and URI are empty
        $this->assertEmpty($resource->username);
        $this->assertEmpty($resource->uri);
        $this->assertEmpty($resource->description);
    }
}
