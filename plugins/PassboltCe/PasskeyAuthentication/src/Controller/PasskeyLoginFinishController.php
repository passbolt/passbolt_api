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
use Passbolt\PasskeyAuthentication\Service\PasskeyVerifyAssertionService;

class PasskeyLoginFinishController extends PasskeyController
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
     * Finish a passkey login ceremony: validate the assertion returned by the authenticator and,
     * only then, return the server half of the passphrase kit so the client can recover the passphrase
     * locally and run the normal GPGAuth challenge. No session is created here.
     *
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyVerifyAssertionService $service Assertion verification service
     * @return void
     */
    public function post(PasskeyVerifyAssertionService $service): void
    {
        $this->assertJson();

        $userId = $this->request->getData('user_id');
        if (!is_string($userId) || !Validation::uuid($userId)) {
            throw new BadRequestException(__('A valid user id is required.'));
        }

        $token = $this->request->getData('token');
        if (!is_string($token) || $token === '') {
            throw new BadRequestException(__('A challenge token is required.'));
        }

        $serverKitKey = $service->verify($userId, $token, $this->extractCredentialJson());

        $this->success(__('The security key has been verified.'), [
            'server_kit_key' => $serverKitKey,
        ]);
    }
}
