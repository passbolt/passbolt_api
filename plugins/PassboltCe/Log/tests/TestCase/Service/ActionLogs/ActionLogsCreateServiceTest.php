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
 * @since         3.12.0
 */

namespace Passbolt\Log\Test\TestCase\Service\ActionLogs;

use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use App\Utility\UserAction;
use App\Utility\UuidFactory;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Passbolt\Log\Service\ActionLogs\ActionLogsCreateService;
use Passbolt\Log\Test\Factory\ActionLogFactory;

/**
 * Class ActionLogsCreateServiceTest
 */
class ActionLogsCreateServiceTest extends TestCase
{
    public function testActionLogsCreateService_Black_Listed_Should_Not_Create_Logs()
    {
        // First, add action to blacklist.
        $actionName = 'Test.BlackList';
        Configure::write('passbolt.plugins.log.config.blackList', [ $actionName ]);
        $accessControl = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $userAction = UserAction::getInstance($accessControl, $actionName, 'Foo');
        $service = new ActionLogsCreateService();
        // Here the stub should be of Cake Controller, as some controllers do not extend AppController
        $controllerStub = $this->getMockBuilder(Controller::class)->disableOriginalConstructor()->getMock();
        $service->create($userAction, $controllerStub);
        $this->assertSame(0, ActionLogFactory::count());
    }
}
