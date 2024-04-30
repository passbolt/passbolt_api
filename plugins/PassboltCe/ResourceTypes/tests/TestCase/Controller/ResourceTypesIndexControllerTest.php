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
use Passbolt\ResourceTypes\ResourceTypesPlugin;
use Passbolt\ResourceTypes\Test\Lib\Model\ResourceTypesModelTrait;
use Passbolt\ResourceTypes\Test\Scenario\ResourceTypesScenario;

/**
 * @covers \Passbolt\ResourceTypes\Controller\ResourceTypesIndexController
 */
class ResourceTypesIndexControllerTest extends AppIntegrationTestCase
{
    use ResourceTypesModelTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(ResourceTypesPlugin::class);
    }

    public function testResourceTypesIndex_Success()
    {
        $this->loadFixtureScenario(ResourceTypesScenario::class);
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
}
