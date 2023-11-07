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
 * @since         3.6.0
 */

namespace Passbolt\Log\Test\TestCase\Service\ActionLogs;

use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenDate;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Log\Service\ActionLogs\ActionLogsDeleteService;
use Passbolt\Log\Test\Factory\ActionLogFactory;

/**
 * Class ActionLogsDeleteServiceTest
 */
class ActionLogsDeleteServiceTest extends AppTestCase
{
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\Log\Model\Table\ActionLogsTable
     */
    protected $ActionLogs;

    public function setUp(): void
    {
        parent::setUp();
        $this->ActionLogs = $this->fetchTable('Passbolt/Log.ActionLogs');
    }

    /**
     * Test delete function without cutoff date
     */
    public function testActionLogsDeleteService_Delete_Without_Date()
    {
        $nRandomActions = rand(5, 20);
        $actionName = ActionLogsDeleteService::AUTH_LOGIN_LOGIN_GET;
        ActionLogFactory::make(2)
            ->setActionId($actionName)
            ->persist();

        ActionLogFactory::make($nRandomActions)->persist();

        $service = new ActionLogsDeleteService();
        $service->delete($actionName);

        $actionsLeft = ActionLogFactory::find()->where(['action_id' => UuidFactory::uuid($actionName)]);
        $this->assertSame($nRandomActions, ActionLogFactory::count());
        $this->assertSame(0, $actionsLeft->count());
    }

    /**
     * Test delete function with cutoff date
     */
    public function testActionLogsDeleteService_Delete_With_Date()
    {
        $nRandomActions = rand(5, 20);
        $nToBeDeleted = rand(5, 20);
        $nToBeKept = rand(5, 20);
        $actionName = ActionLogsDeleteService::AUTH_LOGIN_LOGIN_GET;

        // Actions to be kept
        ActionLogFactory::make($nToBeKept)
            ->setActionId($actionName)
            ->setField('created', FrozenDate::today())
            ->persist();

        // Actions to be deleted
        ActionLogFactory::make($nToBeDeleted)
            ->setActionId($actionName)
            ->setField('created', FrozenDate::yesterday())
            ->persist();

        // Some random actions to be kept
        ActionLogFactory::make($nRandomActions)->persist();

        $service = new ActionLogsDeleteService();
        $service->delete($actionName, FrozenDate::today());

        $actionsLeft = ActionLogFactory::find()->where(['action_id' => UuidFactory::uuid($actionName)]);
        $this->assertSame($nRandomActions + $nToBeKept, ActionLogFactory::count());
        $this->assertSame($nToBeKept, $actionsLeft->count());
    }

    /**
     * Test delete function with non-valid action name
     */
    public function testActionLogsDeleteService_Delete_Wrong_Action_Name()
    {
        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('The action name is not defined.');
        $service = new ActionLogsDeleteService();
        $service->delete('Foo');
    }
}
