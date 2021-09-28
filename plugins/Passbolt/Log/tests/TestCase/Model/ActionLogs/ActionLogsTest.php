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
 */

namespace Passbolt\Log\Test\TestCase\Model\ActionLogs;

use App\Model\Entity\Role;
use App\Test\Lib\AppTestCase;
use App\Utility\UserAccessControl;
use App\Utility\UserAction;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Datasource\ModelAwareTrait;

/**
 * Class ActionLogsTest
 *
 * @property \Passbolt\Log\Model\Table\ActionsTable $Actions
 * @property \Passbolt\Log\Model\Table\ActionLogsTable $ActionLogs
 */
class ActionLogsTest extends AppTestCase
{
    use ModelAwareTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadModel('Passbolt/Log.Actions');
        $this->loadModel('Passbolt/Log.ActionLogs');
    }

    /**
     * Test create function.
     */
    public function testCreate()
    {
        // Delete cache
        $this->Actions->clearCache();
        $accessControl = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $userAction = UserAction::getInstance($accessControl, 'Resources.Index', 'GET Resources.json');

        $actionLog = $this->ActionLogs->create($userAction, 1);

        /** @psalm-suppress UndefinedMagicMethod magic method exists */
        $action = $this->Actions->findByName('Resources.Index')->first();
        $this->assertEquals($action->id, UserAction::actionId('Resources.Index'));
        $this->assertEquals($action->name, 'Resources.Index');

        $this->assertNotEmpty($actionLog);
        $this->assertEquals($actionLog->action_id, UserAction::actionId('Resources.Index'));
    }

    /**
     * Test that create does not process blacklisted entry points, as defined in the config.
     */
    public function testCreateWithBlackList()
    {
        // Delete cache
        $this->Actions->clearCache();

        // First, add action to blacklist.
        $actionName = 'Test.BlackList';
        Configure::write('passbolt.plugins.log.config.blackList', [ $actionName ]);

        $accessControl = new UserAccessControl(Role::USER, UuidFactory::uuid('user.id.ada'));
        $userAction = UserAction::getInstance($accessControl, $actionName, 'GET Resources.json');

        /** @psalm-suppress UndefinedMagicMethod magic method exists */
        $action = $this->Actions->findByName($actionName)->first();
        $this->assertEmpty($action);

        $actionLog = $this->ActionLogs->create($userAction, 1);
        $this->assertEmpty($actionLog);
    }
}
