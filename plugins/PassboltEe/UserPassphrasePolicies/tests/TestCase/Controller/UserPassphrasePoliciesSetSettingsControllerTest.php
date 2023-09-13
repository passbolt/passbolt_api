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
 * @since         4.3.0
 */

namespace Passbolt\UserPassphrasePolicies\Test\TestCase\Controller;

use App\Test\Factory\RoleFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Cake\Routing\Exception\MissingRouteException;
use Passbolt\UserPassphrasePolicies\Test\Factory\UserPassphrasePoliciesSettingFactory;
use Passbolt\UserPassphrasePolicies\UserPassphrasePoliciesPlugin;

/**
 * @covers \Passbolt\UserPassphrasePolicies\Controller\UserPassphrasePoliciesSetSettingsController
 */
class UserPassphrasePoliciesSetSettingsControllerTest extends AppIntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(UserPassphrasePoliciesPlugin::class);

        RoleFactory::make()->guest()->persist();
        // Mock user agent and IP so extended user access control don't fail
        $this->mockUserAgent();
        $this->mockUserIp();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        $this->disableFeaturePlugin(UserPassphrasePoliciesPlugin::class);

        parent::tearDown();
    }

    public function testUserPassphrasePoliciesSetSettingsController_Error_PluginDisabled()
    {
        $this->disableErrorHandlerMiddleware();
        $this->disableFeaturePlugin(UserPassphrasePoliciesPlugin::class);

        $this->expectException(MissingRouteException::class);

        $this->postJson('/user-passphrase-policies/settings.json');
    }

    public function testUserPassphrasePoliciesSetSettingsController_Error_Unauthenticated()
    {
        $this->postJson('/user-passphrase-policies/settings.json');

        $this->assertResponseCode(401);
    }

    public function testUserPassphrasePoliciesSetSettingsController_Error_ForbiddenForUser()
    {
        $this->logInAsUser();

        $this->postJson('/user-passphrase-policies/settings.json');

        $this->assertForbiddenError('Access restricted to administrators.');
    }

    public function testUserPassphrasePoliciesSetSettingsController_Error_ValidationRequired()
    {
        $this->logInAsAdmin();

        $this->postJson('/user-passphrase-policies/settings.json', []);

        $response = $this->_responseJsonBody;
        $this->assertBadRequestError('Could not validate the user passphrase policies settings');
        $this->assertObjectHasAttribute('entropy_minimum', $response);
        $this->assertObjectHasAttribute('external_dictionary_check', $response);
    }

    public function testUserPassphrasePoliciesSetSettingsController_SuccessCreate()
    {
        $admin = $this->logInAsAdmin();
        $data = [
            'entropy_minimum' => '128',
            'external_dictionary_check' => false,
        ];

        $this->postJson('/user-passphrase-policies/settings.json', $data);

        $response = $this->_responseJsonBody;
        /** Make sure response is in correct format & values are valid. */
        $this->assertSuccess();
        $this->assertSame((int)$data['entropy_minimum'], $response->entropy_minimum);
        $this->assertSame($data['external_dictionary_check'], $response->external_dictionary_check);
        $this->assertObjectHasAttribute('id', $response);
        $this->assertObjectHasAttribute('created', $response);
        $this->assertObjectHasAttribute('modified', $response);
        $this->assertSame($admin->id, $response->created_by);
        $this->assertSame($admin->id, $response->modified_by);
        /**
         * Make sure entry is created in the DB.
         *
         * @var \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting[] $settings
         */
        $settings = UserPassphrasePoliciesSettingFactory::find()->toArray();
        $this->assertCount(1, $settings);
        $this->assertArrayEqualsCanonicalizing($data, $settings[0]->value);
    }

    public function testUserPassphrasePoliciesSetSettingsController_SuccessUpdate()
    {
        $admin = $this->logInAsAdmin();
        UserPassphrasePoliciesSettingFactory::make()->persist();
        $data = [
            'entropy_minimum' => 80,
            'external_dictionary_check' => true,
        ];

        $this->postJson('/user-passphrase-policies/settings.json', $data);

        $response = $this->_responseJsonBody;
        $this->assertSuccess();
        $this->assertSame($data['entropy_minimum'], $response->entropy_minimum);
        $this->assertSame($data['external_dictionary_check'], $response->external_dictionary_check);
        $this->assertObjectHasAttribute('id', $response);
        $this->assertObjectHasAttribute('created', $response);
        $this->assertObjectHasAttribute('modified', $response);
        $this->assertSame($admin->id, $response->modified_by);
        /**
         * Make sure entry is created in the DB.
         *
         * @var \Passbolt\UserPassphrasePolicies\Model\Entity\UserPassphrasePoliciesSetting[] $settings
         */
        $settings = UserPassphrasePoliciesSettingFactory::find()->toArray();
        $this->assertCount(1, $settings);
        $this->assertArrayEqualsCanonicalizing($data, $settings[0]->value);
    }
}
