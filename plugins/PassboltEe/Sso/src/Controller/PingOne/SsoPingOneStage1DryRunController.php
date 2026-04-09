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
 * @since         5.11.0
 */

namespace Passbolt\Sso\Controller\PingOne;

use App\Service\Cookie\AbstractSecureCookieService;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\NotFoundException;
use Passbolt\Sso\Controller\AbstractSsoController;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\PingOne\SsoPingOneService;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsGetService;

class SsoPingOneStage1DryRunController extends AbstractSsoController
{
    /**
     * Perform a SSO Login dry run for a given settings_id
     *
     * @param \App\Service\Cookie\AbstractSecureCookieService $cookieService Cookie service
     * @return void
     */
    public function stage1DryRun(AbstractSecureCookieService $cookieService): void
    {
        $this->assertJson();
        $this->User->assertIsAdmin();
        $uac = $this->User->getExtendAccessControl();

        $settingsId = $this->getSettingsIdFromData();
        try {
            $settingsDto = (new SsoSettingsGetService())->getDraftByIdOrFail($settingsId, true);
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The SSO setting does not exist.'), 404, $exception);
        }

        $url = $this->getSsoUrlWithCookie(
            new SsoPingOneService($cookieService, $settingsDto),
            $uac,
            SsoState::TYPE_SSO_SET_SETTINGS
        );

        $this->success(__('The operation was successful.'), $url->jsonSerialize());
    }
}
