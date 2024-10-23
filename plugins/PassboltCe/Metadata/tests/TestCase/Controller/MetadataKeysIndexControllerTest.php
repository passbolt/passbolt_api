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

    public function testMetadataKeysIndexController_Success_WithMetadataPrivateKeys()
    {
        $user = UserFactory::make()->user()->active()->persist();
        $metadataKey = MetadataKeyFactory::make()->withCreatorAndModifier()->persist();
        $metadataPrivateKey = MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser($user)->persist();
        MetadataPrivateKeyFactory::make()->withMetadataKey($metadataKey)->withUser()->persist();
        $this->logInAs($user);

        $queryParams = http_build_query(['contain' => ['metadata_private_keys' => '1']]);
        $this->getJson("/metadata/keys.json?{$queryParams}");

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertCount(1, $response);
        $responseKeys = ['id', 'fingerprint', 'armored_key', 'created', 'modified', 'deleted', 'metadata_private_keys'];
        $this->assertArrayHasAttributes($responseKeys, $response[0]);
        // make sure only private keys related to user is returned
        $this->assertCount(1, $response[0]['metadata_private_keys']);
        $this->assertEqualsCanonicalizing([
            [
                'metadata_key_id' => $metadataKey->get('id'),
                'user_id' => $user->get('id'),
                'data' => $metadataPrivateKey->get('data'),
            ],
        ], $response[0]['metadata_private_keys']);
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
            'deleted' => $deletedMetadataKey->get('deleted')->toAtomString(),
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
