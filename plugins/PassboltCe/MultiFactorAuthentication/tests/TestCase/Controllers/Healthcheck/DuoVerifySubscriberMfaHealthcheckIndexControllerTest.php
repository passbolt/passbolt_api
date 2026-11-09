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
 * @since         5.18.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Healthcheck;

use App\Test\Lib\Utility\HealthcheckRequestTestTrait;
use Cake\Core\Configure;
use Cake\Http\Client;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoVerifyDuoCodeService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaOrganizationSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;

class DuoVerifySubscriberMfaHealthcheckIndexControllerTest extends MfaIntegrationTestCase
{
    use HealthcheckRequestTestTrait;

    public function setUp(): void
    {
        parent::setUp();

        // Avoid real HTTP calls made by other health checks
        $this->mockService(Client::class, function () {
            return $this->getMockedHealthcheckStatusRequest();
        });
        $this->mockService('fullBaseUrlReachableClient', function () {
            return $this->getMockedHealthcheckStatusRequest(200, json_encode(['body' => 'OK']));
        });
        $this->mockService('sslHealthcheckClient', function () {
            return $this->getMockedHealthcheckStatusRequest();
        });
    }

    public function testDuoVerifySubscriberMfaHealthcheckIndexController_Success_Json(): void
    {
        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, false);
        MfaOrganizationSettingFactory::make()->duo()->persist();
        $this->logInAsAdmin();

        $this->getJson('/healthcheck.json');

        $this->assertResponseSuccess();
        $result = $this->getResponseBodyAsArray();
        $this->assertFalse($result['mfa']['duoVerifySubscriberEnabled']);
    }
}
