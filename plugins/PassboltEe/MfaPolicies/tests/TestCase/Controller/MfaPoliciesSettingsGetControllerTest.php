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
 * @since         3.10.0
 */

namespace Passbolt\MfaPolicies\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Routing\Exception\MissingRouteException;
use Passbolt\MfaPolicies\Model\Entity\MfaPoliciesSetting;
use Passbolt\MfaPolicies\Test\Factory\MfaPoliciesSettingFactory;

/**
 * @covers \Passbolt\MfaPolicies\Controller\MfaPoliciesSettingsGetController
 */
class MfaPoliciesSettingsGetControllerTest extends AppIntegrationTestCase
{
    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin('MfaPolicies');
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->disableFeaturePlugin('Passbolt/MfaPolicies');
    }

    public function testMfaPoliciesSettingsGet_ErrorFeatureDisabled()
    {
        $this->disableFeaturePlugin('MfaPolicies');

        $this->disableErrorHandlerMiddleware();

        $this->expectException(MissingRouteException::class);

        $this->getJson('/mfa-policies/settings.json');
    }

    public function testMfaPoliciesSettingsGet_ErrorUnauthenticated()
    {
        $this->getJson('/mfa-policies/settings.json');

        $this->assertResponseCode(401);
    }

    public function testMfaPoliciesSettingsGet_SuccessDefaultSettingsUser()
    {
        $this->logInAsUser();

        $this->getJson('/mfa-policies/settings.json');

        $response = $this->_responseJsonBody;
        $this->assertSuccess();
        $this->assertSame(MfaPoliciesSetting::POLICY_OPT_IN, $response->policy);
        $this->assertSame(true, $response->remember_me_for_a_month);
        $this->assertObjectNotHasAttribute('id', $response);
        $this->assertObjectNotHasAttribute('created_by', $response);
        $this->assertObjectNotHasAttribute('modified_by', $response);
        $this->assertObjectNotHasAttribute('created', $response);
        $this->assertObjectNotHasAttribute('modified', $response);
    }

    public function testMfaPoliciesSettingsGet_SuccessDefaultSettingsAdmin()
    {
        $this->logInAsAdmin();

        $this->getJson('/mfa-policies/settings.json');

        $response = $this->_responseJsonBody;
        $this->assertSuccess();
        $this->assertSame(MfaPoliciesSetting::POLICY_OPT_IN, $response->policy);
        $this->assertSame(true, $response->remember_me_for_a_month);
        $this->assertObjectNotHasAttribute('id', $response);
        $this->assertObjectNotHasAttribute('created_by', $response);
        $this->assertObjectNotHasAttribute('modified_by', $response);
        $this->assertObjectNotHasAttribute('created', $response);
        $this->assertObjectNotHasAttribute('modified', $response);
    }

    public function testMfaPoliciesSettingsGet_SuccessSettingsPresentInDbUser()
    {
        $this->logInAsUser();
        MfaPoliciesSettingFactory::make()->persist();

        $this->getJson('/mfa-policies/settings.json');

        $response = $this->_responseJsonBody;
        $this->assertSuccess();
        $this->assertSame(MfaPoliciesSetting::POLICY_OPT_IN, $response->policy);
        $this->assertSame(true, $response->remember_me_for_a_month);
        $this->assertObjectHasAttributes(
            ['id', 'created_by', 'modified_by', 'created', 'modified'],
            $response
        );
    }

    public function testMfaPoliciesSettingsGet_SuccessSettingsPresentInDbAdmin()
    {
        $this->logInAsAdmin();
        MfaPoliciesSettingFactory::make()->persist();

        $this->getJson('/mfa-policies/settings.json');

        $response = $this->_responseJsonBody;
        $this->assertSuccess();
        $this->assertSame(MfaPoliciesSetting::POLICY_OPT_IN, $response->policy);
        $this->assertSame(true, $response->remember_me_for_a_month);
        $this->assertObjectHasAttributes(
            ['id', 'created_by', 'modified_by', 'created', 'modified'],
            $response
        );
    }
}
