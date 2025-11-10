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
use Passbolt\SecretRevisions\Service\SecretRevisionsSettingsDeleteService;

class SecretRevisionsSettingsDeleteController extends AppController
{
    /**
     * Delete secret revisions settings.
     *
     * @return void
     */
    public function delete(): void
    {
        $this->assertJson();
        $this->User->assertIsAdmin();
        (new SecretRevisionsSettingsDeleteService())->delete($this->User->getAccessControl());
        $this->success(__('The operation was successful.'));
    }
}
