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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions\Controller;

use App\Controller\AppController;
use Passbolt\SecretRevisions\Service\SecretRevisionsSettingsAssertService;
use Passbolt\SecretRevisions\Service\SecretRevisionsSettingsSetService;

class SecretRevisionsSettingsPostController extends AppController
{
    /**
     * Create/update secret revisions settings.
     *
     * @return void
     */
    public function post(): void
    {
        $this->assertJson();
        $this->User->assertIsAdmin();
        $this->assertNotEmptyArrayData();

        $dto = (new SecretRevisionsSettingsAssertService())->assert($this->getRequest()->getData());
        (new SecretRevisionsSettingsSetService())->saveSettings($this->User->getAccessControl(), $dto);

        $this->success(__('The operation was successful.'), $dto->toArray());
    }
}
