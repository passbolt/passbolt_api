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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Controller\Settings;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsActivateService;

class SsoSettingsActivateController extends AppController
{
    /**
     * @param string $id sso setting uuid
     * @return void
     */
    public function activate(string $id): void
    {
        $this->User->assertIsAdmin();

        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The SSO setting id should be a uuid.'));
        }

        $data = $this->request->getData();
        if (!isset($data) || !is_array($data) || !count($data)) {
            throw new BadRequestException(__('The request data should not be empty.'));
        }

        $setting = (new SsoSettingsActivateService())->activate($this->User->getExtendAccessControl(), $id, $data);
        $this->success(__('The SSO settings were activated.'), $setting);
    }
}
