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

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\MultiFactorAuthentication\Controller\MfaController;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaOrgSettingsPostController extends MfaController
{
    /**
     * Handle Org Settings POST request
     *
     * @throws CustomValidationException if the user provided data do not validate
     * @throws ForbiddenException if the user is not an admin
     * @throws BadRequestException if the request is not made using Ajax/Json
     * @return void
     */
    public function post()
    {
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('You are not allowed to access this location'));
        }
        if (!$this->request->is('json')) {
            throw new BadRequestException(__('This is not a valid Ajax/Json request.'));
        }
        // Allow some flexibility in inputs names
        $data = $this->request->getData();
        if (isset($data[MfaSettings::PROVIDER_DUO]['hostname'])) {
            $data[MfaSettings::PROVIDER_DUO][MfaOrgSettings::DUO_HOSTNAME] = $data[MfaSettings::PROVIDER_DUO]['hostname'];
        }

        $orgSettings = $this->mfaSettings->getOrganizationSettings();
        $orgSettings->save($data, $this->User->getAccessControl());
        $config = $this->mfaSettings->getOrganizationSettings()->getConfig();
        $this->success(__('The multi factor authentication settings for the organization were updated.'), $config);
    }
}
