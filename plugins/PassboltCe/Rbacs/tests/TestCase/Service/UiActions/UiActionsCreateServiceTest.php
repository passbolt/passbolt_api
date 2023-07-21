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

namespace Passbolt\Rbacs\Test\TestCase\Service\UiActions;

use Passbolt\Rbacs\Service\UiActions\UiActionsInsertDefaultsService;
use Passbolt\Rbacs\Test\Lib\RbacsTestCase;

class UiActionsCreateServiceTest extends RbacsTestCase
{
    public function testRbacsUiActionsCreateService_Success()
    {
        $collection = (new UiActionsInsertDefaultsService())->insertDefaultsIfNotExist();
        $this->assertSame(count(UiActionsInsertDefaultsService::DEFAULT_UI_ACTIONS), count($collection));
    }

    public function testRbacsUiActionsCreateService_SuccessInsertOnceOnly()
    {
        $service = new UiActionsInsertDefaultsService();
        $collection = $service->insertDefaultsIfNotExist();
        $this->assertNotEmpty($collection);
        $collection = $service->insertDefaultsIfNotExist();
        $this->assertEmpty($collection);
    }
}
