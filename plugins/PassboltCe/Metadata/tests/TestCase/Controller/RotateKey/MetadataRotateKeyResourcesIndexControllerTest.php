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
use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @uses \Passbolt\Metadata\Controller\RotateKey\MetadataRotateKeyResourcesIndexController
 */
class MetadataRotateKeyResourcesIndexControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;
    use GpgMetadataKeysTestTrait;

    public function testMetadataRotateKeyResourcesIndexController_Success(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create expired metadata key
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        ResourceFactory::make(29)->v5Fields(true, [
            'metadata_key_id' => $expiredMetadataKey->id,
            'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();
        // Resources with a deleted resource type should not be returned
        ResourceFactory::make()
            ->v5Fields(true, [
                'metadata_key_id' => $expiredMetadataKey->id,
                'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
            ])
            ->with(
                'ResourceTypes',
                ResourceTypeFactory::make()->deleted()
            )
            ->persist();
        // resources shouldn't be returned
        ResourceFactory::make()->persist(); // v4
        $activeMetadataKey = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($activeMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        ResourceFactory::make()->v5Fields(true, [
            'metadata_key_id' => $activeMetadataKey->id,
            'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist(); // resource with active metadata key
        // another user's resource returned
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->user()
            ->active()
            ->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($user->get('gpgkey'))->persist();
        ResourceFactory::make()->v5Fields(true, [
            'metadata_key_id' => $expiredMetadataKey->id,
            'metadata' => $this->encryptForUser(json_encode([]), $user, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();

        $this->logInAs($admin);
        $this->getJson('/metadata/rotate-key/resources.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(20, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 30,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
    }

    public function testMetadataRotateKeyResourcesIndexController_Success_SetPageAndCount(): void
    {
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create expired metadata key
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        ResourceFactory::make(8)->v5Fields(true, [
            'metadata_key_id' => $expiredMetadataKey->id,
            'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();

        $this->logInAs($admin);
        $this->getJson('/metadata/rotate-key/resources.json?page=2&limit=2');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(8, $response);
        $headers = $this->getHeadersAsArray();
        $this->assertArrayHasKey('pagination', $headers);
        $this->assertArrayEqualsCanonicalizing([
            'count' => 8,
            'page' => 1,
            'limit' => 20,
        ], $headers['pagination']);
    }

    public function metadataRotateKeyResourcesIndexControllerPaginationLimitValuesProvider(): array
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
     * @dataProvider metadataRotateKeyResourcesIndexControllerPaginationLimitValuesProvider
     * @param int $config Config value
     * @param int $no No resources to create
     * @param int $expectedCount Expected records in response
     * @return void
     */
    public function testMetadataRotateKeyResourcesIndexController_Success_PaginationMinMaxValues(int $config, int $no, int $expectedCount): void
    {
        Configure::write('passbolt.plugins.metadata.defaultPaginationLimit', $config);
        $admin = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withAdaKey())
            ->admin()
            ->active()
            ->persist();
        // create expired metadata key
        $expiredMetadataKey = MetadataKeyFactory::make()->withExpiredKey()->expired()->withServerPrivateKey()->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($expiredMetadataKey)->withUserPrivateKey($admin->get('gpgkey'))->persist();
        ResourceFactory::make($no)->v5Fields(true, [
            'metadata_key_id' => $expiredMetadataKey->id,
            'metadata' => $this->encryptForUser(json_encode([]), $admin, $this->getAdaNoPassphraseKeyInfo()),
        ])->persist();

        $this->logInAs($admin);
        $this->getJson('/metadata/rotate-key/resources.json');

        $this->assertSuccess();
        $this->assertCount($expectedCount, $this->getResponseBodyAsArray());
    }

    public function testMetadataRotateKeyResourcesIndexController_Error_NotJson(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->logInAs($user);
        $this->get('/metadata/rotate-key/resources');
        $this->assertResponseCode(404);
    }

    public function testMetadataRotateKeyResourcesIndexController_Error_NotLoggedIn(): void
    {
        $this->getJson('/metadata/rotate-key/resources.json');
        $this->assertResponseCode(401);
    }

    public function testMetadataRotateKeyResourcesIndexController_Error_NotAdmin(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $this->logInAs($user);
        $this->getJson('/metadata/rotate-key/resources.json');
        $this->assertResponseCode(403);
    }

    public function testMetadataRotateKeyResourcesIndexController_Error_InvalidConfigValue(): void
    {
        Configure::write('passbolt.plugins.metadata.defaultPaginationLimit', '🔥');
        $this->logInAsAdmin();
        $this->getJson('/metadata/rotate-key/resources.json');

        $this->assertInternalError('Invalid pagination limit set for metadata endpoint');
    }
}
