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

use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Test\Factory\MetadataSessionKeyFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

/**
 * @uses \Passbolt\Metadata\Controller\MetadataSessionKeyCreateController
 */
class MetadataSessionKeyCreateControllerTest extends AppIntegrationTestCaseV5
{
    use LocatorAwareTrait;
    use GpgMetadataKeysTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function testMetadataSessionKeyCreateController_Success()
    {
        $keyInfo = $this->getMakiKeyInfo();
        $gpgkey = GpgkeyFactory::make(['armored_key' => $keyInfo['armored_key'], 'fingerprint' => $keyInfo['fingerprint']]);
        $user = UserFactory::make()
            ->with('Gpgkeys', $gpgkey)
            ->admin()
            ->active()
            ->persist();
        $this->logInAs($user);

        $data = $this->getEncryptedMetadataSessionKeyForMaki();
        $this->postJson('/metadata/session-keys.json', ['data' => $data]);

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasAttributes(['id', 'user_id', 'data', 'created', 'modified'], $response);
        // check metadata_session_keys table data
        $metadataSessionKeys = MetadataSessionKeyFactory::find()->all()->toArray();
        $this->assertCount(1, $metadataSessionKeys);
        $metadataSessionKey = $metadataSessionKeys[0];
        $this->assertSame($data, $metadataSessionKey['data']);
        $this->assertSame($user->get('id'), $metadataSessionKey['user_id']);
    }

    public function testMetadataSessionKeyCreateController_Error_AuthenticationRequired()
    {
        $this->postJson('/metadata/session-keys.json');
        $this->assertAuthenticationError();
    }

    public function testMetadataSessionKeyCreateController_Error_NotJson()
    {
        $this->logInAsUser();
        $this->post('/metadata/session-keys');
        $this->assertResponseCode(404);
    }

    public function testMetadataSessionKeyCreateController_Error_EmptyData()
    {
        $this->logInAsUser();
        $this->post('/metadata/session-keys.json');
        $this->assertResponseCode(400);
    }

    public function invalidRequestDataProvider(): array
    {
        $sessionKeyForServer = $this->getEncryptedMetadataSessionKeyForServerKey();

        return [
            [
                 'request data' => ['data' => 'foo-bar'],
                 'expected errors paths' => ['data.isValidOpenPGPMessage'],
            ],
            [
                'request data' => ['data' => '😎😎😎'],
                'expected errors paths' => ['data.ascii'],
            ],
            [
                'request data' => ['data' => $sessionKeyForServer],
                'expected errors paths' => ['data.isValidEncryptedMetadataSessionKey'],
            ],
        ];
    }

    /**
     * @dataProvider invalidRequestDataProvider
     */
    public function testMetadataSessionKeyCreateController_Error_BadRequest(array $requestData, array $expectedErrors)
    {
        $user = UserFactory::make()
            ->with('Gpgkeys', GpgkeyFactory::make()->withValidOpenPGPKey())
            ->admin()
            ->active()
            ->persist();
        $this->logInAs($user);

        $this->postJson('/metadata/session-keys.json', $requestData);

        $this->assertResponseCode(400);
        $responseBody = $this->getResponseBodyAsArray();
        foreach ($expectedErrors as $expectedErrorPath) {
            $this->assertTrue(Hash::check($responseBody, $expectedErrorPath));
        }
    }
}
