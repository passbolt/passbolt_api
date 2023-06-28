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
 * @since         2.0.0
 */
namespace App\Controller\Auth;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Routing\Exception\MissingRouteException;

class AuthLogoutController extends AppController
{
    public const GET_LOGOUT_ENDPOINT_ENABLED_CONFIG = 'passbolt.security.getLogoutEndpointEnabled';

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->assertGetEndPointIsEnabled();
        $this->Authentication->allowUnauthenticated(['logout']);

        return parent::beforeFilter($event);
    }

    /**
     * User logout action
     *
     * @return void
     */
    public function logout(): void
    {
        $logoutRedirect = $this->Authentication->logout();

        $isJson = $this->getRequest()->is('json');
        if (!$isJson) {
            $this->redirect($logoutRedirect);
        }

        $this->success(__('You are successfully logged out.'));
    }

    /**
     * If the request is GET, the endpoint is deactivated by default
     * and should be explicitly activated in configs
     *
     * @return void
     * @throws \Cake\Routing\Exception\MissingRouteException if the end point is disabled
     */
    private function assertGetEndPointIsEnabled(): void
    {
        $isGetRequest = $this->getRequest()->is('GET');
        $isGetLogoutEndpointEnabled = Configure::read(self::GET_LOGOUT_ENDPOINT_ENABLED_CONFIG);
        if ($isGetRequest && !$isGetLogoutEndpointEnabled) {
            throw new MissingRouteException(__('The logout route should only be accessed with POST method.'));
        }
    }
}
