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
 * @since         4.0.0
 */

namespace Passbolt\Sso\Controller\Google;

use App\Service\Cookie\AbstractSecureCookieService;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\NotFoundException;
use Passbolt\Sso\Controller\AbstractSsoController;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\Google\SsoGoogleService;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsGetService;

class SsoGoogleStage1DryRunController extends AbstractSsoController
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

        // User must be an admin
        $this->User->assertIsAdmin();
        $uac = $this->User->getExtendAccessControl();

        // There must be a draft setting to build the provider with
        $settingsId = $this->getSettingsIdFromData();
        try {
            $settingsDto = (new SsoSettingsGetService())->getDraftByIdOrFail($settingsId, true);
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The SSO setting does not exist.'), 404, $exception);
        }

        // Redirect to provider
        $url = $this->getSsoUrlWithCookie(
            new SsoGoogleService($cookieService, $settingsDto),
            $uac,
            SsoState::TYPE_SSO_SET_SETTINGS
        );
        $this->success(__('The operation was successful.'), ['url' => $url]);
    }
}
