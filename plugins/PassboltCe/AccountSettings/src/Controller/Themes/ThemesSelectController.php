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
namespace Passbolt\AccountSettings\Controller\Themes;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use Cake\Http\Exception\BadRequestException;

/**
 * @property \Passbolt\AccountSettings\Model\Table\AccountSettingsTable $AccountSettings
 */
class ThemesSelectController extends AppController
{
    /**
     * Themes Select action
     *
     * @return void
     */
    public function select()
    {
        $theme = $this->request->getData('value');
        if (!isset($theme) || empty($theme)) {
            throw new BadRequestException(__('A value for the theme should be provided.'));
        }

        /** @var \Passbolt\AccountSettings\Model\Table\AccountSettingsTable $accountSettingsTable */
        $accountSettingsTable = $this->fetchTable('Passbolt/AccountSettings.AccountSettings');
        try {
            $setting = $accountSettingsTable->createOrUpdateSetting($this->User->id(), 'theme', $theme);
        } catch (ValidationException $e) {
            throw new BadRequestException(__('This is not a valid theme.'));
        }
        $this->success(__('The operation was successful.'), $setting);
    }
}
