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
 * Collect the raw WebAuthn credential the ceremony page deposited onto the challenge token. Called by
 * the browser extension after it detects the ceremony page reached its success url.
 */
class PasskeyCollectController extends PasskeyController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
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

        $credential = $store->collect($userId, $tokenType, $token);

        $this->success(__('The operation was successful.'), [
            'credential' => json_decode($credential, true),
        ]);
    }
}
