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

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Core\Configure;
use Passbolt\Metadata\Model\Entity\MetadataKey;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Tags\Model\Dto\MetadataTagDto;
use Passbolt\Tags\TagsPlugin;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @covers  \Passbolt\Tags\Controller\Upgrade\MetadataUpgradeTagsPostController
 */
class MetadataUpgradeTagsPostControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(TagsPlugin::class);
    }

    public function testMetadataUpgradeTagsPostController_Personal_Success(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())->persist();
        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag[] $resourcesTags */
        $resourcesTags = ResourcesTagFactory::make(2)->with('Tags')->with('Users', $user)->persist();
        $tag1 = $resourcesTags[0]->tag;
        $tag2OwnerId = $resourcesTags[1]->user_id;

        Configure::write('passbolt.plugins.metadata.defaultPaginationLimit', 1);
        $this->logInAsAdmin();
        $this->postJson('/metadata/upgrade/tags.json', [
            [
                'id' => $tag1->get('id'),
                'metadata_key_id' => $user->gpgkey->id,
                'metadata_key_type' => MetadataKey::TYPE_USER_KEY,
                'metadata' => $this->encryptForUser($tag1->slug, $user, $this->getAdaNoPassphraseKeyInfo()),
            ],
        ]);

        $this->assertSuccess();
        $updatedTag = TagFactory::get($tag1->get('id'));
        $this->assertSame($user->gpgkey->id, $updatedTag->get('metadata_key_id'));
        $this->assertSame(MetadataKey::TYPE_USER_KEY, $updatedTag->get('metadata_key_type'));
        $this->assertNotEmpty($updatedTag->get('metadata'));
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
        // Assert that the user_id is contained
        $this->assertSame($tag2OwnerId, $response[0]['user_id']);
    }

    public function testMetadataUpgradeTagsPostController_MetadataKey_Expired(): void
    {
        // create metadata key
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $expiredMetadataKey */
        $expiredMetadataKey = MetadataKeyFactory::make()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()
            ->withMetadataKey($expiredMetadataKey)
            ->withUserPrivateKey(GpgkeyFactory::make()->withAdaKey()->getEntity())
            ->persist();
        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag[] $resourcesTags */
        $resourcesTags = ResourcesTagFactory::make(2)->with('Tags')->with('Users')->persist();
        $tag1 = $resourcesTags[0]->tag;

        $tagDto = MetadataTagDto::fromArray($tag1->toArray());
        $clearTextMetadata = json_encode($tagDto->getClearTextMetadata());
        $metadata = $this->encryptForMetadataKey($clearTextMetadata);

        $this->logInAsAdmin();
        $this->postJson('/metadata/upgrade/tags.json', [
            [
                'id' => $tag1->get('id'),
                'metadata_key_id' => $expiredMetadataKey->id,
                'metadata_key_type' => MetadataKey::TYPE_SHARED_KEY,
                'metadata' => $metadata,
            ],
        ]);
        // assert response
        $this->assertBadRequestError('The tag metadata key data could not be updated.');
        $response = $this->getResponseBodyAsArray();
        $this->assertSame([[
            'metadata_key_id' => [
                'isMetadataKeyNotExpired' => 'The metadata key is marked as expired.',
            ],
        ]], $response);
    }

    public function testMetadataUpgradeTagsPostController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $this->post('/metadata/upgrade/tags');
        $this->assertResponseCode(404);
    }

    public function testMetadataUpgradeTagsPostController_Error_NotLoggedIn(): void
    {
        $this->postJson('/metadata/upgrade/tags.json', []);
        $this->assertResponseCode(401);
    }

    public function testMetadataUpgradeTagsPostController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->postJson('/metadata/upgrade/tags.json');
        $this->assertResponseCode(403);
    }

    public function testMetadataUpgradeTagsPostController_Error_EmptyData(): void
    {
        $this->logInAsAdmin();
        $this->postJson('/metadata/upgrade/tags.json', []);
        $this->assertBadRequestError('The request data can not be empty');
    }
}
