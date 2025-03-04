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

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Core\Configure;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @uses \Passbolt\Metadata\Controller\Upgrade\MetadataUpgradeFoldersPostController
 */
class MetadataUpgradeFoldersPostControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    public function testMetadataUpgradeFoldersPostController_Success(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create metadata key
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        [$folder] = FolderFactory::make(2)->withPermissionsFor(UserFactory::make(2)->persist())->persist();

        Configure::write('passbolt.plugins.metadata.defaultPaginationLimit', 1);
        $this->logInAsAdmin();
        $this->postJson('/metadata/upgrade/folders.json?contain[permissions]=1', [
            [
                'id' => $folder->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForMetadataKey(json_encode([])), // todo: use valid v5 json metadata format
                'modified' => $folder->get('modified'),
                'modified_by' => $folder->get('modified_by'),
            ],
        ]);

        $this->assertSuccess();
        $updatedFolder = FolderFactory::get($folder->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedFolder->get('metadata_key_id'));
        $this->assertSame(MetadataKey::TYPE_SHARED_KEY, $updatedFolder->get('metadata_key_type'));
        $this->assertNotEmpty($updatedFolder->get('metadata'));
        // assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($response);
        $this->assertCount(1, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 1,
            'page' => 1,
            'limit' => 1,
        ], $headers['pagination']);
        // Assert that permissions are contained
        $this->assertArrayHasKey('permissions', $response[0]);
        $this->assertSame(2, count($response[0]['permissions']));
    }

    public function testMetadataUpgradeFoldersPostController_MetadataKey_Expired(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create metadata key
        $expiredMetadataKey = MetadataKeyFactory::make()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()
            ->withMetadataKey($expiredMetadataKey)
            ->withUserPrivateKey($admin->get('gpgkey'))
            ->persist();
        [$folder] = FolderFactory::make(2)->persist();

        $this->logInAsAdmin();
        $this->postJson('/metadata/upgrade/folders.json', [
            [
                'id' => $folder->get('id'),
                'metadata_key_id' => $expiredMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForMetadataKey(json_encode([])), // todo: use valid v5 json metadata format
                'modified' => $folder->get('modified'),
                'modified_by' => $folder->get('modified_by'),
            ],
        ]);
        // assert response
        $this->assertBadRequestError('The folder metadata key data could not be updated.');
        $response = $this->getResponseBodyAsArray();
        $this->assertSame([[
            'metadata_key_id' => [
                'isMetadataKeyNotExpired' => 'The metadata key is marked as expired.',
            ],
        ]], $response);
    }

    public function testMetadataUpgradeFoldersPostController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->post('/metadata/upgrade/folders');
        $this->assertResponseCode(404);
    }

    public function testMetadataUpgradeFoldersPostController_Error_NotLoggedIn(): void
    {
        $this->postJson('/metadata/upgrade/folders.json', []);
        $this->assertResponseCode(401);
    }

    public function testMetadataUpgradeFoldersPostController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->postJson('/metadata/upgrade/folders.json');
        $this->assertResponseCode(403);
    }

    public function testMetadataUpgradeFoldersPostController_Error_EmptyData(): void
    {
        $this->logInAsAdmin();
        $this->postJson('/metadata/upgrade/folders.json', []);
        $this->assertBadRequestError('The request data can not be empty');
    }
}
