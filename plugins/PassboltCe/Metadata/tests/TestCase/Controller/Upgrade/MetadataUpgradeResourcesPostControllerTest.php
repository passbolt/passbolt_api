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

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Core\Configure;
use Passbolt\Metadata\Model\Dto\MetadataFolderDto;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @uses \Passbolt\Metadata\Controller\Upgrade\MetadataUpgradeResourcesPostController
 */
class MetadataUpgradeResourcesPostControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        ResourceTypeFactory::make()->default()->persist();
        ResourceTypeFactory::make()->passwordAndDescription()->persist();
        ResourceTypeFactory::make()->v5Default()->persist();
        ResourceTypeFactory::make()->v5PasswordString()->persist();
    }

    public function testMetadataUpgradeResourcesPostController_Success(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create metadata key
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        [$resource] = ResourceFactory::make(2)->persist();

        Configure::write('passbolt.plugins.metadata.defaultPaginationLimit', 1);
        $this->logInAsAdmin();
        $this->postJson('/metadata/upgrade/resources.json?contain[permissions]=1', [
            [
                'id' => $resource->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForMetadataKey(json_encode([])), // todo: use valid v5 json metadata format
                'modified' => $resource->get('modified'),
                'modified_by' => $resource->get('modified_by'),
            ],
        ]);

        $this->assertSuccess();
        $updatedResource = ResourceFactory::get($resource->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedResource->get('metadata_key_id'));
        $this->assertSame(MetadataKey::TYPE_SHARED_KEY, $updatedResource->get('metadata_key_type'));
        $this->assertNotEmpty($updatedResource->get('metadata'));
        $v5ResourceType = ResourceTypeFactory::find()->where(['slug' => ResourceType::SLUG_V5_DEFAULT])->firstOrFail();
        $this->assertSame($v5ResourceType->get('id'), $updatedResource->get('resource_type_id'));
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
        // Assert that permissions are not contained
        $this->assertArrayHasKey('permissions', $response[0]);
    }

    public function testMetadataUpgradeResourcesPostController_Success_Personal_Key_Missing(): void
    {
        [$user1, $user2] = UserFactory::make(2)
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->persist();
        $resource1 = ResourceFactory::make()->withPermissionsFor([$user1])->persist();
        $resource2 = ResourceFactory::make()->withPermissionsFor([$user2])->persist();
        $dto = MetadataFolderDto::fromArray($resource1->toArray());
        $metadataArray = $dto->getClearTextMetadata();
        $metadata1 = $this->encryptForUser(json_encode($metadataArray), $user1, $this->getAdaNoPassphraseKeyInfo());
        $dto = MetadataFolderDto::fromArray($resource1->toArray());
        $metadataArray = $dto->getClearTextMetadata();
        $metadata2 = $this->encryptForUser(json_encode($metadataArray), $user2, $this->getAdaNoPassphraseKeyInfo());

        $this->logInAsAdmin();
        $this->postJson('/metadata/upgrade/resources.json?contain[permissions]=1', [
            [
                'id' => $resource1->get('id'),
                'metadata_key_id' => null,
                'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
                'metadata' => $metadata1,
                'modified' => $resource1->get('modified'),
                'modified_by' => $resource1->get('modified_by'),
            ],
            [
                'id' => $resource2->get('id'),
                'metadata_key_id' => null,
                'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
                'metadata' => $metadata2,
                'modified' => $resource2->get('modified'),
                'modified_by' => $resource2->get('modified_by'),
            ],
        ]);

        $this->assertSuccess();
        $updatedResource1 = ResourceFactory::get($resource1->get('id'));
        $updatedResource2 = ResourceFactory::get($resource2->get('id'));
        $this->assertSame($user1->gpgkey->id, $updatedResource1->metadata_key_id);
        $this->assertSame($user2->gpgkey->id, $updatedResource2->metadata_key_id);
    }

    public function testMetadataUpgradeResourcesPostController_MetadataKey_Expired(): void
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
        [$resource] = ResourceFactory::make(2)->persist();

        $this->logInAsAdmin();
        $this->postJson('/metadata/upgrade/resources.json', [
            [
                'id' => $resource->get('id'),
                'metadata_key_id' => $expiredMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForMetadataKey(json_encode([])), // todo: use valid v5 json metadata format
                'modified' => $resource->get('modified'),
                'modified_by' => $resource->get('modified_by'),
            ],
        ]);
        // assert response
        $this->assertBadRequestError('The resource metadata key data could not be updated.');
        $response = $this->getResponseBodyAsArray();
        $this->assertSame([[
            'metadata_key_id' => [
                'isMetadataKeyNotExpired' => 'The metadata key is marked as expired.',
            ],
        ]], $response);
    }

    public function testMetadataUpgradeResourcesPostController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->post('/metadata/upgrade/resources');
        $this->assertResponseCode(404);
    }

    public function testMetadataUpgradeResourcesPostController_Error_NotLoggedIn(): void
    {
        $this->postJson('/metadata/upgrade/resources.json', []);
        $this->assertResponseCode(401);
    }

    public function testMetadataUpgradeResourcesPostController_Error_NotAdmin(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->logInAs($user);
        $this->postJson('/metadata/upgrade/resources.json');
        $this->assertResponseCode(403);
    }

    public function testMetadataUpgradeResourcesPostController_Error_EmptyData(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $this->logInAs($admin);
        $this->postJson('/metadata/upgrade/resources.json', []);
        $this->assertBadRequestError('The request data can not be empty');
    }
}
