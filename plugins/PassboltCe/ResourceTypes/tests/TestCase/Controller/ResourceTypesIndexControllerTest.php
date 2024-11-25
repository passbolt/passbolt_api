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

use App\Test\Factory\ResourceFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\Core\Configure;
use Passbolt\ResourceTypes\ResourceTypesPlugin;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Passbolt\ResourceTypes\Test\Lib\Model\ResourceTypesModelTrait;
use Passbolt\ResourceTypes\Test\Scenario\ResourceTypesScenario;

/**
 * @covers \Passbolt\ResourceTypes\Controller\ResourceTypesIndexController
 */
class ResourceTypesIndexControllerTest extends AppIntegrationTestCaseV5
{
    use ResourceTypesModelTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(ResourceTypesPlugin::class);
    }

    public function testResourceTypesIndexController_Success()
    {
        $this->loadFixtureScenario(ResourceTypesScenario::class);
        $this->logInAsUser();

        $this->getJson('/resource-types.json?api-version=2');

        $this->assertSuccess();
        $this->assertGreaterThan(1, count($this->_responseJsonBody));
        $this->assertResourceTypeAttributes($this->_responseJsonBody[0]);
        $this->assertCount(2, $this->_responseJsonBody);
    }

    public function testResourceTypesIndexController_ErrorNotAuthenticated()
    {
        $this->getJson('/resource-types.json');
        $this->assertAuthenticationError();
    }

    public function testResourceTypesIndexController_FilterByDeleted()
    {
        $typeDeletedId = ResourceTypeFactory::make()->deleted()->persist()->get('id');
        ResourceTypeFactory::make()->persist();
        $this->logInAsUser();
        $this->getJson('/resource-types.json?filter[is-deleted]=1');

        $this->assertSuccess();
        $this->assertCount(1, $this->_responseJsonBody);
        $this->assertSame($typeDeletedId, $this->_responseJsonBody[0]->id);
    }

    public function testResourceTypesIndexController_FilterByDeleted_Non_Boolean()
    {
        $this->logInAsUser();
        $this->getJson('/resource-types.json?filter[is-deleted]=foo');
        $this->assertBadRequestError('Invalid filter. "foo" is not a valid value for filter is-deleted.');
    }

    public function testResourceTypesIndexController_Contain_Resources_Count()
    {
        $resourceType = ResourceTypeFactory::make(1)->persist();
        ResourceFactory::make(3)->with('ResourceTypes', $resourceType)->persist();

        $this->logInAsAdmin();
        $this->getJson('/resource-types.json?contain[resources_count]=1');

        $this->assertSuccess();
        $this->assertCount(1, $this->_responseJsonBody);
        $this->assertSame(3, $this->_responseJsonBody[0]->resources_count);
    }

    public function testResourceTypesIndexController_Contain_Resources_Count_Non_Admin()
    {
        $resourceType = ResourceTypeFactory::make(1)->persist();
        ResourceFactory::make(3)->with('ResourceTypes', $resourceType)->persist();
        $this->logInAsUser();
        $this->getJson('/resource-types.json?contain[resources_count]=1');

        $this->assertSuccess();
        $this->assertCount(1, $this->_responseJsonBody);
        $this->assertObjectNotHasAttributes(['resources_count'], $this->_responseJsonBody[0]);
    }

    public function testResourceTypesIndexController__Contain_Resources_Count_Non_Boolean()
    {
        $this->logInAsUser();
        $this->getJson('/resource-types.json?contain[resources_count]=foo');
        $this->assertBadRequestError('Invalid contain. "foo" is not a valid contain value.');
    }

    public function testResourceTypesIndexController_Success_v4DoesntReturnV5Types()
    {
        $v5Setting = Configure::read('passbolt.v5.enabled');
        Configure::write('passbolt.v5.enabled', false);

        ResourceTypeFactory::make()->v5PasswordString()->persist();

        $this->logInAsUser();
        $this->getJson('/resource-types.json');
        $this->assertSuccess();
        $this->assertCount(0, $this->_responseJsonBody);

        Configure::write('passbolt.v5.enabled', $v5Setting);
    }
}
