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
 * @since         3.11.0
 */

namespace Passbolt\MultiFactorAuthentication\Service\Duo;

use App\Model\Entity\AuthenticationToken;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Model\Dto\MfaDuoCallbackDto;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsDuoService;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

/**
 * Class MfaDuoEnableService
 */
class MfaDuoEnableService
{
    /**
     * @var \Duo\DuoUniversal\Client
     */
    protected $duoClient;

    /**
     * MfaDuoEnableService constructor.
     *
     * @param \Duo\DuoUniversal\Client|null $client Duo SDK Client
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException If it cannot create the Duo Sdk Client
     */
    public function __construct(?Client $client = null)
    {
        try {
            $this->duoClient = $client ?? (new MfaDuoGetSdkClientService())->getOrFail(
                new MfaOrgSettingsDuoService(MfaOrgSettings::get()->getSettings()),
                AuthenticationToken::TYPE_MFA_SETUP
            );
        } catch (\Throwable $th) {
            $msg = __('Could not enable Duo MFA provider.');
            throw new InternalErrorException($msg, null, $th);
        }
    }

    /**
     * Enable Duo for the operator.
     *
     * @param \App\Utility\UserAccessControl $uac The user access control
     * @param \Passbolt\MultiFactorAuthentication\Model\Dto\MfaDuoCallbackDto $duoCallbackDto The Duo callback data
     * @param string $token The authentication token.
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \InvalidArgumentException if the provided token is not a UUID
     * @throws \Cake\Http\Exception\UnauthorizedException If no active Duo callback authentication can be found.
     * @throws \Cake\Http\Exception\UnauthorizedException If the duo state cannot be verified.
     * @throws \Cake\Http\Exception\UnauthorizedException If the Duo code cannot be verified.
     * @throws \Cake\Http\Exception\InternalErrorException if the Duo provider cannot be enabled for the user.
     */
    public function enable(
        UserAccessControl $uac,
        MfaDuoCallbackDto $duoCallbackDto,
        string $token
    ): AuthenticationToken {
        if (!Validation::uuid($token)) {
            throw new \InvalidArgumentException('The authentication token should be a valid UUID.');
        }
        $authenticationTokenType = AuthenticationToken::TYPE_MFA_SETUP;
        $authenticationToken = (new MfaDuoCallbackAuthenticationTokenService())
            ->consumeAndVerifyAuthenticationToken(
                $uac,
                $authenticationTokenType,
                $token,
                $duoCallbackDto->state
            );
        try {
            (new MfaDuoVerifyDuoCodeService($authenticationTokenType, $this->duoClient))
                ->verify($uac, $duoCallbackDto->duoCode);
        } catch (\Throwable $th) {
            throw new BadRequestException(__('Unable to verify Duo authentication.'), null, $th);
        }
        $this->enableProvider($uac);

        return $authenticationToken;
    }

    /**
     * Enable the provider for the operator.
     *
     * @param \App\Utility\UserAccessControl $uac The user access control
     * @return void
     * @throw InternalErrorException If it could not enable the Duo MFA provider.
     */
    private function enableProvider(UserAccessControl $uac): void
    {
        try {
            MfaAccountSettings::enableProvider($uac, MfaSettings::PROVIDER_DUO);
        } catch (\Throwable $th) {
            throw new InternalErrorException(__('Could not enable Duo MFA provider.'), null, $th);
        }
    }
}
