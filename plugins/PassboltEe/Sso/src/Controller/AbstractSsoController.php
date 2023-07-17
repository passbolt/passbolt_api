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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Controller;

use App\Controller\AppController;
use App\Model\Entity\Role;
use App\Service\Users\UserGetService;
use App\Utility\ExtendedUserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Utility\Validation\OAuthTokenValidation;

abstract class AbstractSsoController extends AppController
{
    /**
     * Protect from CSRF by checking if state in URL and cookie matches
     *
     * @throws \Cake\Http\Exception\BadRequestException if the state is not provided in cookie or URL or there is a mismatch
     * @return string
     */
    public function getStateFromUrlAndCookie(): string
    {
        $stateUrl = $this->getStateFromUrlQuery();
        $stateCookie = $this->getStateFromCookie();
        if ($stateUrl !== $stateCookie) {
            throw new BadRequestException(__('CSRF issue. The state in URL and Cookies do not match.'));
        }

        return $stateUrl;
    }

    /**
     * @throws \Cake\Http\Exception\BadRequestException if the state is not provided in cookie or invalid type
     * @return string state
     */
    public function getStateFromCookie(): string
    {
        $state = $this->request->getCookie(AbstractSsoService::SSO_STATE_COOKIE);
        if (!is_string($state) || !SsoState::isValidState($state)) {
            throw new BadRequestException(__('The state is required in cookie.'));
        }

        return $state;
    }

    /**
     * @return array with error and message
     */
    public function assertErrorFromUrlQuery(): ?array
    {
        $error = $this->request->getQuery('error');
        $desc = $this->request->getQuery('error_description');

        if (!is_string($error) || !is_string($desc)) {
            return null;
        } else {
            return [$error => $desc];
        }
    }

    /**
     * @throws \Cake\Http\Exception\BadRequestException if the token is not provided in URL query
     * @return string state
     */
    public function getTokenFromUrlQuery(): string
    {
        $token = $this->request->getQuery('token');
        if (!is_string($token) || !OAuthTokenValidation::token($token)) {
            throw new BadRequestException(__('The token is required in URL parameters.'));
        }

        return $token;
    }

    /**
     * @throws \Cake\Http\Exception\BadRequestException if the state is not provided in URL query
     * @return string state
     */
    public function getStateFromUrlQuery(): string
    {
        $state = $this->request->getQuery('state');
        if (!is_string($state) || !SsoState::isValidState($state)) {
            throw new BadRequestException(__('The state is required in URL parameters.'));
        }

        return $state;
    }

    /**
     * @throws \Cake\Http\Exception\BadRequestException if the code (access token) is not provided in URL query
     * @return string code
     */
    public function getCodeFromUrlQuery(): string
    {
        $code = $this->request->getQuery('code');
        if (!isset($code) || !is_string($code)) {
            throw new BadRequestException(__('The code is required in URL parameters.'));
        }

        return $code;
    }

    /**
     * @throws \Cake\Http\Exception\BadRequestException if the user_id is not provided in URL query
     * @return \App\Utility\ExtendedUserAccessControl
     */
    public function getUacFromData(): ExtendedUserAccessControl
    {
        $userId = $this->request->getData('user_id');
        if (!isset($userId) || !is_string($userId)) {
            throw new BadRequestException(__('The user id is required in URL parameters.'));
        }

        return $this->getUacFromUserIdAndRequest($userId);
    }

    /**
     * @throws \Cake\Http\Exception\BadRequestException if the settingsId is not provided in URL query
     * @return string uuid
     */
    public function getSettingsIdFromData(): string
    {
        $settingsId = $this->request->getData('sso_settings_id');
        if (!isset($settingsId) || !is_string($settingsId)) {
            throw new BadRequestException(__('The settings id is required in URL parameters.'));
        }

        return $settingsId;
    }

    /**
     * Get an extended user access control from a user id and request client info
     *
     * @param string $userId uuid
     * @throws \Cake\Http\Exception\BadRequestException if the userid is not valid or user does not exist or is inactive
     * @return \App\Utility\ExtendedUserAccessControl
     */
    public function getUacFromUserIdAndRequest(string $userId): ExtendedUserAccessControl
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id is invalid.'));
        }

        try {
            $user = (new UserGetService())->getActiveNotDeletedOrFail($userId);
        } catch (NotFoundException $exception) {
            throw new BadRequestException(__('The user does not exist or is not active.'), 400, $exception);
        }

        return new ExtendedUserAccessControl(
            Role::GUEST,
            $user->id,
            $user->username,
            $this->User->ip(),
            $this->User->userAgent()
        );
    }

    /**
     * @param \Passbolt\Sso\Service\Sso\AbstractSsoService $ssoService service
     * @param \App\Utility\ExtendedUserAccessControl $uac user access control
     * @param string $type Type of state
     * @return string url
     */
    protected function getSsoUrlWithCookie(
        AbstractSsoService $ssoService,
        ExtendedUserAccessControl $uac,
        string $type
    ): string {
        $url = $ssoService->getAuthorizationUrl($uac); // generates state
        $cookie = $ssoService->createStateCookie($uac, $type);

        // Redirect user to the provider with the cookie set
        $this->response = $this->getResponse()->withCookie($cookie);

        return $url;
    }
}
