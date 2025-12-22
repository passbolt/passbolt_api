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

namespace Passbolt\Rbacs\Test\TestCase\Service\Actions;

use App\Utility\UuidFactory;
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;
use Passbolt\Rbacs\Test\Lib\RbacsTestCase;

class RbacsControlledActionsInsertServiceTest extends RbacsTestCase
{
    public function testRbacsControlledActionsInsertService_On_Empty_DB(): void
    {
        $expectedActions = array_keys(RbacsControlledActionsInsertService::RBACS_CONTROLLED_ACTIONS);

        $entities = (new RbacsControlledActionsInsertService())->insertRbacsControlledActions();
        $this->assertSame(count($expectedActions), ActionFactory::count());
        $this->assertCount(count($expectedActions), $entities);
        foreach ($expectedActions as $action) {
            $this->assertTrue(ActionFactory::make()->getTable()->exists([
                'id' => UuidFactory::uuid($action),
                'name' => $action,
            ]));
        }
    }

    public function testRbacsControlledActionsInsertService_On_Non_Empty_DB(): void
    {
        $expectedActions = array_keys(RbacsControlledActionsInsertService::RBACS_CONTROLLED_ACTIONS);
        $alreadyPersistedAction = array_pop($expectedActions);
        ActionFactory::make([
            'id' => UuidFactory::uuid($alreadyPersistedAction),
            'name' => $alreadyPersistedAction,
        ])->persist();

        $entities = (new RbacsControlledActionsInsertService())->insertRbacsControlledActions();
        $this->assertSame(count($expectedActions) + 1, ActionFactory::count());
        $this->assertCount(count($expectedActions), $entities);
        foreach ($expectedActions as $action) {
            $this->assertTrue(ActionFactory::make()->getTable()->exists([
                'id' => UuidFactory::uuid($action),
                'name' => $action,
            ]));
        }
    }
}
