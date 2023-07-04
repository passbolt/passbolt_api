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

use Passbolt\Rbacs\Service\UiActions\UiActionsCreateService;
use Passbolt\Rbacs\Service\UiActions\UiActionsInsertDefaultsService;
use Passbolt\Rbacs\Test\Lib\RbacsTestCase;

class UiActionsInsertDefaultsServiceTest extends RbacsTestCase
{
    public function testRbacsUiActionsInsertDefaultsService_DiffSuccess()
    {
        $createService = new UiActionsCreateService();
        $createService->create('Folders.use');
        $createService->create('test.action');
        $diff = (new UiActionsInsertDefaultsService())->getDiffDefaultAndDB();
        $this->assertSame(count(UiActionsInsertDefaultsService::DEFAULT_UI_ACTIONS) - 1, count($diff));
    }
}
