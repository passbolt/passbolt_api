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
namespace Passbolt\MultiFactorAuthentication\Service\Webauthn;

use App\Model\Entity\AuthenticationToken;
use App\Utility\UserAccessControl;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cose\Algorithms;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\MfaWebauthnPolicyInterface;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialUserEntity;

class MfaWebauthnStartRegistrationService
{
    use LocatorAwareTrait;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService $rp Relying party service.
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaPolicies\MfaWebauthnPolicyInterface $policy WebAuthn policy.
     */
    public function __construct(
        private readonly MfaWebauthnRelyingPartyService $rp,
        private readonly MfaWebauthnPolicyInterface $policy,
    ) {
    }

    /**
     * Build creation options and persist them behind a ceremony handle.
     *
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @return array{handle: string, credentialCreationOptions: array}
     */
    public function start(UserAccessControl $uac): array
    {
        $this->rp->assertRpIdIsNotIpAddress();

        /** @var \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable $credentials */
        $credentials = $this->fetchTable('Passbolt/MultiFactorAuthentication.MfaWebauthnCredentials');

        $userEntity = PublicKeyCredentialUserEntity::create(
            $uac->getUsername(),
            $uac->getId(),
            $uac->getUsername(),
        );

        $options = PublicKeyCredentialCreationOptions::create(
            $this->rp->getRpEntity(),
            $userEntity,
            random_bytes(32),
            [
                PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_ES256),
                PublicKeyCredentialParameters::create('public-key', Algorithms::COSE_ALGORITHM_RS256),
            ],
            AuthenticatorSelectionCriteria::create(
                $this->policy->getAuthenticatorAttachment(),
                $this->policy->getUserVerification(),
                $this->policy->getResidentKey(),
            ),
            $this->policy->getAttestationConveyancePreference(),
            $credentials->descriptorsForUser($uac->getId()),
            $this->policy->getTimeoutMs(),
            null,
            $this->policy->getHints(),
        );

        $json = $this->rp->getSerializer()->serialize($options, 'json', ['skip_null_values' => true]);
        $token = (new MfaWebauthnChallengeTokenService())->create(
            $uac,
            AuthenticationToken::TYPE_MFA_SETUP,
            $json,
        );

        $optionsArray = json_decode($json, true, flags: JSON_THROW_ON_ERROR);
        $optionsArray['hints'] ??= [];

        return [
            'handle' => $token->token,
            'credentialCreationOptions' => $optionsArray,
        ];
    }
}
