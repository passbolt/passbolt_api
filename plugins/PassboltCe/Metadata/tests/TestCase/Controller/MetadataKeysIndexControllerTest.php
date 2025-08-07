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

namespace Passbolt\Metadata\Test\TestCase\Controller;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;

/**
 * @uses \Passbolt\Metadata\Controller\MetadataKeysIndexController
 */
class MetadataKeysIndexControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function testMetadataKeysIndexController_Success()
    {
        MetadataKeyFactory::make(2)->withCreatorAndModifier()->persist();
        $this->logInAsUser();

        $this->getJson('/metadata/keys.json');

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(2, $response);
        $responseKeys = ['id', 'fingerprint', 'armored_key', 'created', 'modified', 'deleted'];
        $this->assertArrayHasAttributes($responseKeys, $response[0]);
        $this->assertArrayHasAttributes($responseKeys, $response[1]);
    }

    public function testMetadataKeysIndexController_Success_WithContains_And_Filter()
    {
        $user = UserFactory::make()->user()->active()->persist();
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataPrivateKey $metadataPrivateKey */
        $metadataPrivateKey = MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($user)->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();
        $this->logInAs($user);

        $queryParams = http_build_query([
            'contain' => ['metadata_private_keys' => '1', 'creator.profile' => '1'],
            'filter' => ['deleted' => '0']]);
        $this->getJson("/metadata/keys.json?{$queryParams}");

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response);
        $responseKeys = ['id', 'fingerprint', 'armored_key', 'created', 'modified', 'deleted', 'metadata_private_keys', 'creator'];
        $this->assertArrayHasAttributes($responseKeys, $response[0]);
        // make sure only private keys related to user is returned
        $this->assertCount(1, $response[0]['metadata_private_keys']);
        $this->assertEqualsCanonicalizing([
            [
                'id' => $metadataPrivateKey->id,
                'metadata_key_id' => $metadataKey->get('id'),
                'user_id' => $user->get('id'),
                'data' => $metadataPrivateKey->get('data'),
                'created' => $metadataPrivateKey->created->toIso8601String(),
                'modified' => $metadataPrivateKey->modified->toIso8601String(),
                'created_by' => $metadataPrivateKey->created_by,
                'modified_by' => $metadataPrivateKey->modified_by,
            ],
        ], $response[0]['metadata_private_keys']);
        // creator data is returned
        $expectedCreator = $metadataKey->get('creator');
        $creator = $response[0]['creator'];
        $this->assertEqualsCanonicalizing(
            [
                'id' => $expectedCreator->get('id'),
                'role_id' => $expectedCreator->get('role_id'),
                'username' => $expectedCreator->get('username'),
                'active' => $expectedCreator->get('active'),
                'deleted' => $expectedCreator->get('deleted'),
                'disabled' => $expectedCreator->get('disabled'),
                'created' => $expectedCreator->get('created')->toIso8601String(),
                'modified' => $expectedCreator->get('modified')->toIso8601String(),
                'last_logged_in' => $expectedCreator->get('last_logged_in')->toIso8601String(),
            ],
            [
                'id' => $creator['id'],
                'role_id' => $creator['role_id'],
                'username' => $creator['username'],
                'active' => $creator['active'],
                'deleted' => $creator['deleted'],
                'disabled' => $creator['disabled'],
                'created' => $creator['created'],
                'modified' => $creator['modified'],
                'last_logged_in' => $creator['last_logged_in'],
            ]
        );
        // assert profile data
        $this->assertArrayHasKey('profile', $creator);
        $expectedProfile = $expectedCreator->get('profile');
        $profile = $creator['profile'];
        $this->assertEqualsCanonicalizing(
            [
                'id' => $expectedProfile->get('id'),
                'user_id' => $expectedProfile->get('user_id'),
                'first_name' => $expectedProfile->get('first_name'),
                'last_name' => $expectedProfile->get('last_name'),
                'created' => $expectedProfile->get('created')->toIso8601String(),
                'modified' => $expectedProfile->get('modified')->toIso8601String(),
            ],
            [
                'id' => $profile['id'],
                'user_id' => $profile['user_id'],
                'first_name' => $profile['first_name'],
                'last_name' => $profile['last_name'],
                'created' => $profile['created'],
                'modified' => $profile['modified'],
            ]
        );
        $this->assertArrayHasKey('avatar', $profile);
        $this->assertArrayHasKey('url', $profile['avatar']);
        $this->assertNotEmpty($profile['avatar']['url']['small']);
        $this->assertNotEmpty($profile['avatar']['url']['medium']);
    }

    public function testMetadataKeysIndexController_Success_FilterDeleted()
    {
        MetadataKeyFactory::make()->withCreatorAndModifier()->persist();
        $deletedMetadataKey = MetadataKeyFactory::make()->deleted()->withCreatorAndModifier()->persist();
        $this->logInAsUser();

        $queryParams = http_build_query(['filter' => ['deleted' => '1']]);
        $this->getJson("/metadata/keys.json?{$queryParams}");

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response);

        $expected = [
            'id' => $deletedMetadataKey->get('id'),
            'fingerprint' => $deletedMetadataKey->get('fingerprint'),
            'armored_key' => $deletedMetadataKey->get('armored_key'),
            'created' => $deletedMetadataKey->get('created')->toAtomString(),
            'modified' => $deletedMetadataKey->get('modified')->toAtomString(),
            'created_by' => $deletedMetadataKey->get('created_by'),
            'modified_by' => $deletedMetadataKey->get('modified_by'),
            'expired' => $deletedMetadataKey->get('expired'),
            'deleted' => $deletedMetadataKey->get('deleted')->toAtomString(),
        ];
        $this->assertEqualsCanonicalizing($expected, $response[0]);
    }

    public function testMetadataKeysIndexController_Success_FilterExpired()
    {
        MetadataKeyFactory::make()->withCreatorAndModifier()->persist();
        $deletedMetadataKey = MetadataKeyFactory::make()->expired()->withCreatorAndModifier()->persist();
        $this->logInAsUser();

        $queryParams = http_build_query(['filter' => ['expired' => '1']]);
        $this->getJson("/metadata/keys.json?{$queryParams}");

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response);

        $expected = [
            'id' => $deletedMetadataKey->get('id'),
            'fingerprint' => $deletedMetadataKey->get('fingerprint'),
            'armored_key' => $deletedMetadataKey->get('armored_key'),
            'created' => $deletedMetadataKey->get('created')->toAtomString(),
            'modified' => $deletedMetadataKey->get('modified')->toAtomString(),
            'created_by' => $deletedMetadataKey->get('created_by'),
            'modified_by' => $deletedMetadataKey->get('modified_by'),
            'expired' => $deletedMetadataKey->get('expired')->toAtomString(),
            'deleted' => $deletedMetadataKey->get('deleted'),
        ];
        $this->assertEqualsCanonicalizing($expected, $response[0]);
    }

    public function testMetadataKeysIndexController_Error_AuthenticationRequired()
    {
        $this->getJson('/metadata/keys.json');
        $this->assertAuthenticationError();
    }

    public function invalidQueryParameterProvider(): array
    {
        return [
            [
                ['filter' => ['deleted' => 'foo-bar']],
            ],
            [
                ['filter' => '\'; 1=1'],
            ],
            [
                ['contain' => ['metadata_private_keys' => 2000]],
            ],
            [
                ['contain' => 'bad-value'],
            ],
        ];
    }

    /**
     * @dataProvider invalidQueryParameterProvider
     */
    public function testMetadataKeysIndexController_Error_BadFilterQueryParamValue(array $queryParam)
    {
        $this->logInAsUser();
        $queryParams = http_build_query($queryParam);
        $this->getJson("/metadata/keys.json?{$queryParams}");
        $this->assertResponseCode(400);
    }
}
