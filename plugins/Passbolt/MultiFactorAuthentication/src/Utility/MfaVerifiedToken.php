<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Utility;

use App\Model\Entity\AuthenticationToken;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use App\Utility\UuidFactory;
use App\Error\Exception\ValidationException;

class MfaVerifiedToken
{
    /**
     * Build and save an AuthenticationToken entity of type mfa
     *
     * @throws ValidationException
     * @param UserAccessControl $uac
     * @return mixed
     */
    public static function get(UserAccessControl $uac, string $provider)
    {
        $AuthenticationTokens = TableRegistry::get('AuthenticationTokens');
        $entityData = [
            'user_id' => $uac->getId(),
            'token' => UuidFactory::uuid(),
            'active' => true,
            'type' => AuthenticationToken::TYPE_MFA,
            'data' => json_encode([
                'provider' => $provider,
                'user_agent' => env('HTTP_USER_AGENT')
            ])
        ];
        $accessibleFields = [
            'user_id' => true,
            'token' => true,
            'active' => true,
            'type' => true,
            'data' => true
        ];
        $token = $AuthenticationTokens->newEntity($entityData, ['accessibleFields' => $accessibleFields]);
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
     * @param UserAccessControl $uac
     * @param string $token
     * @return bool
     */
    static function check(UserAccessControl $uac, string $token)
    {
        // Baseline validity check
        $auth = TableRegistry::get('AuthenticationTokens');
        if(!$auth->isValid($token, $uac->getId(), AuthenticationToken::TYPE_MFA)) {
            return false;
        }

        // Additional data check
        $token = $auth->getByToken($token);
        $data = json_decode($token->data);
        if ($data->user_agent !== env('HTTP_USER_AGENT')) {
            $token->active = false;
            $auth->save($token);
            return false;
        }

        return true;
    }

    /**
     * @param UserAccessControl $uac
     */
    static function deleteAll(UserAccessControl $uac)
    {
        $AuthenticationTokens = TableRegistry::get('AuthenticationTokens');
        $mfaTokens = $AuthenticationTokens->find()
            ->where(['user_id' => $uac->getId(), 'type' => AuthenticationToken::TYPE_MFA])
            ->all();

        foreach ($mfaTokens as $token) {
            $token->active = false;
            $AuthenticationTokens->save($token);
        }
    }
}