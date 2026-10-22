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
use App\Model\Table\AuthenticationTokensTable;
use App\Service\AuthenticationTokens\AuthenticationTokenConsumeService;
use App\Utility\AuthToken\AuthTokenExpiry;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Routing\Router;
use Cake\Validation\Validation;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Passbolt\JwtAuthentication\Service\AccessToken\JwksGetService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtEncodeService;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;
use Throwable;

class MfaWebauthnDelegatedTokenService
{
    use LocatorAwareTrait;

    public const AUDIENCE = 'passbolt-mobile-mfa-exchange';

    protected AuthenticationTokensTable $AuthenticationTokens;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\Delegated\MfaWebauthnDelegatedStateService $stateService State service.
     */
    public function __construct(private readonly MfaWebauthnDelegatedStateService $stateService)
    {
        /** @var \App\Model\Table\AuthenticationTokensTable $tokens */
        $tokens = $this->fetchTable('AuthenticationTokens');
        $this->AuthenticationTokens = $tokens;
    }

    /**
     * @param string $state The delegated state value.
     * @return string The delegated token.
     * @throws \Cake\Http\Exception\BadRequestException When the state does not resolve, or was signed already.
     */
    public function create(string $state): string
    {
        $uac = $this->stateService->resolve($state);
        $secret = $this->stateService->getSecretForState($state);

        $this->consumeOrFail(
            $state,
            $uac->getId(),
            AuthenticationToken::TYPE_MFA_DELEGATED_STATE,
            __('The delegated state is not valid.'),
        );

        $jti = UuidFactory::uuid();
        $this->AuthenticationTokens->generate(
            $uac->getId(),
            AuthenticationToken::TYPE_MFA_DELEGATED_TOKEN,
            $jti,
            [MfaWebauthnDelegatedStateService::SECRET_KEY => $secret],
        );

        return (new JwtEncodeService())->encode([
            'iss' => Router::url('/', true),
            'aud' => self::AUDIENCE,
            'sub' => $uac->getId(),
            'cid' => $state,
            'jti' => $jti,
            'iat' => (int)DateTime::now()->toUnixString(),
            'exp' => $this->getExpiry(),
        ]);
    }

    /**
     * @param \App\Utility\UserAccessControl $uac UAC object.
     * @param string $jwt The delegated token.
     * @param string $secret The delegated secret.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException When any check fails.
     */
    public function consume(UserAccessControl $uac, string $jwt, string $secret): void
    {
        $claims = $this->decodeOrFail($jwt);

        $errorMessage = __('The delegated token is not valid.');

        if (($claims->aud ?? null) !== self::AUDIENCE) {
            throw new BadRequestException($errorMessage);
        }
        if (($claims->sub ?? null) !== $uac->getId()) {
            throw new BadRequestException($errorMessage);
        }

        $jti = $claims->jti ?? null;
        if (!is_string($jti) || !Validation::uuid($jti)) {
            throw new BadRequestException($errorMessage);
        }

        $row = $this->AuthenticationTokens
            ->find('byTokenActiveNotExpired', token: $jti, tokenType: AuthenticationToken::TYPE_MFA_DELEGATED_TOKEN)
            ->first();
        if ($row === null) {
            throw new BadRequestException($errorMessage);
        }

        /** @var \App\Model\Entity\AuthenticationToken $row */
        $expected = $row->getDataValue(MfaWebauthnDelegatedStateService::SECRET_KEY);
        if ($expected === null || !hash_equals($expected, $secret)) {
            throw new BadRequestException($errorMessage);
        }

        $this->consumeOrFail(
            $jti,
            $uac->getId(),
            AuthenticationToken::TYPE_MFA_DELEGATED_TOKEN,
            $errorMessage,
        );
    }

    /**
     * @param string $jwt Delegated token.
     * @return object Verified claims.
     * @throws \Cake\Http\Exception\BadRequestException When the signature, the issuer or the expiry does not hold.
     */
    private function decodeOrFail(string $jwt): object
    {
        try {
            $claims = JWT::decode(
                $jwt,
                new Key((new JwksGetService())->getRawPublicKey(), JwtTokenCreateService::JWT_ALG),
            );
        } catch (Throwable $e) {
            throw new BadRequestException(__('The delegated token is not valid.'), null, $e);
        }

        if (($claims->iss ?? null) !== Router::url('/', true)) {
            throw new BadRequestException(__('The delegated token is not valid.'));
        }

        return $claims;
    }

    /**
     * @param string $token Token value.
     * @param string $userId Owner of the row.
     * @param string $type Token type.
     * @param string $message Generic error to report, naming no cause.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException When the row is unknown, expired or consumed already.
     */
    private function consumeOrFail(string $token, string $userId, string $type, string $message): void
    {
        try {
            (new AuthenticationTokenConsumeService())->consumeActiveNotExpiredOrFail($token, $userId, $type);
        } catch (Throwable $e) {
            throw new BadRequestException($message, null, $e);
        }
    }

    /**
     * @return int The expiry in which the delegated token expires.
     */
    private function getExpiry(): int
    {
        $expiry = (new AuthTokenExpiry())->getExpiryForTokenType(AuthenticationToken::TYPE_MFA_DELEGATED_TOKEN);

        return (int)(new DateTime('+' . $expiry))->toUnixString();
    }
}
