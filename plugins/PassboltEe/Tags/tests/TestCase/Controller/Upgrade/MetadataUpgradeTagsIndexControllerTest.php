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
 * @since         5.1.0
 */

namespace Passbolt\Tags\Test\TestCase\Controller\Upgrade;

use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Core\Configure;
use Passbolt\Tags\TagsPlugin;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @uses \Passbolt\Metadata\Controller\Upgrade\MetadataUpgradeTagsIndexController
 */
class MetadataUpgradeTagsIndexControllerTest extends AppIntegrationTestCaseV5
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(TagsPlugin::class);
    }

    public function testMetadataUpgradeTagsIndexController_Success_Pagination_Test(): void
    {
        // V4 resources
        $nV4Tags = 3;
        ResourcesTagFactory::make($nV4Tags)
            ->with('Users')
            ->with('Tags')
            ->persist();

        // V5 resource
        TagFactory::make()->v5Fields(['metadata' => 'foo'])->persist();

        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/tags.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount($nV4Tags, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 3,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
    }

    public function testMetadataUpgradeTagsIndexController_Success_Is_Shared(): void
    {
        $resourceTagShared = ResourcesTagFactory::make()
            ->with('Tags', TagFactory::make()->isShared())
            ->persist();
        ResourcesTagFactory::make()
            ->with('Users')
            ->with('Tags', TagFactory::make())
            ->persist();

        $sharedTag = $resourceTagShared->get('tag');

        // V5 tag to be ignored
        TagFactory::make()->v5Fields(['metadata' => 'foo'])->persist();
        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/tags.json?filter[is-shared]=1');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertSame($response, [
            [
                'id' => $sharedTag->id,
                'slug' => $sharedTag->slug,
                'is_shared' => true,
                'user_id' => null,
            ],
        ]);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 1,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
    }

    public function testMetadataUpgradeTagsIndexController_Success_Pagination_Sorting_Is_Not_Allowed(): void
    {
        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/tags.json?page=2&limit=2&sort[Tags.id]=desc');
        $this->assertBadRequestError('Invalid order. "Tags.id" is not in the list of allowed order.');
    }

    public function testMetadataUpgradeTagsIndexController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->get('/metadata/upgrade/tags');
        $this->assertResponseCode(404);
    }

    public function testMetadataUpgradeTagsIndexController_Error_NotLoggedIn(): void
    {
        $this->getJson('/metadata/upgrade/tags.json');
        $this->assertResponseCode(401);
    }

    public function testMetadataUpgradeTagsIndexController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->getJson('/metadata/upgrade/tags.json');
        $this->assertResponseCode(403);
    }

    public function testMetadataUpgradeTagsIndexController_Error_InvalidConfigValue(): void
    {
        Configure::write('passbolt.plugins.metadata.defaultPaginationLimit', '🔥');
        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/tags.json');

        $this->assertInternalError('Invalid pagination limit set for metadata endpoint');
    }
}
