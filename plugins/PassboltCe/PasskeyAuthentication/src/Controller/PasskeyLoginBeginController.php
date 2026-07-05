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

use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\PasskeyAuthentication\Service\PasskeyStartAssertionService;

class PasskeyLoginBeginController extends PasskeyController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        // Passkey login runs BEFORE any session exists.
        $this->Authentication->allowUnauthenticated(['post']);

        return parent::beforeFilter($event);
    }

    /**
     * Start a passkey login ceremony for the target user: issue the credential request options
     * (restricted to that user's passkey credentials) and a challenge token. The user id is
     * supplied by the client (the extension knows which account it is set up for).
     *
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyStartAssertionService $service Assertion service
     * @return void
     */
    public function post(PasskeyStartAssertionService $service): void
    {
        $this->assertJson();

        $userId = $this->request->getData('user_id');
        if (!is_string($userId) || !Validation::uuid($userId)) {
            throw new BadRequestException(__('A valid user id is required.'));
        }

        /*
         * Always start a ceremony, even when the user has no passkey credential. Returning a 404
         * for "no credential" would let an unauthenticated caller enumerate which accounts have a
         * passkey. When there is no credential the allowCredentials list is simply empty and the
         * ceremony fails at finish, indistinguishably from a wrong key.
         */
        $result = $service->start($userId);

        $this->success(__('The passkey login has started.'), [
            'token' => $result['token']->token,
            'credentialRequestOptions' => json_decode($result['options'], true),
        ]);
    }
}
