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

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\UserPassphrasePolicies\Model\Dto\UserPassphrasePoliciesSettingsDto;
use Passbolt\UserPassphrasePolicies\UserPassphrasePoliciesPlugin;

/**
 * @covers \Passbolt\UserPassphrasePolicies\Controller\UserPassphrasePoliciesGetSettingsController
 */
class UserPassphrasePoliciesGetSettingsControllerTest extends AppIntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(UserPassphrasePoliciesPlugin::class);
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        $this->disableFeaturePlugin(UserPassphrasePoliciesPlugin::class);

        parent::tearDown();
    }

    public function testUserPassphrasePoliciesGetSettingsController_ErrorUnauthenticated()
    {
        $this->getJson('/user-passphrase-policies/settings.json');

        $this->assertResponseCode(401);
    }

    public function testUserPassphrasePoliciesGetSettingsController_Success_DefaultSettingsUser()
    {
        $this->logInAsUser();

        $this->getJson('/user-passphrase-policies/settings.json');

        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $this->assertSame(UserPassphrasePoliciesSettingsDto::ENTROPY_MINIMUM_DEFAULT, $response->entropy_minimum);
        $this->assertSame(true, $response->external_dictionary_check);
        $this->assertSame(UserPassphrasePoliciesSettingsDto::SOURCE_DEFAULT, $response->source);
        $this->assertObjectNotHasAttributes([
            'id',
            'created',
            'modified',
            'created_by',
            'modified_by',
        ], $response);
    }

    public function testUserPassphrasePoliciesGetSettingsController_Success_DefaultSettingsAdmin()
    {
        $this->logInAsAdmin();

        $this->getJson('/user-passphrase-policies/settings.json');

        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $this->assertSame(UserPassphrasePoliciesSettingsDto::ENTROPY_MINIMUM_DEFAULT, $response->entropy_minimum);
        $this->assertSame(true, $response->external_dictionary_check);
        $this->assertSame(UserPassphrasePoliciesSettingsDto::SOURCE_DEFAULT, $response->source);
        $this->assertObjectNotHasAttributes([
            'id',
            'created',
            'modified',
            'created_by',
            'modified_by',
        ], $response);
    }
}
