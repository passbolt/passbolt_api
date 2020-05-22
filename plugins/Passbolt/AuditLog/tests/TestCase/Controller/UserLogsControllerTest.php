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

namespace Passbolt\AuditLog\Test\TestCase\Utility;

use App\Utility\UuidFactory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

class UserLogsControllerTest extends LogIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users',
        'app.Base/Gpgkeys',
        'app.Base/Profiles',
        'app.Base/Avatars',
        'app.Base/Roles',
        'app.Base/Groups',
        'app.Base/GroupsUsers',
        'app.Base/Resources',
        'app.Base/Permissions',
        'app.Base/Secrets',
        'app.Base/Favorites',
        'app.Base/EmailQueue',
        'plugin.Passbolt/Log.Base/Actions',
        'plugin.Passbolt/Log.Base/ActionLogs',
        'plugin.Passbolt/Log.Base/EntitiesHistory',
        'plugin.Passbolt/Log.Base/PermissionsHistory',
        'plugin.Passbolt/Log.Base/SecretAccesses',
        'plugin.Passbolt/Log.Base/SecretsHistory',
    ];

    public function testUserLogsControllerViewByResourceEmpty()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->getJson("/actionlog/resource/$resourceId.json?api-version=v2");
        $this->assertSuccess();
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testUserLogsControllerViewByResourceUserDoesNotHavePermission()
    {
        $this->authenticateAs('betty');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->getJson("/actionlog/resource/$resourceId.json?api-version=v2");
        $this->assertError(404, 'The resource does not exist.');
    }
}
