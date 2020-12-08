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

namespace App\Controller\Setup;

use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;

trait SetupControllerTrait
{
    /**
     * Assert that the setup start request is valid
     *
     * @param string $userId uuid
     * @param string $tokenId uuid
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the token is missing or not a uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user id is missing or not a uuid
     */
    protected function _assertRequestSanity(string $userId, string $tokenId): void
    {
        if (!isset($userId)) {
            throw new BadRequestException(__('The user id is missing.'));
        }
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id is not valid. It should be a uuid.'));
        }
        if (!isset($tokenId)) {
            throw new BadRequestException(__('The authentication token is missing.'));
        }
        if (!Validation::uuid($tokenId)) {
            throw new BadRequestException(__('The token is not valid. It should be a uuid.'));
        }
    }

    /**
     * Assert if the browser is supported. Redirect if the browser is not supported.
     *
     * @return bool
     */
    protected function isBrowserSupported(): bool
    {
        $supportedBrowsers = ['firefox', 'chrome'];
        $browserName = strtolower($this->UserAgents->browserName());
        if (!in_array($browserName, $supportedBrowsers)) {
            return false;
        }

        return true;
    }
}
