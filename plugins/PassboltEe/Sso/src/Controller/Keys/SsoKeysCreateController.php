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
namespace Passbolt\Sso\Controller\Keys;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Sso\Service\SsoKeys\SsoKeysCreateService;

class SsoKeysCreateController extends AppController
{
    /**
     * Create SSO Passphrase Key
     *
     * @throws \App\Error\Exception\ValidationException if data do not validate
     * @throws \Cake\Http\Exception\InternalErrorException if saving data is not possible
     * @return void
     */
    public function create(): void
    {
        $data = $this->request->getData();
        if (!isset($data) || !is_array($data) || !count($data)) {
            throw new BadRequestException(__('Information about the key is required.'));
        }

        $uac = $this->User->getAccessControl();
        $key = (new SsoKeysCreateService())->create($uac, $data);

        $this->success(__('The operation was successful'), $key);
    }
}
