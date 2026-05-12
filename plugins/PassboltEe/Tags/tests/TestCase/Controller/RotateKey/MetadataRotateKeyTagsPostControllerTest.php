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

namespace Passbolt\Tags\Test\TestCase\Controller\RotateKey;

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Tags\Model\Dto\MetadataTagDto;
use Passbolt\Tags\TagsPlugin;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @uses \Passbolt\Tags\Controller\RotateKey\MetadataRotateKeyTagsPostController
 */
class MetadataRotateKeyTagsPostControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;
    use GpgMetadataKeysTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(TagsPlugin::class);
    }

    public function testMetadataRotateKeyTagsPostController_Success(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create metadata keys
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $expiredMetadataKey */
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        // expired tag
        $metadata = json_encode(MetadataTagDto::fromArray(['name' => 'marketing'])->getClearTextMetadata());
        $expiredTag1 = TagFactory::make()
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();
        // another user's tag
        $metadata = json_encode(MetadataTagDto::fromArray(['name' => 'test tag'])->getClearTextMetadata());
        TagFactory::make(5)
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();

        $this->logInAs($admin);
        $metadataToUpdate = $this->encryptForMetadataKey(json_encode(MetadataTagDto::fromArray(['slug' => 'my-tag'])->getClearTextMetadata()));
        $this->postJson('/metadata/rotate-key/tags.json', [
            [
                'id' => $expiredTag1->get('id'),
                'metadata_key_id' => $activeMetadataKey->get('id'),
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadataToUpdate,
            ],
        ]);

        $this->assertSuccess();
        $updatedTag1 = TagFactory::get($expiredTag1->get('id'));
        $this->assertSame($activeMetadataKey->get('id'), $updatedTag1->get('metadata_key_id'));
        $this->assertSame(MetadataKey::TYPE_SHARED_KEY, $updatedTag1->get('metadata_key_type'));
        $this->assertNotEmpty($updatedTag1->get('metadata'));
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

    public function testMetadataRotateKeyTagsPostController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->post('/metadata/rotate-key/tags');
        $this->assertResponseCode(404);
    }

    public function testMetadataRotateKeyTagsPostController_Error_NotLoggedIn(): void
    {
        $this->postJson('/metadata/rotate-key/tags.json', []);
        $this->assertResponseCode(401);
    }

    public function testMetadataRotateKeyTagsPostController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->postJson('/metadata/rotate-key/tags.json');
        $this->assertResponseCode(403);
    }

    public function testMetadataRotateKeyTagsPostController_Error_EmptyData(): void
    {
        $this->logInAsAdmin();
        $this->postJson('/metadata/rotate-key/tags.json', []);
        $this->assertBadRequestError('The request data can not be empty');
    }
}
