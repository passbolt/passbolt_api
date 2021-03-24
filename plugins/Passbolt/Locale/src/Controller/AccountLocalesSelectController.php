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
 * @since         3.2.0
 */
namespace Passbolt\Locale\Controller;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Locale\Utility\LocaleUtility;

/**
 * @property \Passbolt\AccountSettings\Model\Table\AccountSettingsTable $AccountSettings
 * @property \Passbolt\Locale\Controller\Component\LocaleComponent $Locale
 */
class AccountLocalesSelectController extends AppController
{
    /**
     * Create or update the user's locale setting.
     *
     * @return void
     */
    public function select()
    {
        $this->loadComponent('Passbolt/Locale.Locale');
        $locale = $this->Locale->handleRequestData();

        $this->loadModel('Passbolt/AccountSettings.AccountSettings');
        try {
            $setting = $this->AccountSettings->createOrUpdateSetting(
                $this->User->id(),
                LocaleUtility::SETTING_PROPERTY,
                $locale
            );
        } catch (ValidationException $e) {
            throw new BadRequestException(__('This is not a valid locale.'));
        }
        $this->success(__('The operation was successful.'), $setting);
    }
}
