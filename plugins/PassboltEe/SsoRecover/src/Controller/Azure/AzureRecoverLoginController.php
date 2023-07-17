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
 * @since         3.11.0
 */

namespace Passbolt\SsoRecover\Controller\Azure;

use App\Model\Entity\Role;
use App\Service\Cookie\AbstractSecureCookieService;
use App\Utility\ExtendedUserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Sso\Controller\AbstractSsoController;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\Azure\SsoAzureService;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsGetService;

class AzureRecoverLoginController extends AbstractSsoController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['login']);
    }

    /**
     * Return a URL to redirect the user to perform SSO (without hint)
     *
     * @param \App\Service\Cookie\AbstractSecureCookieService $cookieService Cookie service
     * @return void
     */
    public function login(AbstractSecureCookieService $cookieService): void
    {
        try {
            (new SsoSettingsGetService())->getActiveOrFail();
        } catch (RecordNotFoundException $e) {
            throw new BadRequestException(__('The SSO settings do not exist.'), null, $e);
        }

        $this->User->assertNotLoggedIn();

        $uac = new ExtendedUserAccessControl(
            Role::GUEST,
            null,
            null,
            $this->User->ip(),
            $this->User->userAgent()
        );

        $url = $this->getSsoUrlWithCookie(new SsoAzureService($cookieService), $uac, SsoState::TYPE_SSO_RECOVER);

        $this->success(__('The operation was successful.'), ['url' => $url]);
    }
}
