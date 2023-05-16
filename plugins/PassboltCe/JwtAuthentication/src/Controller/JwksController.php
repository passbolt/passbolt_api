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
 * @since         3.3.0
 */

namespace Passbolt\JwtAuthentication\Controller;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Log\Log;
use Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;

class JwksController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['rsa', 'jwks']);
    }

    /**
     * Serve the JWT public key
     *
     * @param \Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService $jwksGetService Service
     * @return void
     */
    public function rsa(JwksGetService $jwksGetService): void
    {
        $keydata = [];
        try {
            $keydata['keydata'] = $jwksGetService->getRawPublicKey();
        } catch (InvalidJwtKeyPairException $e) {
            $this->logAndThrowInvalidJwtKeyPairException($e);
        }
        $this->success(__('The operation was successful.'), $keydata);
    }

    /**
     * Serve the JWT public key
     *
     * @param \Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService $jwksGetService Service
     * @return void
     */
    public function jwks(JwksGetService $jwksGetService): void
    {
        $keys = [];
        try {
            $keys['keys'][] = $jwksGetService->getPublicKey();
        } catch (InvalidJwtKeyPairException $e) {
            $this->logAndThrowInvalidJwtKeyPairException($e);
        }

        // Do not use regular envelope as this is a normalized endpoint
        $this->set(compact('keys'));
        $this->viewBuilder()->setOption('serialize', 'keys');
    }

    /**
     * Logs the message encountered by the user and logs
     * further information for the admins.
     *
     * @param \Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException $e Exception to be logged
     * @return void
     * @throws \Passbolt\JwtAuthentication\Error\Exception\AccessToken\InvalidJwtKeyPairException the exception to be logged
     */
    protected function logAndThrowInvalidJwtKeyPairException(InvalidJwtKeyPairException $e): void
    {
        Log::alert($e->getMessage());
        Log::error(__('The following file could not be read: {0}.', JwksGetService::PUBLIC_KEY_PATH));
        throw $e;
    }
}
