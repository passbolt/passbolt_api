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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Test\TestCase\Controller\Users;

use App\Model\Entity\Permission;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataSessionKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @uses \App\Controller\Users\UsersDeleteController
 */
class MetadataUsersDeleteControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;
    use GpgMetadataKeysTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MetadataPlugin::class);
        RoleFactory::make()->guest()->persist();
    }

    public static function dryRunProvider()
    {
        return [
            [false], // delete
            [true], // dry-run
        ];
    }

    /**
     * When V4, test V5 fields are not present in the response.
     *
     * @return void
     * @dataProvider dryRunProvider
     */
    public function testMetadataUsersDeleteController_Error_SoleOwnerV4(bool $isDryRun)
    {
        [$owner, $user] = UserFactory::make(2)->user()->persist();
        ResourceFactory::make(2)
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$user], Permission::READ)
            ->withSecretsFor([$owner, $user])
            ->persist();

        $this->logInAsAdmin();
        $ownerId = $owner->get('id');
        $url = $isDryRun ? "/users/{$ownerId}/dry-run.json" : "/users/{$ownerId}.json";
        $this->deleteJson($url);

        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($ownerId);
        $responseBody = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($responseBody['errors']['resources']['sole_owner']);
        // Assert V5 fields are not present
        $resources = $responseBody['errors']['resources']['sole_owner'];
        $v5Fields = MetadataResourceDto::V5_META_PROPS;
        foreach ($resources as $resource) {
            foreach ($v5Fields as $v5Field) {
                $this->assertArrayNotHasKey($v5Field, (array)$resource);
            }
        }
    }

    /**
     * When V5, test v4 fields are not present in the response.
     *
     * @return void
     * @dataProvider dryRunProvider
     */
    public function testMetadataUsersDeleteController_Error_SoleOwnerV5(bool $isDryRun)
    {
        [$owner, $user] = UserFactory::make(2)->user()->persist();
        ResourceFactory::make(2)
            ->v5Fields()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$user], Permission::READ)
            ->withSecretsFor([$owner, $user])
            ->persist();

        $this->logInAsAdmin();
        $ownerId = $owner->get('id');
        $url = $isDryRun ? "/users/{$ownerId}/dry-run.json" : "/users/{$ownerId}.json";
        $this->deleteJson($url);

        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($ownerId);
        $responseBody = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($responseBody['errors']['resources']['sole_owner']);
        // Assert V4 fields are not present
        $resources = $responseBody['errors']['resources']['sole_owner'];
        $v4Fields = MetadataResourceDto::V4_META_PROPS;
        foreach ($resources as $resource) {
            foreach ($v4Fields as $v4Field) {
                $this->assertArrayNotHasKey($v4Field, (array)$resource);
            }
        }
    }

    /**
     * @dataProvider dryRunProvider
     */
    public function testMetadataUsersDeleteController_Error_SoleOwnerMixedV4AndV5Version(bool $isDryRun)
    {
        [$owner, $user] = UserFactory::make(2)->user()->persist();
        // 1 - v4 resource
        $v4Resource = ResourceFactory::make()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$user], Permission::READ)
            ->withSecretsFor([$owner, $user])
            ->persist();
        // 2 - v5 resources
        ResourceFactory::make(2)
            ->v5Fields()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$user], Permission::READ)
            ->withSecretsFor([$owner, $user])
            ->persist();

        $this->logInAsAdmin();
        $ownerId = $owner->get('id');
        $url = $isDryRun ? "/users/{$ownerId}/dry-run.json" : "/users/{$ownerId}.json";
        $this->deleteJson($url);

        $this->assertError(400);
        $this->assertUserIsNotSoftDeleted($ownerId);
        $responseBody = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($responseBody['errors']['resources']['sole_owner']);
        // Assert V4 fields are not present
        $resources = $responseBody['errors']['resources']['sole_owner'];
        $v4Fields = MetadataResourceDto::V4_META_PROPS;
        $v5Fields = MetadataResourceDto::V5_META_PROPS;
        // Check that if v4 resource, then v5 fields should not be present and vice versa.
        foreach ($resources as $resource) {
            if ($resource['id'] === $v4Resource->get('id')) {
                foreach ($v5Fields as $v5Field) {
                    $this->assertArrayNotHasKey($v5Field, (array)$resource);
                }
            } else {
                foreach ($v4Fields as $v4Field) {
                    $this->assertArrayNotHasKey($v4Field, (array)$resource);
                }
            }
        }
    }

    public function testMetadataUsersDeleteController_Success_MetadataRelatedEntriesAreDeleted()
    {
        [$owner, $user] = UserFactory::make(2)->user()->persist();
        ResourceFactory::make(2)
            ->withPermissionsFor([$owner, $user])
            ->withSecretsFor([$owner, $user])
            ->persist();
        $metadataPrivateKey = MetadataPrivateKeyFactory::make()->withMetadataKey()->withUser($owner)->persist();
        MetadataSessionKeyFactory::make(2)->withUser($owner)->persist();
        MetadataSessionKeyFactory::make()->withUser()->persist();

        $this->logInAsAdmin();
        $ownerId = $owner->get('id');
        $this->deleteJson("/users/{$ownerId}.json");

        $this->assertSuccess();
        // Assert private & session keys entries are deleted
        $this->assertEmpty(MetadataPrivateKeyFactory::find()->where(['id' => $metadataPrivateKey->get('id')])->toArray());
        $sessionKeys = MetadataSessionKeyFactory::find()->toArray();
        $this->assertCount(1, $sessionKeys);
    }
}
