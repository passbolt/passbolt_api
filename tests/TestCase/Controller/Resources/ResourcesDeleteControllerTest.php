<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Resources;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\Gpg;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class ResourcesDeleteControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/groups_users', 'app.Base/resources', 'app.Base/permissions'
    ];

    public function setUp()
    {
        $this->Resources = TableRegistry::get('Resources');
        $this->gpg = new Gpg();
        parent::setUp();
    }

    public function testResourcesDeleteSuccess()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->deleteJson("/resources/$resourceId.json?api-version=v1");
        $this->assertSuccess();
    }

    public function testResourcesDeleteErrorResourceIsSoftDeleted()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $this->deleteJson("/resources/$resourceId.json?api-version=v1");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testResourcesDeleteErrorAccessDenied()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.april');
        $this->deleteJson("/resources/$resourceId.json?api-version=v1");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testResourcesDeleteErrorAccessDenied_ReadAccess()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->deleteJson("/resources/$resourceId.json?api-version=v1");
        $this->assertError(403, 'You do not have the permission to delete this resource.');
    }

    public function testResourcesDeleteErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->deleteJson("/resources/$resourceId.json?api-version=v1");
        $this->assertAuthenticationError();
    }
}
