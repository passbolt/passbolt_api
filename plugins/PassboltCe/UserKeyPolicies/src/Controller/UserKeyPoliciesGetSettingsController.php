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
 * @since         5.2.0
 */

namespace Passbolt\UserKeyPolicies\Controller;

use App\Controller\AppController;
use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Service\AuthenticationTokens\AuthenticationTokenGetService;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Log\Log;
use Cake\Validation\Validation;
use Exception;
use Passbolt\UserKeyPolicies\Service\UserKeyPoliciesGetSettingsService;
use Throwable;

class UserKeyPoliciesGetSettingsController extends AppController
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
     * Returns user key policies settings.
     *
     * @return void
     */
    public function get(): void
    {
        $this->assertQueryParameters();

        $userPassphraseGetSettingsService = new UserKeyPoliciesGetSettingsService();
        try {
            $userKeyPoliciesSettingsDto = $userPassphraseGetSettingsService->get();

            $this->success(__('The operation was successful.'), $userKeyPoliciesSettingsDto->toArray());
        } catch (Throwable $error) {
            Log::error($error->getMessage());
            throw new InternalErrorException(__('Could not retrieve the user key policies.'), null, $error);
        }
    }

    /**
     * This method verifies that a guest user can be authenticated with a valid user ID and authentication token.
     *
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException If the user is a guest and neither a user ID nor an authentication token is provided.
     * @throws \Cake\Http\Exception\BadRequestException If the provided user ID is not a valid UUID.
     * @throws \Cake\Http\Exception\BadRequestException If the provided authentication token is not a valid UUID.
     * @throws \Cake\Http\Exception\ForbiddenException If no valid authentication token is found.
     */
    private function assertQueryParameters(): void
    {
        $isLoggedIn = !$this->User->isGuest();
        $isUserToken = $this->getRequest()->getQuery('user_id', false) || $this->getRequest()->getQuery('token', false);

        if ($isLoggedIn) {
            if ($isUserToken) {
                // session confusion: If user is logged in but still authentication token is provided we consider it bad request.
                throw new BadRequestException(__('Conflicting authentication parameters, provide user_id/token only when the user is not already signed in.')); // phpcs:ignore
            }

            return;
        }

        $userId = $this->getRequest()->getQuery('user_id');
        $authToken = $this->getRequest()->getQuery('token');

        if (is_null($userId) || is_null($authToken)) {
            throw new UnauthorizedException(
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

        $errorMsg = __('Unable to authenticate the guest user with the provided credentials.');

        try {
            (new AuthenticationTokenGetService())
                ->getActiveNotExpiredOrFail($authToken, $userId, AuthenticationToken::TYPE_REGISTER);
        } catch (NotFoundException $exception) {
            $errorMsg .= ' ';
            $errorMsg .= __('No registration authentication token found for the given user.');
            throw new BadRequestException($errorMsg, null, $exception);
        } catch (CustomValidationException $exception) {
            $errorMsg .= ' ';
            $errorMsg .= __('The registration authentication token is expired.');
            throw new BadRequestException($errorMsg, null, $exception);
        } catch (Exception $exception) {
            throw new ForbiddenException($errorMsg, null, $exception); // phpcs:ignore
        }
    }
}
