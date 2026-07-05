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

use Cake\Http\Exception\BadRequestException;
use Passbolt\PasskeyAuthentication\Service\PasskeyVerifyAttestationService;

class PasskeySetupFinishController extends PasskeyController
{
    /**
     * Finish a passkey enrollment ceremony: validate the attestation returned by the
     * authenticator and store the credential together with the server half of the passphrase kit
     * computed by the client.
     *
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyVerifyAttestationService $service Attestation verification service
     * @return void
     */
    public function post(PasskeyVerifyAttestationService $service): void
    {
        $this->assertJson();

        $token = $this->request->getData('token');
        if (!is_string($token) || $token === '') {
            throw new BadRequestException(__('A challenge token is required.'));
        }

        $serverKitKey = $this->request->getData('server_kit_key');
        if (!is_string($serverKitKey) || $serverKitKey === '') {
            throw new BadRequestException(__('The passphrase kit is required.'));
        }

        $name = $this->request->getData('name');
        $name = is_string($name) && trim($name) !== '' ? trim($name) : null;

        $entity = $service->verify(
            $this->User->getAccessControl(),
            $token,
            $this->extractCredentialJson(),
            $serverKitKey,
            $name
        );

        $this->success(__('The security key has been registered.'), [
            'id' => $entity->get('id'),
            'credential_id' => $entity->get('credential_id'),
            'name' => $entity->get('name'),
        ]);
    }
}
