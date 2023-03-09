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
use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\Gpgkey;
use App\Service\AuthenticationTokens\AuthenticationTokenGetService;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\ServerRequest;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;

/**
 * AbstractCompleteService class
 */
abstract class AbstractCompleteService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    /**
     * @var \Cake\Http\ServerRequest
     */
    protected $request;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    /**
     * @var \App\Model\Table\GpgkeysTable
     */
    protected $Gpgkeys;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * AbstractCompleteService constructor
     *
     * @param \Cake\Http\ServerRequest|null $request Server request
     */
    public function __construct(?ServerRequest $request = null)
    {
        $this->request = $request ?? new ServerRequest();
        /** @phpstan-ignore-next-line */
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
        /** @phpstan-ignore-next-line */
        $this->Gpgkeys = $this->fetchTable('Gpgkeys');
        /** @phpstan-ignore-next-line */
        $this->Users = $this->fetchTable('Users');
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

        // @depracted since v3.6
        if (isset($data['authenticationtoken'])) {
            $data['authentication_token'] = $data['authenticationtoken'];
        }
        if (!isset($data['authentication_token']) || !isset($data['authentication_token']['token'])) {
            throw new BadRequestException(__('An authentication token should be provided.'));
        }
        $token = $data['authentication_token']['token'];
        if (!Validation::uuid($token)) {
            throw new BadRequestException(__('The authentication token should be a valid UUID.'));
        }
        try {
            return (new AuthenticationTokenGetService())->getActiveNotExpiredOrFail($token, $userId, $tokenType);
        } catch (NotFoundException $exception) {
            throw new BadRequestException(__('The authentication token is not valid.'));
        }
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

        try {
            return $this->Gpgkeys->buildEntityFromArmoredKey($armoredKey, $userId);
        } catch (ValidationException $exception) {
            // Remap errors to match sent data
            throw new CustomValidationException($exception->getMessage(), ['gpgkey' => $exception->getErrors()]);
        }
    }
}
