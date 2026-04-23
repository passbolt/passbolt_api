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
use Passbolt\Sso\Service\SsoSettings\SsoSettingsSetService;

class SsoSettingsCreateController extends AppController
{
    /**
     * @throws \Cake\Http\Exception\ForbiddenException if user is not admin
     * @throws \Cake\Http\Exception\BadRequestException if no data is provided
     * @throws \App\Error\Exception\ValidationException if data is not valid
     * @return void
     */
    public function create(): void
    {
        $this->User->assertIsAdmin();

        // Assert request data sanity
        $data = $this->request->getData();
        if (!isset($data) || !is_array($data) || !count($data)) {
            throw new BadRequestException(__('The request data should not be empty.'));
        }

        $service = new SsoSettingsSetService();
        $serviceSettingsDto = $service->create($this->User->getAccessControl(), $data);

        $this->success(__('The draft setting was saved.'), $serviceSettingsDto->toArray());
    }
}
