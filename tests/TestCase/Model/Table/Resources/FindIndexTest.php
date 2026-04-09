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
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\ORM\TableRegistry;
use InvalidArgumentException;

class FindIndexTest extends AppTestCase
{
    /**
     * @var ResourcesTable
     */
    public $Resources;

    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::getTableLocator()->get('Resources', $config);
    }

    public function testSuccess()
    {
        $user = UserFactory::make()->persist();
        ResourceFactory::make(2)->withPermissionsFor([$user])->persist();
        $resources = $this->Resources->findIndex($user->id)->all();
        $this->assertGreaterThan(1, count($resources));

        // Expected fields.
        $resource = $resources->first();
        $this->assertResourceAttributes($resource);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('secrets', $resource);
        $this->assertObjectNotHasAttribute('creator', $resource);
        $this->assertObjectNotHasAttribute('modifier', $resource);
        $this->assertObjectNotHasAttribute('favorite', $resource);
    }

    public function testExcludeSoftDeletedResources()
    {
        $user = UserFactory::make()->persist();
        $factory = ResourceFactory::make()->withCreatorAndPermission($user);

        $notDeletedResource = $factory->persist();
        $factory->setField('deleted', true)->persist();

        $resources = $this->Resources->findIndex($user->id);

        $this->assertSame(1, $resources->all()->count());
        $this->assertSame($notDeletedResource->id, $resources->firstOrFail()->id);
    }

    public function testContainSecrets()
    {
        $user = UserFactory::make()->persist();
        ResourceFactory::make()->withPermissionsFor([$user])->withSecretsFor([$user])->persist();
        $options['contain']['secret'] = true;
        $resources = $this->Resources->findIndex($user->id, $options)->all();
        $resource = $resources->first();

        // Expected fields.
        $this->assertResourceAttributes($resource);
        $this->assertObjectHasAttribute('secrets', $resource);
        $this->assertCount(1, $resource->secrets);
        $this->assertSecretAttributes($resource->secrets[0]);
    }

    public function testContainSecrets_WithSecretsDeleted()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->withSecretsFor([$user])
            ->persist();
        $secret = $resource->secrets[0];
        // Add five secrets associated to this resource, but deleted
        SecretFactory::make(5)
            ->with('Resources', $resource)
            ->with('Users', $user)
            ->deleted()
            ->persist();

        $options['contain']['secret'] = true;
        $resources = $this->Resources->findIndex($user->id, $options);
        $resource = $resources->first();

        // Expected fields.
        $this->assertResourceAttributes($resource);
        $this->assertObjectHasAttribute('secrets', $resource);
        $this->assertCount(1, $resource->secrets);
        $this->assertSame($secret->id, $resource->secrets[0]['id']);
    }

    public function testContainCreator()
    {
        $user = UserFactory::make()->persist();
        ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $options['contain']['creator'] = true;
        $resources = $this->Resources->findIndex($user->id, $options)->all();
        $resource = $resources->first();

        // Expected fields.
        $this->assertResourceAttributes($resource);
        $this->assertObjectHasAttribute('creator', $resource);
        $this->assertUserAttributes($resource->creator);
    }

    public function testContainModifier()
    {
        $user = UserFactory::make()->persist();
        ResourceFactory::make(['modified_by' => $user->id])->withPermissionsFor([$user])->persist();
        $options['contain']['modifier'] = true;
        $resources = $this->Resources->findIndex($user->id, $options)->all();
        $resource = $resources->first();

        // Expected fields.
        $this->assertResourceAttributes($resource);
        $this->assertObjectHasAttribute('modifier', $resource);
        $this->assertUserAttributes($resource->modifier);
    }

    public function testContainFavorite()
    {
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        FavoriteFactory::make()
            ->with('Resources', $resource)
            ->with('Users', $user)
            ->persist();

        $options['contain']['favorite'] = true;
        $resources = $this->Resources->findIndex($user->id, $options)->all();
        $resource = $resources->first();

        // Expected fields.
        $this->assertResourceAttributes($resource);
        $this->assertObjectHasAttribute('favorite', $resource);
        $this->assertFavoriteAttributes($resource->favorite);
    }

    public function testFilterIsFavorite()
    {
        $user = UserFactory::make()->persist();
        [$r1, $r2] = ResourceFactory::make(2)->withCreatorAndPermission($user)->persist();
        FavoriteFactory::make()->setResource($r1)->setUser($user)->persist();
        FavoriteFactory::make()->setResource($r2)->setUser($user)->persist();
        $options['filter']['is-favorite'] = true;
        $resources = $this->Resources->findIndex($user->id, $options)->all();

        // Check that the result contain only the expected favorite resources.
        $favoriteResourcesIds = $resources->reduce(function ($result, $row) {
            $result[] = $row->id;

            return $result;
        }, []);
        $expectedResources = [$r1->id, $r2->id];
        $this->assertEquals(0, count(array_diff($expectedResources, $favoriteResourcesIds)));
    }

    public function testFilterIsNotFavorite()
    {
        $user = UserFactory::make()->persist();
        [$r1, $r2] = ResourceFactory::make(2)->withCreatorAndPermission($user)->persist();
        FavoriteFactory::make()->setResource($r1)->setUser($user)->persist();
        FavoriteFactory::make()->setResource($r2)->setUser($user)->persist();
        $options['filter']['is-favorite'] = false;
        $resources = $this->Resources->findIndex($user->id, $options)->all();

        // Check that the result contain only the expected favorite resources.
        $favoriteResourcesIds = $resources->reduce(function ($result, $row) {
            $result[] = $row->id;

            return $result;
        }, []);
        $expectedResources = [$r1->id, $r2->id];
        $this->assertEquals(0, count(array_intersect($expectedResources, $favoriteResourcesIds)));
    }

    public function testFilterIsSharedWithGroup()
    {
        [$user, $otherUser, $groupUser] = UserFactory::make(3)->persist();
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$user])
            ->withGroupsUsersFor([$groupUser])
            ->persist();
        $group2 = GroupFactory::make()->withGroupsManagersFor([$groupUser])->persist();
        // Resources with Access
        $r1withAccess = ResourceFactory::make()
            ->withPermissionsFor([$group])->persist();
        $r2withAccess = ResourceFactory::make()
            ->withPermissionsFor([$group], Permission::READ)->persist();
        $r3withAccess = ResourceFactory::make()
            ->withPermissionsFor([$group], Permission::UPDATE)->persist();
        $r4withAccess = ResourceFactory::make()
            ->withPermissionsFor([$group, $user])->persist();
        $r5withAccess = ResourceFactory::make()
            ->withPermissionsFor([$group, $otherUser])->persist();

        // Resources with No Access
        ResourceFactory::make()->withPermissionsFor([$user])->persist();
        ResourceFactory::make()->withPermissionsFor([$otherUser])->persist();
        ResourceFactory::make()->withPermissionsFor([$group2])->persist();
        ResourceFactory::make()->withPermissionsFor([$user, $otherUser, $groupUser, $group2])->persist();
        ResourceFactory::make()->withPermissionsFor([$group])->setDeleted()->persist();

        // Filter resources which are shared with the target group;
        $options['filter']['is-shared-with-group'] = $group->id;
        $resourcesIds = $this->Resources->findIndex($user->id, $options)
            ->all()
            ->extract('id')
            ->toArray();
        sort($resourcesIds);

        // Extract the resource the group should have access.
        $expectedResourcesIds = [$r1withAccess->id, $r2withAccess->id, $r3withAccess->id, $r4withAccess->id, $r5withAccess->id];
        sort($expectedResourcesIds);

        $this->assertCount(count($expectedResourcesIds), $resourcesIds);
        $this->assertEmpty(array_diff($expectedResourcesIds, $resourcesIds));
    }

    public function testFilterIsOwnedByMe()
    {
        $user = UserFactory::make()->user()->persist();
        $otherUser = UserFactory::make()->user()->persist();

        $r1IsOwned = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $r2IsOwned = ResourceFactory::make()->withPermissionsFor([$user, $otherUser])->persist();
        ResourceFactory::make()->withPermissionsFor([$user], Permission::UPDATE)->persist();
        ResourceFactory::make()->withPermissionsFor([$user], Permission::READ)->persist();
        ResourceFactory::make()->withPermissionsFor([$otherUser])->persist();

        // Filter resources which are shared with the target group;
        $options['filter']['is-owned-by-me'] = 1;
        $resourcesIds = $this->Resources->findIndex($user->id, $options)
            ->all()
            ->extract('id')
            ->toArray();
        sort($resourcesIds);

        $expectedResourcesIds = [$r1IsOwned->id, $r2IsOwned->id];
        sort($expectedResourcesIds);

        $this->assertCount(count($expectedResourcesIds), $resourcesIds);
        $this->assertEmpty(array_diff($expectedResourcesIds, $resourcesIds));
    }

    public function testFilterIsSharedWithMe()
    {
        $user = UserFactory::make()->user()->persist();
        $owner = UserFactory::make()->user()->persist();

        $r1IsShared = ResourceFactory::make()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$user], Permission::READ)
            ->persist();
        $r2IsShared = ResourceFactory::make()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$user], Permission::UPDATE)
            ->persist();
        ResourceFactory::make()->withPermissionsFor([$user])->persist();
        ResourceFactory::make()->withPermissionsFor([$owner])->persist();

        // Filter resources which are shared with the target group;
        $options['filter']['is-shared-with-me'] = 1;
        $resourcesIds = $this->Resources->findIndex($user->id, $options)
            ->all()
            ->extract('id')
            ->toArray();
        sort($resourcesIds);

        // Get all resources with permissions.
        $expectedResourcesIds = [$r1IsShared->id, $r2IsShared->id];
        sort($expectedResourcesIds);

        $this->assertEquals($resourcesIds, $expectedResourcesIds);
    }

    public function testErrorInvalidUserIdParameter()
    {
        try {
            $this->Resources->findIndex('not-valid');
        } catch (InvalidArgumentException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }
}
