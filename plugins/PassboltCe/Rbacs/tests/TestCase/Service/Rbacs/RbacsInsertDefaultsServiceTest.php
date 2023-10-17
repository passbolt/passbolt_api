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
 * @since         4.1.0
 */

namespace Passbolt\Rbacs\Test\TestCase\Service\Rbacs;

use App\Test\Factory\RoleFactory;
use Passbolt\Rbacs\Service\Rbacs\RbacsInsertDefaultsService;
use Passbolt\Rbacs\Service\UiActions\UiActionsInsertDefaultsService;
use Passbolt\Rbacs\Test\Factory\RbacFactory;
use Passbolt\Rbacs\Test\Factory\UiActionFactory;
use Passbolt\Rbacs\Test\Lib\RbacsTestCase;

class RbacsInsertDefaultsServiceTest extends RbacsTestCase
{
    public function testRbacsInsertDefaultsService_Success(): void
    {
        RoleFactory::make()->user()->persist();
        $this->assertEquals(1, RoleFactory::count());
        $this->assertEquals(0, UiActionFactory::count());
        $this->assertEquals(0, RbacFactory::count());

        (new UiActionsInsertDefaultsService())->insertDefaultsIfNotExist();
        $entities = (new RbacsInsertDefaultsService())->allowAllUiActionsForUsers();

        // expect only ui actions for users
        $this->assertEquals(count(UiActionsInsertDefaultsService::DEFAULT_UI_ACTIONS), count($entities));
    }

    public function testRbacsInsertDefaultsService_Success_NoDuplicates(): void
    {
        RoleFactory::make()->user()->persist();

        (new UiActionsInsertDefaultsService())->insertDefaultsIfNotExist();
        $service = new RbacsInsertDefaultsService();

        // do a first run with everything
        $service->allowAllUiActionsForUsers();

        // do a second run with everything, nothing should have been inserted
        $entities = $service->allowAllUiActionsForUsers();
        // No duplicates
        $this->assertEquals(0, count($entities));
    }
}
