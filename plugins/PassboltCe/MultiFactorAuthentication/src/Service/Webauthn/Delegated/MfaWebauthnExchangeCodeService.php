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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated;

use App\Model\Entity\AuthenticationToken;
use App\Utility\AuthToken\AuthTokenExpiry;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Routing\Router;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtEncodeService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Throwable;

class MfaWebauthnExchangeCodeService
{
    use LocatorAwareTrait;

    public const AUDIENCE = 'passbolt-mobile-mfa-exchange';
    public const TTL_SECONDS = 60;

    /**
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @param string $ceremonyTokenUuid Ceremony token UUID.
     * @param string $state Base64url state.
     * @return string
     */
    public function create(UserAccessControl $uac, string $ceremonyTokenUuid, string $state): string
    {
        $now = time();

        return (new JwtEncodeService())->encode([
            'iss' => Router::url('/', true),
            'aud' => self::AUDIENCE,
            'sub' => $uac->getId(),
            'cid' => $ceremonyTokenUuid,
            'jti' => bin2hex(random_bytes(16)),
            'state' => $state,
            'iat' => $now,
            'exp' => $now + self::TTL_SECONDS,
        ]);
    }

    /**
     * @param \App\Utility\UserAccessControl $uac UAC.
     * @param string $jwt Exchange code JWT.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException When any check fails.
     */
    public function consume(UserAccessControl $uac, string $jwt): void
    {
        try {
            $publicKey = (new JwksGetService())->getRawPublicKey();
            $claims = JWT::decode($jwt, new Key($publicKey, JwtTokenCreateService::JWT_ALG));
        } catch (Throwable $e) {
            throw new BadRequestException(__('The exchange code is not valid.'), null, $e);
        }

        if (($claims->iss ?? null) !== Router::url('/', true)) {
            throw new BadRequestException(__('The exchange code is not valid.'));
        }
        if (($claims->aud ?? null) !== self::AUDIENCE) {
            throw new BadRequestException(__('The exchange code is not valid.'));
        }
        if (($claims->sub ?? null) !== $uac->getId()) {
            throw new BadRequestException(__('The exchange code is not valid.'));
        }
        if (!isset($claims->cid) || !is_string($claims->cid)) {
            throw new BadRequestException(__('The exchange code is not valid.'));
        }
        if (!isset($claims->state) || !is_string($claims->state)) {
            throw new BadRequestException(__('The exchange code is not valid.'));
        }

        try {
            $expectedState = (new MfaWebauthnDelegatedCeremonyService())->getStateForToken($claims->cid);
        } catch (NotFoundException $e) {
            throw new BadRequestException(__('The exchange code is not valid.'), null, $e);
        }
        if (!hash_equals($expectedState, $claims->state)) {
            throw new BadRequestException(__('The exchange code is not valid.'));
        }

        $ceremonyExpiry = (new AuthTokenExpiry())
            ->getExpiryForTokenType(AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY);
        /** @var \App\Model\Table\AuthenticationTokensTable $tokens */
        $tokens = $this->fetchTable('AuthenticationTokens');
        $affected = $tokens->updateAll(
            ['active' => false],
            [
                'token' => $claims->cid,
                'type' => AuthenticationToken::TYPE_MFA_DELEGATED_CEREMONY,
                'active' => true,
                'created >=' => DateTime::now()->modify('-' . $ceremonyExpiry),
            ],
        );
        if ($affected === 0) {
            throw new BadRequestException(__('The exchange code is not valid.'));
        }
    }
}
