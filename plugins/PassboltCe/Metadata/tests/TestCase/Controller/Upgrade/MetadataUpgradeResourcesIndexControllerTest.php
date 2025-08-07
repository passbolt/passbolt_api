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
 * @since         4.11.0
 */

namespace Passbolt\Metadata\Test\TestCase\Controller\Upgrade;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Core\Configure;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @uses \Passbolt\Metadata\Controller\Upgrade\MetadataUpgradeResourcesIndexController
 */
class MetadataUpgradeResourcesIndexControllerTest extends AppIntegrationTestCaseV5
{
    public function testMetadataUpgradeResourcesIndexController_Success_Pagination(): void
    {
        // V4 resources
        $nV4Resources = 3;
        ResourceFactory::make($nV4Resources)->persist();
        // Add a v4 resource of a deleted resource type.
        // Resources of deleted resource types should be ignored.
        ResourceFactory::make()
            ->with(
                'ResourceTypes',
                ResourceTypeFactory::make()->deleted()
            )->persist();

        // V5 resource
        ResourceFactory::make()->v5Fields()->persist();

        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/resources.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount($nV4Resources, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 3,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
    }

    public function testMetadataUpgradeResourcesIndexController_Success_Is_Shared(): void
    {
        // V4 resources
        $nV4SharedResources = 3;
        ResourceFactory::make($nV4SharedResources)->withPermissionsFor(UserFactory::make(2)->persist())->persist();
        ResourceFactory::make(3)->withPermissionsFor(UserFactory::make(2)->persist())
            ->deleted()
            ->persist();
        ResourceFactory::make()->withPermissionsFor([UserFactory::make()->persist()])->persist();

        // V5 resource
        ResourceFactory::make()->v5Fields()->persist();

        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/resources.json?filter[is-shared]=1');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount($nV4SharedResources, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 3,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
        // Assert that permissions are not contained
        $this->assertArrayNotHasKey('permissions', $response[0]);
    }

    public function testMetadataUpgradeResourcesIndexController_Contain_Permissions(): void
    {
        // V4 resources
        $nV4SharedResources = 3;
        ResourceFactory::make($nV4SharedResources)->withPermissionsFor(UserFactory::make(2)->persist())->persist();
        ResourceFactory::make(3)->withPermissionsFor(UserFactory::make(2)->persist())
            ->deleted()
            ->persist();
        ResourceFactory::make()->withPermissionsFor([UserFactory::make()->persist()])->persist();

        // V5 resource
        ResourceFactory::make()->v5Fields()->persist();

        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/resources.json?filter[is-shared]=1&contain[permissions]=1');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount($nV4SharedResources, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 3,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
        // Assert that permissions are contained
        $this->assertArrayHasKey('permissions', $response[0]);
    }

    public function testMetadataUpgradeResourcesIndexController_Success_Is_Not_Shared(): void
    {
        // V4 resources
        $nV4SharedResources = 3;
        ResourceFactory::make($nV4SharedResources)->withPermissionsFor(UserFactory::make(2)->persist())->persist();
        ResourceFactory::make(3)->withPermissionsFor(UserFactory::make(2)->persist())
            ->deleted()
            ->persist();
        // Resource shared with one user only
        ResourceFactory::make()->withPermissionsFor([UserFactory::make()->persist()])->persist();

        // V5 resource
        ResourceFactory::make()->v5Fields()->withPermissionsFor([UserFactory::make()->persist()])->persist();

        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/resources.json?filter[is-shared]=0');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 1,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
    }

    public function testMetadataUpgradeResourcesIndexController_Success_Pagination_Sort_By_ID_Desc(): void
    {
        // V4 resources
        /** @var array $resources */
        $resources = ResourceFactory::make([
            ['id' => '300a2f02-8111-485f-a62c-114cd6306435'],
            ['id' => '200a2f02-8111-485f-a62c-114cd6306435'],
            ['id' => '100a2f02-8111-485f-a62c-114cd6306435'],
        ])->persist();

        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/resources.json?page=2&limit=2');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(3, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 3,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
        // Assert that
        $this->assertEquals($resources[2]['id'], $response[0]['id']);
        $this->assertEquals($resources[1]['id'], $response[1]['id']);
        $this->assertEquals($resources[0]['id'], $response[2]['id']);
    }

    public function testMetadataUpgradeResourcesIndexController_Success_Pagination_Sorting_Is_Not_Allowed(): void
    {
        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/resources.json?page=2&limit=2&sort[Resources.id]=desc');
        $this->assertBadRequestError('Invalid order. "Resources.id" is not in the list of allowed order.');
    }

    public function testMetadataUpgradeResourcesIndexController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->get('/metadata/upgrade/resources');
        $this->assertResponseCode(404);
    }

    public function testMetadataUpgradeResourcesIndexController_Error_NotLoggedIn(): void
    {
        $this->getJson('/metadata/upgrade/resources.json');
        $this->assertResponseCode(401);
    }

    public function testMetadataUpgradeResourcesIndexController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->getJson('/metadata/upgrade/resources.json');
        $this->assertResponseCode(403);
    }

    public function testMetadataUpgradeResourcesIndexController_Error_InvalidConfigValue(): void
    {
        Configure::write('passbolt.plugins.metadata.defaultPaginationLimit', '🔥');
        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/resources.json');

        $this->assertInternalError('Invalid pagination limit set for metadata endpoint');
    }
}
