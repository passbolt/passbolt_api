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
 * @since         4.4.0
 */

namespace Passbolt\Sso\Controller\OAuth2;

use App\Service\Cookie\AbstractSecureCookieService;
use Cake\Event\EventInterface;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\Sso\Controller\AbstractSsoController;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\OAuth2\SsoOAuth2Service;

class SsoOAuth2Stage1Controller extends AbstractSsoController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['stage1']);
    }

    /**
     * Return a URL to redirect the user to perform SSO
     *
     * @param \App\Service\Cookie\AbstractSecureCookieService $cookieService Cookie service
     * @return void
     */
    public function stage1(AbstractSecureCookieService $cookieService): void
    {
        $this->assertJson();

        // User must not be logged in and be active/not deleted
        $this->User->assertNotLoggedIn();
        $uac = $this->getUacFromData();

        // Redirect to provider
        $providerService = $this->getSsoServiceName();
        $this->assertProviderServiceClass($providerService);
        $url = $this->getSsoUrlWithCookie(new $providerService($cookieService), $uac, SsoState::TYPE_SSO_GET_KEY);

        $this->success(__('The operation was successful.'), $url->jsonSerialize());
    }

    /**
     * Returns SSO service name that will be used to create the service instance.
     *
     * @return string
     */
    protected function getSsoServiceName(): string
    {
        return SsoOAuth2Service::class;
    }

    /**
     * Asserts if given service class exist.
     *
     * @param string $providerService Provider service class name.
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException When provided service class doesn't exist
     */
    private function assertProviderServiceClass(string $providerService): void
    {
        if (!class_exists($providerService)) {
            throw new InternalErrorException("SSO Provider class `$providerService` not found");
        }
    }
}
