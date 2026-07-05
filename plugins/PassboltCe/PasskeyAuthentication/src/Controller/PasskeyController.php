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

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;

/**
 * Base controller for the passkey endpoints. Holds the small helpers shared by the
 * enrollment and login controllers.
 */
abstract class PasskeyController extends AppController
{
    /**
     * Assert the request is a JSON request.
     *
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException If the request is not JSON
     */
    protected function assertJson(): void
    {
        if (!$this->getRequest()->is('json')) {
            throw new BadRequestException(__('This is not a valid Ajax/Json request.'));
        }
    }

    /**
     * Extract the raw JSON credential from the request. The client may send it either as a JSON
     * encoded string or as an already decoded object; the webauthn-lib serializer expects a string.
     *
     * @return string
     * @throws \Cake\Http\Exception\BadRequestException If the credential is missing
     */
    protected function extractCredentialJson(): string
    {
        $credential = $this->request->getData('credential');
        if (is_string($credential) && $credential !== '') {
            return $credential;
        }
        if (is_array($credential)) {
            return (string)json_encode($credential);
        }

        throw new BadRequestException(__('An authentication response is required.'));
    }
}
