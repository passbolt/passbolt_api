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
 * @since         2.0.0
 */

namespace Passbolt\ResourceTypes\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Passbolt\ResourceTypes\Test\Lib\Model\ResourceTypesModelTrait;

/**
 * @covers \Passbolt\ResourceTypes\Controller\ResourceTypesIndexController
 */
class ResourceTypesIndexControllerTest extends AppIntegrationTestCase
{
    use ResourceTypesModelTrait;

    public function testResourceTypesIndex_Success()
    {
        ResourceTypeFactory::make(2)->persist();
//        $this->loadFixtureScenario(TotpResourceTypesScenario::class);
        $this->logInAsUser();

        $this->getJson('/resource-types.json?api-version=2');

        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $this->assertResourceTypeAttributes($this->_responseJsonBody[0]);
        $this->assertCount(2, $this->_responseJsonBody);
    }

    public function testResourceTypesIndex_ErrorNotAuthenticated()
    {
        $this->getJson('/resource-types.json');
        $this->assertAuthenticationError();
    }

    /*
     * TODO: Move to TotpResourceType plugin
    public function testResourceTypesIndex_Success_TotpResourceTypeEnabled()
    {
        ResourceTypeFactory::make(2)->persist();
        $this->loadFixtureScenario(TotpResourceTypesScenario::class);
        $this->logInAsUser();
        $this->enableFeaturePlugin(TotpResourceTypePlugin::class);

        $this->getJson('/resource-types.json?api-version=2');

        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $this->assertCount(4, $this->_responseJsonBody);
    }
    */
}
