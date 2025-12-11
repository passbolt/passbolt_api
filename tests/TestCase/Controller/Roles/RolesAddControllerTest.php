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
 * @since         5.8.0
 */

namespace App\Test\TestCase\Controller\Roles;

use App\Service\Roles\RolesAddService;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\Rbacs\Test\Lib\RbacsIntegrationTestCase;

/**
 * @covers \App\Controller\Roles\RolesAddController
 */
class RolesAddControllerTest extends RbacsIntegrationTestCase
{
    use EmailQueueTrait;

    public function testRolesAddController_Success(): void
    {
        // Enable event tracking, required to test events.
        EventManager::instance()->setEventList(new EventList());

        /** @var \App\Model\Entity\User $anotherAdmin */
        $anotherAdmin = UserFactory::make()->admin()->persist();
        $admin = $this->logInAsAdmin();
        $this->postJson('/roles.json', ['name' => 'sales']);

        $this->assertSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertArrayHasAttributes([
            'id',
            'name',
            'description',
            'created',
            'modified',
        ], $response);
        // Assert event fired
        $this->assertEventFired(RolesAddService::AFTER_ROLE_CREATE_SUCCESS_EVENT_NAME);
        $this->assertEmailQueueCount(1);
        $this->assertEmailInBatchContains([
            $admin->profile->full_name . ' created a new role sales',
            'A new role sales has been created.',
        ], $anotherAdmin->username);
    }

    public function testRolesAddController_Error_NotLoggedIn(): void
    {
        RoleFactory::make()->guest()->persist();
        $this->postJson('/roles.json', []);
        $this->assertResponseCode(401);
    }

    public function testRolesAddController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->postJson('/roles.json', []);
        $this->assertResponseCode(403);
    }

    public function testRolesAddController_Error_NotJson(): void
    {
        $this->logInAsAdmin();
        $this->post('/roles', ['name' => 'marketing']);
        $this->assertResponseCode(404);
    }

    public function testRolesAddController_Error_InvalidData(): void
    {
        $this->logInAsAdmin();
        $this->postJson('/roles.json', ['name' => '😄😄😄']);
        $this->assertResponseCode(400);
    }
}
