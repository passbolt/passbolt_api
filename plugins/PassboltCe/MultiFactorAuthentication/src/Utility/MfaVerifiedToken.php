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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Utility;

use App\Authenticator\SessionIdentificationServiceInterface;
use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Service\AuthenticationTokens\AuthenticationTokenGetService;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;

class MfaVerifiedToken
{
    /**
     * Build and save an AuthenticationToken entity of type mfa
     *
     * @throws \App\Error\Exception\ValidationException if data are not valid (for example user does not exist)
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param string $provider provider name
     * @param string $sessionId Session ID
     * @param bool $remember Remember flag
     * @return string token
     */
    public static function get(
        UserAccessControl $uac,
        string $provider,
        string $sessionId,
        ?bool $remember = false
    ): string {
        /** @var \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens */
        $AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $entityData = [
            'user_id' => $uac->getId(),
            'token' => UuidFactory::uuid(),
            'active' => true,
            'type' => AuthenticationToken::TYPE_MFA,
            'data' => json_encode([
                'provider' => $provider,
                'user_agent' => env('HTTP_USER_AGENT'),
                'remember' => $remember,
            ]),
        ];
        $accessibleFields = [
            'user_id' => true,
            'token' => true,
            'active' => true,
            'type' => true,
            'data' => true,
        ];
        $token = $AuthenticationTokens->newEntity($entityData, ['accessibleFields' => $accessibleFields]);
        $token->hashAndSetSessionId($sessionId);
        $msg = __('It is not possible to create an authentication token for this user.');
        $errors = $token->getErrors();
        if (!empty($errors) || !$AuthenticationTokens->save($token)) {
            throw new ValidationException($msg);
        }

        return $token->token;
    }

    /**
     * Check if a mfa verified token is legit
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param string $tokenString token
     * @param \App\Authenticator\SessionIdentificationServiceInterface|null $sessionIdentificationService Session ID identifier, required unless logging in
     * @param \Cake\Http\ServerRequest|null $request Server request, required only if $sessionIdentificationService is required
     * @return bool
     */
    public static function check(
        UserAccessControl $uac,
        string $tokenString,
        ?SessionIdentificationServiceInterface $sessionIdentificationService = null,
        ?ServerRequest $request = null
    ): bool {
        // Baseline validity check
        /** @var \App\Model\Table\AuthenticationTokensTable $auth */
        $auth = TableRegistry::getTableLocator()->get('AuthenticationTokens');

        try {
            $type = AuthenticationToken::TYPE_MFA;
            $expiry = MfaVerifiedCookie::MAX_DURATION;
            $token = (new AuthenticationTokenGetService())
                ->getActiveNotExpiredOrFail($tokenString, $uac->getId(), $type, $expiry);
        } catch (\Exception $exception) {
            return false;
        }

        // Check for issue when decoding data
        $data = json_decode($token->data ?? '');
        if ($data === null) {
            $auth->setInactive($token->token);

            return false;
        }

        // Remember me
        if (isset($data->remember) && $data->remember === true) {
            if (!$token->isExpired(MfaVerifiedCookie::MAX_DURATION)) {
                return true;
            }
        }

        // Check Session ID
        if (
            isset($sessionIdentificationService) &&
            $sessionIdentificationService->checkAuthenticationToken($request, $token)
        ) {
            return true;
        }

        $auth->setInactive($token->token);

        return false;
    }

    /**
     * Set all token as inactive
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @return void
     */
    public static function setAllInactive(UserAccessControl $uac)
    {
        $AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $mfaTokens = $AuthenticationTokens->find()
            ->where(['user_id' => $uac->getId(), 'type' => AuthenticationToken::TYPE_MFA])
            ->all();

        foreach ($mfaTokens as $token) {
            $token->active = false;
            $AuthenticationTokens->save($token);
        }
    }
}
