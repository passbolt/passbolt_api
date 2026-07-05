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
namespace Passbolt\PasskeyAuthentication\Service;

use App\Model\Entity\AuthenticationToken;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Webauthn\PublicKeyCredentialRequestOptions;

/**
 * Builds the PublicKeyCredentialRequestOptions for a passkey login ceremony, restricting
 * allowCredentials to the TARGET user's registered credentials, and persists the challenge in a
 * passkey_login authentication token bound to that user. There is no session at this point: the user
 * id is supplied by the client (the extension knows which account it is set up for).
 *
 * User verification is REQUIRED (the passkey stands in for the passphrase).
 */
class PasskeyStartAssertionService
{
    /**
     * Login ceremony timeout in milliseconds.
     *
     * @var int
     */
    public const TIMEOUT = 60000;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService
     */
    protected MfaWebauthnRelyingPartyService $relyingParty;

    /**
     * @var \Passbolt\PasskeyAuthentication\Service\PasskeyCredentialsService
     */
    protected PasskeyCredentialsService $credentials;

    /**
     * @var \Passbolt\PasskeyAuthentication\Service\PasskeyChallengeTokenService
     */
    protected PasskeyChallengeTokenService $challengeToken;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService|null $relyingParty Relying party service
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyCredentialsService|null $credentials Credentials service
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyChallengeTokenService|null $challengeToken Challenge token service
     */
    public function __construct(
        ?MfaWebauthnRelyingPartyService $relyingParty = null,
        ?PasskeyCredentialsService $credentials = null,
        ?PasskeyChallengeTokenService $challengeToken = null
    ) {
        $this->relyingParty = $relyingParty ?? new MfaWebauthnRelyingPartyService();
        $this->credentials = $credentials ?? new PasskeyCredentialsService($this->relyingParty);
        $this->challengeToken = $challengeToken ?? new PasskeyChallengeTokenService();
    }

    /**
     * Start a login ceremony for the given user.
     *
     * @param string $userId The target user id
     * @return array{options: string, token: \App\Model\Entity\AuthenticationToken}
     */
    public function start(string $userId): array
    {
        $options = PublicKeyCredentialRequestOptions::create(
            random_bytes(32),
            $this->relyingParty->getRpId(),
            $this->credentials->getDescriptors($userId),
            PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            self::TIMEOUT
        );

        $optionsJson = $this->relyingParty->getSerializer()->serialize($options, 'json', [
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            JsonEncode::OPTIONS => JSON_THROW_ON_ERROR,
        ]);

        $token = $this->challengeToken->create($userId, AuthenticationToken::TYPE_PASSKEY_LOGIN, $optionsJson);

        return ['options' => $optionsJson, 'token' => $token];
    }
}
