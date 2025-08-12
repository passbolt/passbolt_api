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

namespace Passbolt\Metadata\Test\TestCase\Controller\RotateKey;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Metadata\Model\Dto\MetadataFolderDto;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @uses \Passbolt\Metadata\Controller\RotateKey\MetadataRotateKeyFoldersPostController
 */
class MetadataRotateKeyFoldersPostControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;
    use GpgMetadataKeysTestTrait;

    public function testMetadataRotateKeyFoldersPostController_Success(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create metadata keys
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $activeMetadataKey */
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $expiredMetadataKey */
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        // expired folder
        $metadata = json_encode(MetadataFolderDto::fromArray(['name' => 'marketing'])->getClearTextMetadata());
        $expiredFolder1 = FolderFactory::make()
            ->withPermissionsFor([$admin])
            ->withFoldersRelationsFor([$admin])
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();
        // another user's folder
        $user = UserFactory::make()->user()->active()->persist();
        $metadata = json_encode(MetadataFolderDto::fromArray(['name' => 'test folder'])->getClearTextMetadata());
        FolderFactory::make(5)
            ->withPermissionsFor([$user])
            ->withFoldersRelationsFor([$user])
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();

        $this->logInAs($admin);
        $metadataToUpdate = $this->encryptForMetadataKey(json_encode(MetadataFolderDto::fromArray(['name' => 'marketing updated'])->getClearTextMetadata()));
        $this->postJson('/metadata/rotate-key/folders.json', [
            [
                'id' => $expiredFolder1->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataToUpdate,
                'modified' => $expiredFolder1->get('modified'),
                'modified_by' => $expiredFolder1->get('modified_by'),
            ],
        ]);

        $this->assertSuccess();
        $updatedFolder1 = FolderFactory::get($expiredFolder1->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedFolder1->get('metadata_key_id'));
        $this->assertSame(MetadataKey::TYPE_SHARED_KEY, $updatedFolder1->get('metadata_key_type'));
        $this->assertNotEmpty($updatedFolder1->get('metadata'));
        // assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($response);
        $this->assertCount(5, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 5,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
    }

    public function testMetadataRotateKeyFoldersPostController_Error_NotJson(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->logInAs($user);
        $this->post('/metadata/rotate-key/folders');
        $this->assertResponseCode(404);
    }

    public function testMetadataRotateKeyFoldersPostController_Error_NotLoggedIn(): void
    {
        $this->postJson('/metadata/rotate-key/folders.json', []);
        $this->assertResponseCode(401);
    }

    public function testMetadataRotateKeyFoldersPostController_Error_NotAdmin(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->logInAs($user);
        $this->postJson('/metadata/rotate-key/folders.json');
        $this->assertResponseCode(403);
    }

    public function testMetadataRotateKeyFoldersPostController_Error_EmptyData(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $this->logInAs($admin);
        $this->postJson('/metadata/rotate-key/folders.json', []);
        $this->assertBadRequestError('The request data can not be empty');
    }
}
