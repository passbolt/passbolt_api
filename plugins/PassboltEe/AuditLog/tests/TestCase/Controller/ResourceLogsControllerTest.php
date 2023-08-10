<?php
declare(strict_types=1);

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

namespace Passbolt\AuditLog\Test\TestCase\Controller;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Passbolt\Log\Test\Lib\LogIntegrationTestCase;

/**
 * @uses \Passbolt\AuditLog\Controller\ResourceLogsController
 */
class ResourceLogsControllerTest extends LogIntegrationTestCase
{
    public function testAuditLogResourceLogsControllerViewByResourceEmpty()
    {
        $user = UserFactory::make()->user()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $this->logInAs($user);
        $this->getJson("/actionlog/resource/{$resource->id}.json?api-version=v2");
        $this->assertSuccess();
        $this->assertEmpty($this->_responseJsonBody);
    }

    public function testAuditLogResourceLogsControllerViewByResourceUserDoesNotHavePermission()
    {
        $resource = ResourceFactory::make()->withCreator(UserFactory::make()->user())->persist();
        $user = $resource->creator;
        $this->logInAs($user);
        $this->getJson("/actionlog/resource/{$resource->id}.json?api-version=v2");
        $this->assertError(404, 'The resource does not exist.');
    }
}
