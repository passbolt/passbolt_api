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
use Cake\Validation\Validation;
use Passbolt\Sso\Service\SsoKeys\SsoKeysDeleteService;

class SsoKeysDeleteController extends AppController
{
    /**
     * Delete a given SSO Passphrase Key
     *
     * @param string $id uuid key id
     * @return void
     */
    public function delete(string $id): void
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The SSO key id should be a uuid.'));
        }

        $uac = $this->User->getAccessControl();
        (new SsoKeysDeleteService())->delete($uac, $id);

        $this->success(__('The operation was successful'));
    }
}
