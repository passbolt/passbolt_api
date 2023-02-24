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
 * @since         2.6.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\OrgSettings;

use Cake\Http\Exception\BadRequestException;
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Controller\MfaController;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsSetService;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettingsDuoBackwardCompatible;

class MfaOrgSettingsPostController extends MfaController
{
    /**
     * Handle Org Settings POST request
     *
     * @throws \App\Error\Exception\CustomValidationException if the user provided data do not validate
     * @throws \Cake\Http\Exception\ForbiddenException if the user is not an admin
     * @throws \Cake\Http\Exception\BadRequestException if the request is not made using Ajax/Json
     * @param \Duo\DuoUniversal\Client $duoSdkClient Duo SDK Client
     * @return void
     */
    public function post(?Client $duoSdkClient = null): void
    {
        $this->User->assertIsAdmin();

        if (!$this->request->is('json')) {
            throw new BadRequestException(__('This is not a valid Ajax/Json request.'));
        }

        /** TODO: Remove this line and its class once the frontend has been updated to use the new format/names */
        $data = MfaOrgSettingsDuoBackwardCompatible::remapSetDuoSettings((array)$this->getRequest()->getData());

        $config = (new MfaOrgSettingsSetService())->setOrgSettings(
            $data,
            $this->User->getAccessControl(),
            $duoSdkClient
        );
        $this->success(__('The multi factor authentication settings for the organization were updated.'), $config);
    }
}
