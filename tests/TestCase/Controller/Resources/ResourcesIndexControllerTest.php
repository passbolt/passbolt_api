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

namespace App\Test\TestCase\Controller\Resources;

use App\Model\Entity\Permission;
use App\Test\Factory\FavoriteFactory;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\FavoritesModelTrait;
use Cake\I18n\DateTime;
use Cake\Utility\Hash;
use Passbolt\Folders\FoldersPlugin;

class ResourcesIndexControllerTest extends AppIntegrationTestCase
{
    use FavoritesModelTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(FoldersPlugin::class);
    }

    public function testResourcesIndexController_Success_WithFactories(): void
    {
        $user = $this->logInAsUser();
        ResourceFactory::make(3)->withPermissionsFor([$user], Permission::READ)->persist();
        ResourceFactory::make(3)->withPermissionsFor([$user], Permission::UPDATE)->persist();
        ResourceFactory::make(3)->withPermissionsFor([$user])->persist();
        $this->getJson('/resources.json?filter=[]');
        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        // Assert that the created date is in the right format
        $format = "yyyy-MM-dd'T'HH':'mm':'ssxxx";
        $created = $this->_responseJsonBody[0]->created;
        $createdParsed = \Cake\I18n\DateTime::parse($this->_responseJsonBody[0]->created)->i18nFormat($format);
        $this->assertSame($createdParsed, $created, "The created date $created is not in $format format");
        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody[0]);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('secrets', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('creator', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('modifier', $this->_responseJsonBody[0]);
        $this->assertObjectNotHasAttribute('favorite', $this->_responseJsonBody[0]);
    }

    public function testResourcesIndexController_Success_WithContain_WithFactories(): void
    {
        $user = $this->logInAsUser();
        ResourceFactory::make()->withPermissionsFor([$user], Permission::READ)
            ->withSecretsFor([$user])
            ->withCreator(UserFactory::make())
            ->with('Modifier')
            ->persist();
        ResourceFactory::make(3)->withPermissionsFor([$user], Permission::UPDATE)
            ->withSecretsFor([$user])
            ->withCreator(UserFactory::make())
            ->with('Modifier')
            ->persist();
        $favoriteResource = ResourceFactory::make()->withPermissionsFor([$user])
            ->withSecretsFor([$user])
            ->withCreator(UserFactory::make())
            ->with('Modifier')
            ->with('Favorites', FavoriteFactory::make()->setUser($user))
            ->persist();

        $urlParameter = 'contain[creator]=1&contain[favorite]=1&contain[modifier]=1&contain[permission]=1';
        $urlParameter .= '&contain[permissions.user.profile]=1&contain[permissions.group]=1&contain[secret]=1';
        $this->getJson("/resources.json?$urlParameter");
        $this->assertSuccess();
        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody[0]);
        // Contain creator.
        $this->assertObjectHasAttribute('creator', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->creator);
        // Contain modifier.
        $this->assertObjectHasAttribute('modifier', $this->_responseJsonBody[0]);
        $this->assertUserAttributes($this->_responseJsonBody[0]->modifier);
        // Contain permission.
        $this->assertObjectHasAttribute('permission', $this->_responseJsonBody[0]);
        $this->assertPermissionAttributes($this->_responseJsonBody[0]->permission);
        // Contain permission user.
        $this->assertObjectHasAttribute('permissions', $this->_responseJsonBody[0]);
        foreach ($this->_responseJsonBody[0]->permissions as $permission) {
            $this->assertPermissionAttributes($permission);
            if ($permission->user) {
                $this->assertUserAttributes($permission->user);
            } else {
                $this->assertGroupAttributes($permission->group);
            }
        }
        // Contain secret.
        $this->assertObjectHasAttribute('secrets', $this->_responseJsonBody[0]);
        $this->assertCount(1, $this->_responseJsonBody[0]->secrets);
        $this->assertSecretAttributes($this->_responseJsonBody[0]->secrets[0]);
        // Contain favorite.
        $this->assertObjectHasAttribute('favorite', $this->_responseJsonBody[0]);
        // A resource marked as favorite contains the favorite data.
        $favoriteResourceId = $favoriteResource->id;
        $favoriteResource = current(array_filter($this->_responseJsonBody, function ($resource) use ($favoriteResourceId) {
            return $resource->id == $favoriteResourceId;
        }));
        $this->assertObjectHasAttribute('favorite', $favoriteResource);
        $this->assertFavoriteAttributes($favoriteResource->favorite);
    }

    public function testResourcesIndexController_Success_FilterIsFavorite_WithFactories(): void
    {
        $user = $this->logInAsUser();
        [$resourceA, $resourceB] = ResourceFactory::make(2)->withPermissionsFor([$user], Permission::READ)
            ->with('Favorites', FavoriteFactory::make()->setUser($user))
            ->persist();
        $urlParameter = 'filter[is-favorite]=1';
        $this->getJson("/resources.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $this->assertCount(2, $this->_responseJsonBody);

        // Check that the result contain only the expected favorite resources.
        $favoriteResourcesIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $expectedResources = [$resourceA->id, $resourceB->id];
        $this->assertEquals(0, count(array_diff($expectedResources, $favoriteResourcesIds)));

        // Expected fields.
        $this->assertResourceAttributes($this->_responseJsonBody[0]);

        // Favorite field shouldn't be present by default even when filtering by favorite.
        $this->assertObjectNotHasAttribute('favorite', $this->_responseJsonBody[0]);
    }

    public function testResourcesIndexController_Success_FilterIsSharedWithGroup_WithFactories(): void
    {
        $user = $this->logInAsUser();
        $user2 = UserFactory::make()->user()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $resourceA = ResourceFactory::make()->withPermissionsFor([$group], Permission::READ)->persist();
        $resourceB = ResourceFactory::make()->withPermissionsFor([$group], Permission::UPDATE)->persist();
        $resourceC = ResourceFactory::make()->withPermissionsFor([$group])->persist();
        ResourceFactory::make()->withPermissionsFor([$user2])->persist();
        $groupId = $group->id;
        $urlParameter = "filter[is-shared-with-group]=$groupId";
        $this->getJson("/resources.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $resourcesIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        sort($resourcesIds);

        $expectedResourcesIds = [$resourceA->id, $resourceB->id, $resourceC->id];
        sort($expectedResourcesIds);

        $this->assertCount(count($expectedResourcesIds), $resourcesIds);
        $this->assertEmpty(array_diff($expectedResourcesIds, $resourcesIds));
    }

    public function testResourcesIndexController_Success_FilterIsSharedWithMe_WithFactories(): void
    {
        $user = $this->logInAsUser();
        $user2 = UserFactory::make()->user()->persist();
        $resourceA = ResourceFactory::make()->withPermissionsFor([$user], Permission::READ)->persist();
        $resourceB = ResourceFactory::make()->withPermissionsFor([$user], Permission::UPDATE)->persist();
        ResourceFactory::make()->withPermissionsFor([$user, $user2])->persist();
        ResourceFactory::make()->persist();
        $urlParameter = 'filter[is-shared-with-me]=1';
        $this->getJson("/resources.json?$urlParameter&api-version=2");
        $this->assertSuccess();
        $resourcesIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        sort($resourcesIds);

        // Get all resources Ids
        $expectedResourcesIds = [$resourceA->id, $resourceB->id];
        sort($expectedResourcesIds);

        $this->assertEquals($expectedResourcesIds, $resourcesIds);
    }

    public function testResourcesIndexController_Success_FilterHasId_WithFactories(): void
    {
        $user = $this->logInAsUser();
        $resourceA = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $resourceB = ResourceFactory::make()->withPermissionsFor([$user], Permission::READ)->persist();
        ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $resourceAId = $resourceA->id;
        $resourceBId = $resourceB->id;
        $urlParameter = "filter[has-id][]=$resourceAId&filter[has-id][]=$resourceBId";
        $this->getJson("/resources.json?$urlParameter&api-version=2");
        $this->assertSuccess();

        $this->assertCount(2, $this->_responseJsonBody);
        $resourcesIds = Hash::extract($this->_responseJsonBody, '{n}.id');
        $this->assertContains($resourceAId, $resourcesIds);
        $this->assertContains($resourceBId, $resourcesIds);
    }

    public function testResourcesIndexController_Error_NotAuthenticated_WithFactories(): void
    {
        $this->getJson('/resources.json');
        $this->assertAuthenticationError();
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testResourcesIndexController_Error_NotJson_WithFactories(): void
    {
        $this->logInAsUser();
        $this->get('/resources');
        $this->assertResponseCode(404);
    }

    public function testResourcesIndexController_InvalidFilterSharedWithGroup(): void
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?filter[is-shared-with-group]=1');
        $this->assertBadRequestError('Invalid filter');
    }
}
