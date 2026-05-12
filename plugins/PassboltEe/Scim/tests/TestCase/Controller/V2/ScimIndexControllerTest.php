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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Test\TestCase\Controller\V2;

use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Test\Factory\UserFactory;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\Scim\Test\Factory\ScimSettingFactory;
use Passbolt\Scim\Test\Utility\ScimApiIntegrationTestCase;

/**
 * ScimIndexControllerTest class
 */
class ScimIndexControllerTest extends ScimApiIntegrationTestCase
{
    use ScenarioAwareTrait;
    use OpenPGPCommonServerOperationsTrait;

    /**
     * Test case
     *
     * @param string $endpoint
     * @param string $expectedResponseFile
     * @return void
     * @dataProvider providerRestScimControllerUsersIndex
     */
    public function testScimControllerUsersIndex(string $endpoint, string $expectedResponseFile)
    {
        $this->setTestNow();
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry1 */
        $scimEntry1 = $this->createScimUser1();
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry2 */
        $scimEntry2 = $this->createScimUser2();

        $expectedResponse = $this->getScimFixtureData($expectedResponseFile);
        $expectedResponse = $this->replaceUserPlaceholders($expectedResponse, $scimEntry1, 1);
        $expectedResponse = $this->replaceUserPlaceholders($expectedResponse, $scimEntry2, 2);

        $this->configScimAuth();
        $this->get($this->getScimEndpoint($endpoint));
        $this->assertResponseCode(200);
        $this->assertResponseEquals($expectedResponse);
    }

    /**
     * Provider for testScimControllerUsersIndex
     *
     * @return array[]
     */
    public static function providerRestScimControllerUsersIndex(): array
    {
        return [
            'no-filter' => [
                'endpoint' => 'Users',
                'expectedResponseFile' => self::FIXTURE_RESPONSE_USERS_LIST,
            ],
            'no-match-filter' => [
                'endpoint' => 'Users?filter=userName+eq+%22user-not-exist%40username.com%22',
                'expectedResponseFile' => self::FIXTURE_RESPONSE_USERS_LIST_NO_MATCH,
            ],
            'match-filter' => [
                'endpoint' => 'Users?filter=userName+eq+%22user1%40username.com%22',
                'expectedResponseFile' => self::FIXTURE_RESPONSE_USERS_LIST_MATCH,
            ],
        ];
    }

    /**
     * Test that a legacy SHA-256 hashed token still authenticates successfully.
     *
     * @return void
     */
    public function testScimControllerUsersIndex_LegacySecretTokenFormat_Success(): void
    {
        // Re-create settings with legacy SHA-256 format (overrides the bcrypt one from setUp)
        $this->fetchTable('Passbolt/Scim.ScimSettings')->deleteAll([]);
        ScimSettingFactory::make()->legacySecretTokenFormat()->persist();
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users'));
        $this->assertResponseCode(200);
    }

    /**
     * Test that a legacy SHA-256 token is transparently rehashed to bcrypt after successful auth.
     *
     * @return void
     */
    public function testScimControllerUsersIndex_LegacySecretTokenFormat_RehashToBcrypt(): void
    {
        // Re-create settings with legacy SHA-256 format
        $this->fetchTable('Passbolt/Scim.ScimSettings')->deleteAll([]);
        ScimSettingFactory::make()->legacySecretTokenFormat()->persist();

        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users'));

        $this->assertResponseCode(200);
        // Verify the stored hash is now bcrypt
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $settings */
        $settings = ScimSettingFactory::find()->firstOrFail();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $values = json_decode($gpg->decrypt($settings->value), associative: true);
        // assert secret token value
        $this->assertStringStartsWith('$2y$', $values['secret_token']);
        $this->assertTrue(password_verify(ScimSettingFactory::SCIM_TEST_SECRET_TOKEN, $values['secret_token']));
    }

    /**
     * Test that legacy SHA-256 tokens are rejected when legacyHashAllowed configuration is false.
     *
     * @return void
     */
    public function testScimControllerUsersIndex_LegacySecretTokenFormat_RejectedWhenDisabled(): void
    {
        Configure::write('passbolt.plugins.scim.security.secretToken.legacyHashAllowed', false);
        // Re-create settings with legacy SHA-256 format
        $this->fetchTable('Passbolt/Scim.ScimSettings')->deleteAll([]);
        ScimSettingFactory::make()->legacySecretTokenFormat()->persist();
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users'));
        $this->assertRedirect();
    }

    /**
     * Regression for PB-51541: an unsupported `{resourceType}` segment used to return 500.
     * It must now return a SCIM-compliant 400 Bad Request via `ListResponse::fetchResources()`.
     */
    public function testScimControllerUsersIndex_InvalidResourceType_ReturnsBadRequest()
    {
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('InvalidResourceType'));

        $this->assertResponseCode(400);
        $this->assertResponseContains('urn:ietf:params:scim:api:messages:2.0:Error');
        $this->assertResponseContains('"status": 400');
        $this->assertResponseContains('The resource type `InvalidResourceType` is not valid');
    }

    /**
     * Test case for SCIM api call with a configured user with MFA enabled
     *
     * @return void
     */
    public function testScimControllerUsersIndex_WithMfaEnabled()
    {
        $this->enableFeaturePlugin('MultiFactorAuthentication');
        $this->setTestNow();
        $user = UserFactory::get($this->scimUserId, contain: ['Profiles', 'Roles']);
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);

        $expectedResponse = $this->getScimFixtureData(self::FIXTURE_RESPONSE_USERS_LIST_NO_MATCH);
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('Users?filter=userName+eq+%22user-not-exist%40username.com%22'));
        $this->assertResponseCode(200);
        $this->assertResponseEquals($expectedResponse);
    }
}
