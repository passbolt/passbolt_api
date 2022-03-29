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

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Gpgkey;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Cake\Validation\Validation;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \App\Model\Table\GpgkeysTable $Gpgkeys
 * @property \App\Model\Table\UsersTable $Users
 */
abstract class AbstractCompleteService
{
    use EventDispatcherTrait;
    use ModelAwareTrait;

    /**
     * @var \Cake\Http\ServerRequest
     */
    protected $request;

    /**
     * AbstractCompleteService constructor
     *
     * @param \Cake\Http\ServerRequest|null $request Server request
     */
    public function __construct(?ServerRequest $request = null)
    {
        $this->request = $request ?? new ServerRequest();
        $this->loadModel('AuthenticationTokens');
        $this->loadModel('Gpgkeys');
        $this->loadModel('Users');
    }

    /**
     * Return the authentication from data if any
     *
     * @param string $userId the user uuid the token belongs to
     * @param string $tokenType AuthenticationToken::TYPE_*
     * @throws \Cake\Http\Exception\BadRequestException if no authentication token was provided
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is not a uuid
     * @throws \Cake\Http\Exception\BadRequestException if the authentication token is expired or invalid
     * @return \App\Model\Entity\AuthenticationToken
     */
    protected function getAndAssertToken(string $userId, string $tokenType): AuthenticationToken
    {
        $data = $this->request->getData();
        if (!isset($data['authenticationtoken']) || !isset($data['authenticationtoken']['token'])) {
            throw new BadRequestException(__('An authentication token should be provided.'));
        }
        $tokenId = $data['authenticationtoken']['token'];
        if (!Validation::uuid($tokenId)) {
            throw new BadRequestException(__('The authentication token should be a valid UUID.'));
        }
        if (!$this->AuthenticationTokens->isValid($tokenId, $userId, $tokenType)) {
            throw new BadRequestException(__('The authentication token is not valid or has expired.'));
        }

        return $this->AuthenticationTokens->getByToken($tokenId);
    }

    /**
     * Return the gpg key entity for matching the requesting id
     *
     * @param string $userId the user uuid
     * @throws \Cake\Http\Exception\BadRequestException if the gpg key is not provided or not a valid OpenPGP key
     * @throws \App\Error\Exception\CustomValidationException if armored key content cannot be validated
     * @throws \App\Error\Exception\ValidationException if key cannot be validated against model rules
     * @return \App\Model\Entity\Gpgkey entity
     */
    protected function getAndAssertGpgkey(string $userId): Gpgkey
    {
        $data = $this->request->getData();
        $armoredKey = $data['gpgkey']['armored_key'] ?? null;

        if (empty($armoredKey) || !is_string($armoredKey)) {
            throw new BadRequestException(__('An OpenPGP key must be provided.'));
        }

        // Check basis entity validation rules
        return $this->Gpgkeys->buildEntityFromArmoredKey($armoredKey, $userId);
    }
}
