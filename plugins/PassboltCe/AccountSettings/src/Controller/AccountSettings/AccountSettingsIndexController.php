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
 * @since         2.1.0
 */
namespace Passbolt\AccountSettings\Controller\AccountSettings;

use App\Controller\AppController;

/**
 * @property \Passbolt\AccountSettings\Model\Table\AccountSettingsTable $AccountSettings
 */
class AccountSettingsIndexController extends AppController
{
    /**
     * AccountSettings Index action
     *
     * @return void
     */
    public function index()
    {
        /** @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable $accountSettingsTable */
        $accountSettingsTable = $this->fetchTable('Passbolt/AccountSettings.AccountSettings');
        $response = $accountSettingsTable->findIndex($this->User->id(), ['theme', 'locale']);
        $this->success(__('The operation was successful.'), $response);
    }
}
