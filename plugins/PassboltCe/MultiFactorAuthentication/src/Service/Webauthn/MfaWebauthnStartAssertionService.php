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
 * @since         5.14.0
 */
namespace Passbolt\MultiFactorAuthentication\Service\Webauthn;

use App\Model\Entity\AuthenticationToken;
use App\Utility\UserAccessControl;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Webauthn\PublicKeyCredentialRequestOptions;

/**
 * Builds the PublicKeyCredentialRequestOptions for an authentication ceremony, restricting
 * allowCredentials to the user's registered credentials, and persists the challenge in a mfa_verify
 * authentication token.
 */
class MfaWebauthnStartAssertionService
{
    /**
     * Authentication ceremony timeout in milliseconds.
     *
     * @var int
     */
    public const TIMEOUT = 60000;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService
     */
    protected MfaWebauthnRelyingPartyService $relyingParty;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnCredentialsService
     */
    protected MfaWebauthnCredentialsService $credentials;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnChallengeTokenService
     */
    protected MfaWebauthnChallengeTokenService $challengeToken;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService|null $relyingParty Relying party service
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnCredentialsService|null $credentials Credentials service
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnChallengeTokenService|null $challengeToken Challenge token service
     */
    public function __construct(
        ?MfaWebauthnRelyingPartyService $relyingParty = null,
        ?MfaWebauthnCredentialsService $credentials = null,
        ?MfaWebauthnChallengeTokenService $challengeToken = null
    ) {
        $this->relyingParty = $relyingParty ?? new MfaWebauthnRelyingPartyService();
        $this->credentials = $credentials ?? new MfaWebauthnCredentialsService($this->relyingParty);
        $this->challengeToken = $challengeToken ?? new MfaWebauthnChallengeTokenService();
    }

    /**
     * Start an authentication ceremony.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @return array{options: string, token: \App\Model\Entity\AuthenticationToken}
     */
    public function start(UserAccessControl $uac): array
    {
        $options = PublicKeyCredentialRequestOptions::create(
            random_bytes(32),
            $this->relyingParty->getRpId(),
            $this->credentials->getDescriptors($uac->getId()),
            PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_PREFERRED,
            self::TIMEOUT
        );

        $optionsJson = $this->relyingParty->getSerializer()->serialize($options, 'json', [
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            JsonEncode::OPTIONS => JSON_THROW_ON_ERROR,
        ]);

        $token = $this->challengeToken->create($uac, AuthenticationToken::TYPE_MFA_VERIFY, $optionsJson);

        return ['options' => $optionsJson, 'token' => $token];
    }
}
