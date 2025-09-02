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

use App\Test\Factory\UserFactory;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\Scim\Test\Utility\ScimApiIntegrationTestCase;

/**
 * ScimIndexControllerTest class
 */
class ScimIndexControllerTest extends ScimApiIntegrationTestCase
{
    use ScenarioAwareTrait;

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
