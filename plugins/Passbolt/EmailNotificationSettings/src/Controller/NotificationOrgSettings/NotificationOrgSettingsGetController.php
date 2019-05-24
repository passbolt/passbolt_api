<?php
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
 * @since         2.10.0
 */
namespace Passbolt\EmailNotificationSettings\Controller\NotificationOrgSettings;

use App\Controller\AppController;
use App\Model\Entity\Role;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Utility\Hash;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

class NotificationOrgSettingsGetController extends AppController
{
    /**
     * Handle Org Settings get request
     *
     * @return void
     */
    public function get()
    {
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('You are not allowed to access this location.'));
        }
        if (!$this->request->is('json')) {
            throw new BadRequestException(__('This is not a valid Ajax/Json request.'));
        }

        $configs = EmailNotificationSettings::get();

        $flatten = Hash::flatten($configs);

        $this->success(__('The operation was successful.'), $this->_formatForOutput($flatten));
    }

    /**
     * Format the . delimited keys to snake_case
     *
     * @param array $data The data to Format
     * @return array the formatted array
     */
    private function _formatForOutput(array $data = [])
    {
        $output = [];

        foreach ($data as $key => $value) {
            $key = str_replace('.', '_', $key);

            $output[$key] = $value;
        }

        return $output;
    }
}
