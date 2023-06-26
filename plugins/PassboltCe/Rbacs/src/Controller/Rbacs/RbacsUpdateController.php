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

namespace Passbolt\Rbacs\Controller\Rbacs;

use App\Controller\AppController;
use Passbolt\Rbacs\Model\Dto\RbacsUpdateDtoCollection;
use Passbolt\Rbacs\Service\Rbacs\RbacsUpdateService;

class RbacsUpdateController extends AppController
{
    /**
     * @return void
     */
    public function update(): void
    {
        $this->assertJson();
        $this->User->assertIsAdmin();
        $this->assertNotEmptyArrayData();
        $collection = new RbacsUpdateDtoCollection($this->getRequest()->getData());

        $results = (new RbacsUpdateService())
            ->update($this->User->getAccessControl(), $collection);

        $this->success(__('The operation was successful.'), $results);
    }
}
