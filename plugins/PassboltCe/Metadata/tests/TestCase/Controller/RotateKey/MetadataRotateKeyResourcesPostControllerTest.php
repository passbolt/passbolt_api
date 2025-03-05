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

namespace Passbolt\Metadata\Test\TestCase\Controller\RotateKey;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @uses \Passbolt\Metadata\Controller\RotateKey\MetadataRotateKeyResourcesPostController
 */
class MetadataRotateKeyResourcesPostControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;
    use GpgMetadataKeysTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        ResourceTypeFactory::make()->default()->persist();
        ResourceTypeFactory::make()->passwordAndDescription()->persist();
    }

    public function testMetadataRotateKeyResourcesPostController_Success(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        $expiredResource1 = ResourceFactory::make()->withPermissionsFor([$admin])
            ->patchData([
                'metadata_key_id' => $expiredMetadataKey->get('id'),
                'metadata' => $this->encryptForMetadataKey(json_encode([])),
                'metadata_key_type' => 'shared_key',
                // Set V4 fields to null
                'name' => null,
                'username' => null,
                'uri' => null,
                'description' => null,
            ])->persist();
        // another user's resource returned
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($user->get('gpgkey'))->persist();
        $expiredResource2 = ResourceFactory::make()->withPermissionsFor([$user])->patchData([
            'metadata_key_id' => $expiredMetadataKey->get('id'),
            'metadata' => $this->encryptForMetadataKey(json_encode([])),
            'metadata_key_type' => 'shared_key',
            // Set V4 fields to null
            'name' => null,
            'username' => null,
            'uri' => null,
            'description' => null,
        ])->persist();
        // v4 resources
        ResourceFactory::make(2)->withPermissionsFor([$user])->persist();
        ResourceFactory::make(2)->withPermissionsFor([$admin])->persist();

        $this->logInAs($admin);
        $this->postJson('/metadata/rotate-key/resources.json', [
            [
                'id' => $expiredResource1->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $this->encryptForMetadataKey(json_encode([])), // todo: use valid v5 json metadata format
                'modified' => $expiredResource1->get('modified'),
                'modified_by' => $expiredResource1->get('modified_by'),
            ],
        ]);

        $this->assertSuccess();
        $updatedResource1 = ResourceFactory::get($expiredResource1->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedResource1->get('metadata_key_id'));
        $this->assertSame(MetadataKey::TYPE_SHARED_KEY, $updatedResource1->get('metadata_key_type'));
        $this->assertNotEmpty($updatedResource1->get('metadata'));
        // assert response
        $response = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($response);
        $this->assertCount(1, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 1,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
    }

    public function testMetadataRotateKeyResourcesPostController_Error_NotJson(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->logInAs($user);
        $this->post('/metadata/rotate-key/resources');
        $this->assertResponseCode(404);
    }

    public function testMetadataRotateKeyResourcesPostController_Error_NotLoggedIn(): void
    {
        $this->postJson('/metadata/rotate-key/resources.json', []);
        $this->assertResponseCode(401);
    }

    public function testMetadataRotateKeyResourcesPostController_Error_NotAdmin(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->logInAs($user);
        $this->postJson('/metadata/rotate-key/resources.json');
        $this->assertResponseCode(403);
    }

    public function testMetadataRotateKeyResourcesPostController_Error_EmptyData(): void
    {
        $admin = UserFactory::make()->admin()->active()->persist();
        $this->logInAs($admin);
        $this->postJson('/metadata/rotate-key/resources.json', []);
        $this->assertBadRequestError('The request data can not be empty');
    }
}
