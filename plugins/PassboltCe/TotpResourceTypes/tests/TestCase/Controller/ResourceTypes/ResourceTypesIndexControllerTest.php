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
 * @since         4.0.0
 */

namespace Passbolt\TotpResourceTypes\Test\TestCase\Controller\ResourceTypes;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\ResourceTypes\ResourceTypesPlugin;
use Passbolt\ResourceTypes\Test\Lib\Model\ResourceTypesModelTrait;
use Passbolt\ResourceTypes\Test\Scenario\ResourceTypesScenario;
use Passbolt\TotpResourceTypes\Test\Scenario\TotpResourceTypesScenario;
use Passbolt\TotpResourceTypes\TotpResourceTypesPlugin;

/**
 * @see \Passbolt\ResourceTypes\Controller\ResourceTypesIndexController
 */
class ResourceTypesIndexControllerTest extends AppIntegrationTestCase
{
    use ResourceTypesModelTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(TotpResourceTypesPlugin::class);
        $this->enableFeaturePlugin(ResourceTypesPlugin::class);
    }

    public function testResourceTypesIndex_Success_WithTotpResourceTypes()
    {
        $this->loadFixtureScenario(ResourceTypesScenario::class);
        $this->loadFixtureScenario(TotpResourceTypesScenario::class);
        $this->logInAsUser();

        $this->getJson('/resource-types.json?api-version=2');

        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $this->assertCount(4, $this->_responseJsonBody);
    }

    public function testResourceTypesIndex_Success_WithoutTotpResourceTypes()
    {
        $this->loadFixtureScenario(ResourceTypesScenario::class);
        $this->loadFixtureScenario(TotpResourceTypesScenario::class);
        $this->logInAsUser();
        // Disable plugin
        $this->disableFeaturePlugin(TotpResourceTypesPlugin::class);

        $this->getJson('/resource-types.json?api-version=2');

        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $this->assertResourceTypeAttributes($this->_responseJsonBody[0]);
        $this->assertCount(2, $this->_responseJsonBody);
    }

    public function testResourceTypesIndex_ResourceTypesPlugin_Disabled()
    {
        $this->disableFeaturePlugin(ResourceTypesPlugin::class);
        $this->get('/resource-types.json');
        $this->assertResponseCode(404);
        $this->enableFeaturePlugin(ResourceTypesPlugin::class);
    }
}
