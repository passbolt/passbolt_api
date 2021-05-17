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

namespace App\Test\TestCase\Controller\ResourceTypes;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\ResourceTypesModelTrait;
use App\Utility\UuidFactory;

class ResourceTypesViewControllerTest extends AppIntegrationTestCase
{
    use ResourceTypesModelTrait;

    public $fixtures = ['app.Base/Users', 'app.Base/Roles', 'app.Base/ResourceTypes'];

    public function testResourceTypesView_Success()
    {
        $this->authenticateAs('ada');
        $resourceType = UuidFactory::uuid('resource-types.id.password-string');
        $this->getJson("/resource-types/$resourceType.json?api-version=2");
        $this->assertSuccess();
        $this->assertResourceTypeAttributes($this->_responseJsonBody);
    }

    public function testResourceTypesView_ErrorNotValidId()
    {
        $this->authenticateAs('ada');
        $resourceId = 'invalid-id';
        $this->getJson("/resource-types/$resourceId.json");
        $this->assertError(400, 'The resource identifier should be a valid UUID.');
    }

    public function testResourceTypesView_ErrorNotFound()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid();
        $this->getJson("/resource-types/$resourceId.json");
        $this->assertError(404, 'The resource type does not exist.');
    }

    public function testResourceTypesView_ErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid();
        $this->getJson("/resource-types/$resourceId.json");
        $this->assertAuthenticationError();
    }
}
