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
use App\Utility\UserAccessControl;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialUserEntity;

/**
 * Builds the PublicKeyCredentialCreationOptions for a passkey enrollment ceremony, persists the
 * challenge in a passkey_setup authentication token, and returns both the serialized options (for the
 * client) and the challenge token (whose id the client echoes back on finish).
 *
 * User verification is REQUIRED: the passkey stands in for the passphrase, so the authenticator must
 * verify the user (PIN / biometric) rather than merely prove presence.
 */
class PasskeyStartRegistrationService
{
    /**
     * ES256 COSE algorithm identifier.
     *
     * @var int
     */
    public const ALG_ES256 = -7;

    /**
     * RS256 COSE algorithm identifier.
     *
     * @var int
     */
    public const ALG_RS256 = -257;

    /**
     * Enrollment ceremony timeout in milliseconds.
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
     * Start an enrollment ceremony.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @return array{options: string, token: \App\Model\Entity\AuthenticationToken}
     */
    public function start(UserAccessControl $uac): array
    {
        $options = $this->buildOptions($uac);
        $optionsJson = $this->relyingParty->getSerializer()->serialize($options, 'json', [
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            JsonEncode::OPTIONS => JSON_THROW_ON_ERROR,
        ]);

        $token = $this->challengeToken->create($uac->getId(), AuthenticationToken::TYPE_PASSKEY_SETUP, $optionsJson);

        return ['options' => $optionsJson, 'token' => $token];
    }

    /**
     * Build the creation options for the given user.
     *
     * @param \App\Utility\UserAccessControl $uac User access control
     * @return \Webauthn\PublicKeyCredentialCreationOptions
     */
    protected function buildOptions(UserAccessControl $uac): PublicKeyCredentialCreationOptions
    {
        $userEntity = PublicKeyCredentialUserEntity::create(
            $uac->getUsername(),
            $uac->getId(),
            $uac->getUsername()
        );

        // Do NOT require a discoverable (resident) credential: a platform authenticator keeps only one
        // resident credential per (rp, user), so requiring resident makes "enrol another passkey"
        // fail with InvalidStateError on the same device. Login already knows the user (the local kit
        // supplies the user id) and sends allowCredentials, so non-resident credentials work fine and
        // several can be enrolled on the same authenticator — like the MFA security keys.
        $authenticatorSelection = AuthenticatorSelectionCriteria::create(
            null,
            AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_REQUIRED,
            AuthenticatorSelectionCriteria::RESIDENT_KEY_REQUIREMENT_DISCOURAGED
        );

        return PublicKeyCredentialCreationOptions::create(
            $this->relyingParty->getRpEntity(),
            $userEntity,
            random_bytes(32),
            [
                PublicKeyCredentialParameters::createPk(self::ALG_ES256),
                PublicKeyCredentialParameters::createPk(self::ALG_RS256),
            ],
            $authenticatorSelection,
            PublicKeyCredentialCreationOptions::ATTESTATION_CONVEYANCE_PREFERENCE_NONE,
            $this->credentials->getDescriptors($uac->getId()),
            self::TIMEOUT
        );
    }
}
