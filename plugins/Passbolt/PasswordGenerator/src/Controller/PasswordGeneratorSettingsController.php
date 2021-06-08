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
 * @since         3.3.0
 */
namespace Passbolt\PasswordGenerator\Controller;

use App\Controller\AppController;
use Passbolt\PasswordGenerator\Service\GetPasswordGeneratorService;

class PasswordGeneratorSettingsController extends AppController
{
    /**
     * Get the organization's password generator settings.
     *
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException if the password generator set in env is not supported.
     */
    public function index()
    {
        $service = new GetPasswordGeneratorService();
        $setting = [
            'default_generator' => $service->getPasswordGenerator(),
        ];
        $this->success(__('The operation was successful.'), $setting);
    }
}
