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
 * @since         5.1.1
 */

namespace Passbolt\UserGpgKeyPolicies\Controller;

use App\Controller\AppController;
use App\Model\Entity\AuthenticationToken;
use App\Service\AuthenticationTokens\AuthenticationTokenGetService;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;
use Cake\Validation\Validation;
use Passbolt\UserGpgKeyPolicies\Service\UserGpgKeyPoliciesGetSettingsService;
use Throwable;

class UserGpgKeyPoliciesGetSettingsController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Authentication->allowUnauthenticated(['get']);

        return parent::beforeFilter($event);
    }

    /**
     * Returns user passphrase policies settings.
     *
     * @return void
     */
    public function get()
    {
        $this->authenticateGuestUser();
        $userPassphraseGetSettingsService = new UserGpgKeyPoliciesGetSettingsService();

        try {
            $userGpgKeyPoliciesSettingsDto = $userPassphraseGetSettingsService->get();

            $this->success(
                __('The operation was successful.'),
                $userGpgKeyPoliciesSettingsDto->toArray()
            );
        } catch (Throwable $error) {
            Log::error($error->getMessage());
            throw new InternalErrorException(__('Could not retrieve the user gpg key policies.'));
        }
    }

    /**
     * Authenticate a guest user using the provided authentication token.
     *
     * This method verifies that a guest user can be authenticated with a valid user ID
     * and authentication token.
     *
     * @return void
     * @throws ForbiddenException If the user is a guest and neither a user ID nor an authentication token is provided.
     * @throws BadRequestException If the provided user ID is not a valid UUID.
     * @throws BadRequestException If the provided authentication token is not a valid UUID.
     * @throws ForbiddenException If no valid authentication token is found.
     */
    private function authenticateGuestUser(): void
    {
        if (!$this->User->isGuest()) {
            return;
        }

        $userId = $this->request->getQuery("user_id");
        $authToken = $this->request->getQuery("authentication_token");

        if (is_null($userId) || is_null($authToken)) {
            throw new ForbiddenException(
                __('You are not authorized to access this location.') . ' ' .
                __('Sign-in to passbolt, or provide a valid user ID and authentication token.')
            );
        }

        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user ID must be a valid UUID.'));
        }

        if (!Validation::uuid($authToken)) {
            throw new BadRequestException(__('The authentication token must be a valid UUID.'));
        }

        try {
            (new AuthenticationTokenGetService())
                ->getActiveNotExpiredOrFail($authToken, $userId, AuthenticationToken::TYPE_REGISTER);
        } catch (\Exception $exception) {
            throw new ForbiddenException(__('Unable to authenticate the guest user with the provided credentials.'), null, $exception);
        }
    }
}
