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
use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\Tags\Model\Dto\MetadataTagDto;
use Passbolt\Tags\TagsPlugin;
use Passbolt\Tags\Test\Factory\TagFactory;

/**
 * @uses \Passbolt\Tags\Controller\RotateKey\MetadataRotateKeyTagsIndexController
 */
class MetadataRotateKeyTagsIndexControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;
    use GpgMetadataKeysTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(TagsPlugin::class);
    }

    public function testMetadataRotateKeyTagsIndexController_Success(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create expired metadata key
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $expiredMetadataKey */
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        $metadata = json_encode(MetadataTagDto::fromArray(['name' => 'marketing'])->getClearTextMetadata());
        TagFactory::make(8)
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();
        // Tag shouldn't be returned
        TagFactory::make(2)->persist(); // v4
        // Tag with active metadata key
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $activeMetadataKey */
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        $metadata = json_encode(MetadataTagDto::fromArray(['name' => 'active'])->getClearTextMetadata());
        TagFactory::make(2)
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $activeMetadataKey->id], true)
            ->persist();
        // another user's tag
        $metadata = json_encode(MetadataTagDto::fromArray(['name' => 'betty Tag'])->getClearTextMetadata());
        TagFactory::make(25)
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();

        $this->logInAs($admin);
        $this->getJson('/metadata/rotate-key/tags.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(20, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 33,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
    }

    /**
     * Setting pagination query params in the request shouldn't change the result.
     *
     * @return void
     */
    public function testMetadataRotateKeyTagsIndexController_Success_SetPageAndCount(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create expired metadata key
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $expiredMetadataKey */
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        $metadata = json_encode(MetadataTagDto::fromArray(['name' => 'marketing'])->getClearTextMetadata());
        TagFactory::make(5)
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();

        $this->logInAs($admin);
        $this->getJson('/metadata/rotate-key/tags.json?page=2&limit=2');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(5, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 5,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
    }

    public function metadataRotateKeyTagsIndexControllerPaginationLimitValuesProvider(): array
    {
        return [
            [
                'config limit' => 201,
                'no of resources' => 201,
                'expected count' => 200,
            ],
            [
                'config limit' => -1,
                'no of resources' => 2,
                'expected count' => 1,
            ],
        ];
    }

    /**
     * @dataProvider metadataRotateKeyTagsIndexControllerPaginationLimitValuesProvider
     * @param int $config Config value
     * @param int $no No Tags to create
     * @param int $expectedCount Expected records in response
     * @return void
     */
    public function testMetadataRotateKeyTagsIndexController_Success_PaginationMinMaxValues(int $config, int $no, int $expectedCount): void
    {
        Configure::write('passbolt.plugins.metadata.defaultPaginationLimit', $config);
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create expired metadata key
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $expiredMetadataKey */
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        $metadata = json_encode(MetadataTagDto::fromArray(['name' => 'marketing'])->getClearTextMetadata());
        TagFactory::make($no)
            ->v5Fields(['metadata' => $this->encryptForMetadataKey($metadata), 'metadata_key_id' => $expiredMetadataKey->id], true)
            ->persist();

        $this->logInAs($admin);
        $this->getJson('/metadata/rotate-key/tags.json');

        $this->assertSuccess();
        $this->assertCount($expectedCount, $this->getResponseBodyAsArray());
    }

    public function testMetadataRotateKeyTagsIndexController_Error_NotJson(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->logInAs($user);
        $this->get('/metadata/rotate-key/tags');
        $this->assertResponseCode(404);
    }

    public function testMetadataRotateKeyTagsIndexController_Error_NotLoggedIn(): void
    {
        $this->getJson('/metadata/rotate-key/tags.json');
        $this->assertResponseCode(401);
    }

    public function testMetadataRotateKeyTagsIndexController_Error_NotAdmin(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->logInAs($user);
        $this->getJson('/metadata/rotate-key/tags.json');
        $this->assertResponseCode(403);
    }

    public function testMetadataRotateKeyTagsIndexController_Error_InvalidConfigValue(): void
    {
        Configure::write('passbolt.plugins.metadata.defaultPaginationLimit', '🔥');
        $this->logInAsAdmin();
        $this->getJson('/metadata/rotate-key/tags.json');
        $this->assertInternalError('Invalid pagination limit set for metadata endpoint');
    }
}
