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
 * @since         4.12.0
 */

namespace Passbolt\Metadata\Test\TestCase\Controller\Upgrade;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Core\Configure;
use Passbolt\Folders\Test\Factory\FolderFactory;

/**
 * @uses \Passbolt\Metadata\Controller\Upgrade\MetadataUpgradeFoldersIndexController
 */
class MetadataUpgradeFoldersIndexControllerTest extends AppIntegrationTestCaseV5
{
    public function testMetadataUpgradeFoldersIndexController_Success_Pagination(): void
    {
        // V4 folders
        $nV4Folders = 3;
        FolderFactory::make($nV4Folders)->persist();

        // V5 folders
        FolderFactory::make()->v5Fields(['metadata' => 'foo'])->persist();

        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/folders.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount($nV4Folders, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 3,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
    }

    public function testMetadataUpgradeFoldersIndexController_Success_Filter_Is_Shared(): void
    {
        // V4 folders
        $nV4SharedFolders = 3;
        // Shared with two users
        FolderFactory::make()->withPermissionsFor(UserFactory::make(2)->persist())->persist();
        // Shared with a group
        FolderFactory::make()->withPermissionsFor([GroupFactory::make()->persist()])->persist();
        // Shared with a group and a user
        FolderFactory::make()
            ->withPermissionsFor([
                UserFactory::make()->persist(),
                GroupFactory::make()->persist(),
            ])->persist();
        // Not shared
        FolderFactory::make()->withPermissionsFor([UserFactory::make()->persist()])->persist();

        // V5 folder
        FolderFactory::make()->v5Fields(['metadata' => 'foo'])->persist();

        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/folders.json?filter[is-shared]=1');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount($nV4SharedFolders, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 3,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
    }

    public function testMetadataUpgradeFoldersIndexController_Success_Filter_Is_Not_Shared(): void
    {
        // V4 folders
        $nV4SharedFolders = 3;
        FolderFactory::make($nV4SharedFolders)->withPermissionsFor(UserFactory::make(2)->persist())->persist();
        FolderFactory::make(3)
            ->withPermissionsFor(UserFactory::make(2)->persist())
            ->persist();
        FolderFactory::make(3)
            ->withPermissionsFor(GroupFactory::make(2)->persist())
            ->persist();
        // Resource shared with one user only
        FolderFactory::make()->withPermissionsFor([UserFactory::make()->persist()])->persist();

        // V5 folder
        FolderFactory::make()->v5Fields(['metadata' => 'foo'])->withPermissionsFor([UserFactory::make()->persist()])->persist();

        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/folders.json?filter[is-shared]=0');

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

    public function testMetadataUpgradeFoldersIndexController_Success_Pagination_Sort_By_ID_Desc(): void
    {
        // V4 folders
        /** @var array $folders */
        $folders = FolderFactory::make([
            ['id' => '300a2f02-8111-485f-a62c-114cd6306435'],
            ['id' => '200a2f02-8111-485f-a62c-114cd6306435'],
            ['id' => '100a2f02-8111-485f-a62c-114cd6306435'],
        ])->persist();

        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/folders.json?page=2&limit=2');

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
        $this->assertEquals($folders[2]['id'], $response[0]['id']);
        $this->assertEquals($folders[1]['id'], $response[1]['id']);
        $this->assertEquals($folders[0]['id'], $response[2]['id']);
    }

    public function testMetadataUpgradeFoldersIndexController_Success_Pagination_Sorting_Is_Not_Allowed(): void
    {
        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/folders.json?page=2&limit=2&sort[Folders.id]=desc');
        $this->assertBadRequestError('Invalid order. "Folders.id" is not in the list of allowed order.');
    }

    public function testMetadataUpgradeFoldersIndexController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->get('/metadata/upgrade/folders');
        $this->assertResponseCode(404);
    }

    public function testMetadataUpgradeFoldersIndexController_Error_NotLoggedIn(): void
    {
        $this->getJson('/metadata/upgrade/folders.json');
        $this->assertResponseCode(401);
    }

    public function testMetadataUpgradeFoldersIndexController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->getJson('/metadata/upgrade/folders.json');
        $this->assertResponseCode(403);
    }

    public function testMetadataUpgradeFoldersIndexController_Error_InvalidConfigValue(): void
    {
        Configure::write('passbolt.plugins.metadata.defaultPaginationLimit', '🔥');
        $this->logInAsAdmin();
        $this->getJson('/metadata/upgrade/folders.json');

        $this->assertInternalError('Invalid pagination limit set for metadata endpoint');
    }
}
