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
 * @since         3.6.0
 */

namespace App\Service\AuthenticationTokens;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\AuthenticationToken;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;

/**
 * Class AuthenticationTokenGetService
 *
 * @package App\Service\AuthenticationTokens
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 */
class AuthenticationTokenGetService
{
    use LocatorAwareTrait;

    /**
     * @var \Cake\ORM\Table $AuthenticationTokens
     */
    protected $AuthenticationTokens;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
    }

    /**
     * Get active and not expired token or fail
     *
     * @param string $token token value uuid - user provided data
     * @param string $userId user id uuid (should be checked separately before use)
     * @param string $type see AuthenticationToken entity types (should be checked separately before use)
     * @param string|null $expiry expiry, expressed in max duration, ex. "30 days". Default to values in config.
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Cake\Http\Exception\NotFoundException if token is not found
     * @throws \App\Error\Exception\CustomValidationException if the token is expired or inactive
     * @throws \Cake\Http\Exception\BadRequestException if token id is not a valid uuid
     */
    public function getActiveNotExpiredOrFail(
        string $token,
        string $userId,
        string $type,
        ?string $expiry = null
    ): AuthenticationToken {
        $tokenEntity = $this->getActiveOrFail($token, $userId, $type);

        if ($tokenEntity->isExpired($expiry)) {
            $tokenEntity->set('active', false);
            $this->AuthenticationTokens->save($tokenEntity);
            $error = [
                'token' => [
                    'expired' => __('The token is expired.'),
                ],
            ];
            throw new CustomValidationException(__('The authentication token is not valid.'), $error);
        }

        return $tokenEntity;
    }

    /**
     * @param string $token authentication token
     * @param string $userId user ID
     * @param string $type token type
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Cake\Http\Exception\NotFoundException if token is not found
     * @throws \App\Error\Exception\CustomValidationException if the token is inactive
     * @throws \Cake\Http\Exception\BadRequestException if token id is not a valid uuid
     */
    public function getActiveOrFail(string $token, string $userId, string $type): AuthenticationToken
    {
        if (!Validation::uuid($token)) {
            throw new BadRequestException(__('The token should be a valid UUID.'));
        }

        try {
            $where = [
                'token' => $token,
                'type' => $type,
                'user_id' => $userId,
            ];
            /** @var \App\Model\Entity\AuthenticationToken $tokenEntity */
            $tokenEntity = $this->AuthenticationTokens->find()->where($where)->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The authentication token could not be found.'));
        }

        if ($tokenEntity->isNotActive()) {
            $error = [
                'token' => [
                    'isActive' => __('The token is already consumed.'),
                ],
            ];
            throw new CustomValidationException(__('The authentication token is not valid.'), $error);
        }

        return $tokenEntity;
    }
}
