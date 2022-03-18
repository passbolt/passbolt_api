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
 * @since         3.5.0
 */

namespace App\Service\Setup;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Cake\Validation\Validation;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \App\Model\Table\UserAgentsTable $UserAgents
 * @property \App\Model\Table\UsersTable $Users
 */
abstract class AbstractStartService
{
    use ModelAwareTrait;

    /**
     * @var \Cake\Http\ServerRequest
     */
    protected $request;

    /**
     * AbstractStartService constructor
     *
     * @param \Cake\Http\ServerRequest|null $request Server Request
     */
    public function __construct(?ServerRequest $request = null)
    {
        $this->loadModel('AuthenticationTokens');
        $this->loadModel('UserAgents');
        $this->loadModel('Users');
        $this->request = $request ?? new ServerRequest();
    }

    /**
     * Assert that the setup start request is valid
     *
     * @param string $userId uuid
     * @param string $tokenId uuid
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the token is missing or not a uuid
     * @throws \Cake\Http\Exception\BadRequestException if the user id is missing or not a uuid
     */
    protected function assertRequestSanity(string $userId, string $tokenId): void
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user identifier should be a valid UUID.'));
        }
        if (!Validation::uuid($tokenId)) {
            throw new BadRequestException(__('The token should be a valid UUID.'));
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

    /**
     * Assert the token expiry. If the token is expired, regenerate a new one and throw an notify the client with an error.
     *
     * @param \App\Model\Entity\User $user user attempting to recover
     * @param \App\Model\Entity\AuthenticationToken $token the recovery token
     * @return void
     * @throw CustomValidationException if the token is expired
     */
    protected function assertTokenExpiry(User $user, AuthenticationToken $token): void
    {
        if ($this->AuthenticationTokens->isExpired($token)) {
            $error = [
                'token' => [
                    'expired' => 'The token is expired.',
                ],
            ];
            throw new CustomValidationException(__('The token is expired.'), $error);
        }
    }
}
