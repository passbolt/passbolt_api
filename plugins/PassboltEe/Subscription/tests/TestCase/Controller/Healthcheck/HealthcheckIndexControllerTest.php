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
 * @since         5.12.0
 */
namespace Passbolt\Subscription\Test\TestCase\Controller\Healthcheck;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\HealthcheckRequestTestTrait;
use Cake\Http\TestSuite\HttpClientTrait;
use Passbolt\Subscription\SubscriptionPlugin;

/**
 * @covers \App\Controller\Healthcheck\HealthcheckIndexController
 */
class HealthcheckIndexControllerTest extends AppIntegrationTestCase
{
    use HealthcheckRequestTestTrait;
    use HttpClientTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SubscriptionPlugin::class);
    }

    public function testHealthcheckIndexController_Success_Json(): void
    {
        $this->logInAsAdmin();
        $this->getJson('/healthcheck.json');

        $this->assertResponseSuccess();
        $result = $this->getResponseBodyAsArray();
        $this->assertFalse($result['application']['subscriptionKeyStatus']);
    }
}
