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
use Cake\Http\Response;
use Cake\Routing\Exception\MissingRouteException;

class AuthLogoutController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->assertGetJsonEndPointIsEnabled();
        $this->Authentication->allowUnauthenticated(['logout']);

        return parent::beforeFilter($event);
    }

    /**
     * User logout action
     *
     * @return \Cake\Http\Response|null
     */
    public function logout(): ?Response
    {
        return $this->redirect($this->Authentication->logout());
    }

    /**
     * If the request is GET and Json, the endpoint is deactivated by default
     * and should be explicitly activated in configs
     *
     * @return void
     * @throws \Cake\Routing\Exception\MissingRouteException if the end point is disabled
     */
    private function assertGetJsonEndPointIsEnabled(): void
    {
        $isGetJsonEndpoint = $this->getRequest()->is('json') && $this->getRequest()->is('GET');
        $isGetJsonEndpointEnabled = Configure::read('passbolt.security.getLogoutEndpointEnabled');
        if ($isGetJsonEndpoint && !$isGetJsonEndpointEnabled) {
            throw new MissingRouteException(__('The logout route should only be accessed with POST method.'));
        }
    }
}
