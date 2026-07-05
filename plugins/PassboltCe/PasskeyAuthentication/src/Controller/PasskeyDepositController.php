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
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication\Controller;

use App\Model\Entity\AuthenticationToken;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Passbolt\PasskeyAuthentication\Service\PasskeyCeremonyStoreService;

/**
 * Deposit the raw WebAuthn credential onto the challenge token. Called by the top-level ceremony page
 * right after navigator.credentials completes, so the browser extension can collect it out of band.
 */
class PasskeyDepositController extends PasskeyController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        // The ceremony page may be running in the session-less login flow.
        $this->Authentication->allowUnauthenticated(['post']);

        return parent::beforeFilter($event);
    }

    /**
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyCeremonyStoreService $store Store service
     * @return void
     */
    public function post(PasskeyCeremonyStoreService $store): void
    {
        $this->assertJson();

        [$userId, $tokenType, $token] = $this->extractCommon();
        $store->deposit($userId, $tokenType, $token, $this->extractCredentialJson());

        $this->success(__('The ceremony response has been received.'));
    }

    /**
     * @return array{0: string, 1: string, 2: string} [userId, tokenType, token]
     */
    protected function extractCommon(): array
    {
        $userId = $this->request->getData('user_id');
        $token = $this->request->getData('token');
        $mode = $this->request->getData('mode');
        if (!is_string($userId) || $userId === '') {
            throw new BadRequestException(__('A valid user id is required.'));
        }
        if (!is_string($token) || $token === '') {
            throw new BadRequestException(__('A challenge token is required.'));
        }
        $tokenType = $mode === 'login'
            ? AuthenticationToken::TYPE_PASSKEY_LOGIN
            : AuthenticationToken::TYPE_PASSKEY_SETUP;

        return [$userId, $tokenType, $token];
    }
}
