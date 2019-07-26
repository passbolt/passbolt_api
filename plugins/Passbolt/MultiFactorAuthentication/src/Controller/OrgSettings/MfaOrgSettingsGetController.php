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
 * @since         2.6.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\OrgSettings;

use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\MultiFactorAuthentication\Controller\MfaController;
use App\Model\Entity\Role;
use Cake\Core\Configure;
class MfaOrgSettingsGetController extends MfaController
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
        $config = $this->mfaSettings->getOrganizationSettings()->getConfig();
        $this->success(__('The operation was successful.'), $config);
    }
}