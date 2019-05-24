<?php
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

namespace App\Test\TestCase\Controller\Resources;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class ResourcesDeleteControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Profiles', 'app.Base/Gpgkeys',
        'app.Base/Secrets', 'app.Base/Permissions', 'app.Base/Roles', 'app.Base/Avatars', 'app.Base/Favorites', 'app.Base/EmailQueue'
    ];

    public function setUp()
    {
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        parent::setUp();
    }

    public function testResourcesDeleteSuccess()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->deleteJson("/resources/$resourceId.json");
        $this->assertSuccess();
    }

    public function testErrorCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->delete("/resources/$resourceId.json?api-version=v2");
        $this->assertResponseCode(403);
    }

    public function testResourcesDeleteErrorResourceIsSoftDeleted()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $this->deleteJson("/resources/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testResourcesDeleteErrorAccessDenied()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.april');
        $this->deleteJson("/resources/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testResourcesDeleteErrorAccessDenied_ReadAccess()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->deleteJson("/resources/$resourceId.json");
        $this->assertError(403, 'You do not have the permission to delete this resource.');
    }

    public function testResourcesDeleteErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->deleteJson("/resources/$resourceId.json");
        $this->assertAuthenticationError();
    }
}
