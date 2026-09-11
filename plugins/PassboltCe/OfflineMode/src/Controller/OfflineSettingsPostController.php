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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Controller;

use App\Controller\AppController;
use Passbolt\OfflineMode\Service\Settings\OfflineSettingsSetService;

class OfflineSettingsPostController extends AppController
{
    /**
     * Enable / update Offline Mode settings.
     *
     * @param \Passbolt\OfflineMode\Service\Settings\OfflineSettingsSetService $offlineSettingsSetService Offline settings get service.
     * @return void
     */
    public function post(OfflineSettingsSetService $offlineSettingsSetService): void
    {
        $this->User->assertIsAdmin();
        $this->assertNotEmptyArrayData();

        $dto = $offlineSettingsSetService->set(
            $this->User->getExtendAccessControl(),
            $this->getRequest()->getData(),
        );

        $this->success(__('The operation was successful.'), $dto->toArray());
    }
}
