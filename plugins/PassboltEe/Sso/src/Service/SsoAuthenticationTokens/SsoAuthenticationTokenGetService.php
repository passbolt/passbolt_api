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
namespace Passbolt\Sso\Service\SsoAuthenticationTokens;

use App\Error\Exception\AuthenticationTokenDataPropertyException;
use App\Error\Exception\CustomValidationException;
use App\Utility\ExtendedUserAccessControl;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use Passbolt\Sso\Model\Entity\SsoAuthenticationToken;

/**
 * Class AuthenticationTokenGetService
 *
 * @package App\Service\AuthenticationTokens
 */
class SsoAuthenticationTokenGetService
{
    use LocatorAwareTrait;

    /**
     * @var \Cake\ORM\Table $SsoAuthenticationTokens
     */
    protected $SsoAuthenticationTokens;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->SsoAuthenticationTokens = $this->fetchTable('Passbolt/Sso.SsoAuthenticationTokens');
    }

    /**
     * @param string $token token
     * @param string $type type
     * @param string|null $userId uuid
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is invalid
     * @throws \Cake\Datasource\Exception\RecordNotFoundException if the authentication token cannot be found
     * @return \Passbolt\Sso\Model\Entity\SsoAuthenticationToken
     */
    public function getOrFail(string $token, string $type, ?string $userId = null): SsoAuthenticationToken
    {
        if (!Validation::uuid($token)) {
            throw new BadRequestException(__('The authentication token should be a valid UUID.'));
        }
        if (isset($userId) && !Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id should be a valid UUID.'));
        }

        try {
            $where = [
                'token' => $token,
                'type' => $type,
                'active' => true,
            ];
            if (isset($userId)) {
                $where['user_id'] = $userId;
            }

            /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $tokenEntity */
            $tokenEntity = $this->SsoAuthenticationTokens->find()->where($where)->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new RecordNotFoundException(__('The authentication token does not exist.'), 400, $exception);
        }

        return $tokenEntity;
    }

    /**
     * Get active and not expired token or fail
     *
     * @param string $token Token value.
     * @param string $type Type of token.
     * @return \Passbolt\Sso\Model\Entity\SsoAuthenticationToken
     * @throws \Cake\Http\Exception\NotFoundException If token is not found or inactive
     * @throws \App\Error\Exception\CustomValidationException If the token is expired
     * @throws \Cake\Http\Exception\BadRequestException If token id is not a valid uuid
     */
    public function getActiveNotExpiredOrFail(string $token, string $type): SsoAuthenticationToken
    {
        $ssoAuthToken = $this->getOrFail($token, $type);

        if ($ssoAuthToken->isExpired()) {
            $error = [
                'token' => [
                    'expired' => __('The token is expired.'),
                ],
            ];

            throw new CustomValidationException(__('The authentication token is not valid.'), $error);
        }

        return $ssoAuthToken;
    }

    /**
     * Assert a SsoAuthenticationToken and mark it as inactive disregard if it valid or not
     *
     * @param \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $token entity
     * @param \App\Utility\ExtendedUserAccessControl $uac user access control
     * @param string $settingsId uuid
     * @throws \Cake\Http\Exception\BadRequestException if the token can not be validated against the UAC or settings
     * @return void
     */
    public function assertAndConsume(
        SsoAuthenticationToken $token,
        ExtendedUserAccessControl $uac,
        string $settingsId
    ): void {
        try {
            $this->assert($token, $uac, $settingsId);
        } catch (BadRequestException $exception) {
            $this->consume($token);
            throw $exception;
        }

        $this->consume($token);
    }

    /**
     * Mark a given token as inactive
     *
     * @param \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $token entity
     * @throws \Cake\Http\Exception\InternalErrorException if the authentication token can not be saved
     * @return void
     */
    public function consume(SsoAuthenticationToken $token): void
    {
        if ($token->active) {
            $token->active = false;
            if (!$this->SsoAuthenticationTokens->save($token)) {
                throw new InternalErrorException(__('The authentication token could not be saved.'));
            }
        }
    }

    /**
     * Assert a SsoAuthenticationToken entity against an extended user control (IP and UA) and settings id.
     * This is used to ensure between request user/client, settings consistence
     *
     * @param \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $token entity
     * @param \App\Utility\ExtendedUserAccessControl $uac user access control
     * @param string $settingsId uuid
     * @throws \Cake\Http\Exception\BadRequestException if the authentication is expired
     * @throws \Cake\Http\Exception\BadRequestException if the user agent or IP are missing or not matching
     * @throws \Cake\Http\Exception\BadRequestException if the SSO settings is not valid or not matching
     * @return void
     */
    public function assert(SsoAuthenticationToken $token, ExtendedUserAccessControl $uac, string $settingsId): void
    {
        $errorMsg = __('The SSO authentication token is invalid.') . ' ';

        if ($token->isExpired()) {
            throw new BadRequestException($errorMsg . __('The authentication token is expired.'));
        }

        try {
            $sid = $token->getDataProperty(SsoAuthenticationToken::DATA_SSO_SETTING_ID);
        } catch (AuthenticationTokenDataPropertyException $exception) {
            throw new BadRequestException($errorMsg . __('Settings id is missing.'), 400, $exception);
        }

        if ($token->user_id !== $uac->getId() || !Validation::uuid($token->user_id)) {
            throw new BadRequestException($errorMsg . __('User id mismatch.'));
        }

        if (Configure::read('passbolt.security.userIp')) {
            try {
                $ip = $token->getDataProperty(SsoAuthenticationToken::DATA_IP);
            } catch (AuthenticationTokenDataPropertyException $exception) {
                throw new BadRequestException($errorMsg . __('Token IP is missing.'), 400, $exception);
            }

            if ($ip !== $uac->getUserIp()) {
                throw new BadRequestException($errorMsg . __('User IP mismatch.'));
            }
        }

        if (Configure::read('passbolt.security.userAgent')) {
            try {
                $ua = $token->getDataProperty(SsoAuthenticationToken::DATA_USER_AGENT);
            } catch (AuthenticationTokenDataPropertyException $exception) {
                throw new BadRequestException($errorMsg . __('User agent is missing.'), 400, $exception);
            }
            if ($ua !== $uac->getUserAgent()) {
                throw new BadRequestException($errorMsg . __('User agent mismatch.'));
            }
        }

        if ($sid !== $settingsId || !Validation::uuid($sid)) {
            throw new BadRequestException($errorMsg . __('Settings mismatch.'));
        }
    }
}
