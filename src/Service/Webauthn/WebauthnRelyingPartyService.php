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
namespace App\Service\Webauthn;

use App\Error\Exception\WebauthnRelyingPartyIdInvalidException;
use Cake\Core\Configure;
use Cake\Validation\Validation;
use Cose\Algorithm\Manager as CoseManager;
use Cose\Algorithm\Signature\ECDSA\ES256;
use Cose\Algorithm\Signature\RSA\RS256;
use RuntimeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Webauthn\AttestationStatement\AndroidKeyAttestationStatementSupport;
use Webauthn\AttestationStatement\AppleAttestationStatementSupport;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\FidoU2FAttestationStatementSupport;
use Webauthn\AttestationStatement\PackedAttestationStatementSupport;
use Webauthn\AttestationStatement\TPMAttestationStatementSupport;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\Denormalizer\WebauthnSerializerFactory;
use Webauthn\PublicKeyCredentialRpEntity;

/**
 * Universal WebAuthn relying-party plumbing. Concrete subclasses per flow (MFA today, SSO
 * passkey login later) inject the domain-specific policy interface so attestation-statement
 * support registration can follow the resolved `attestationConveyancePreference`.
 */
abstract class WebauthnRelyingPartyService
{
    private ?Serializer $serializer = null;
    private ?AttestationStatementSupportManager $asm = null;
    private ?CeremonyStepManagerFactory $csm = null;

    /**
     * @param \App\Service\Webauthn\WebauthnPolicyInterface $policy Resolved WebAuthn policy.
     */
    public function __construct(protected readonly WebauthnPolicyInterface $policy)
    {
    }

    /**
     * @return \Webauthn\PublicKeyCredentialRpEntity
     */
    public function getRpEntity(): PublicKeyCredentialRpEntity
    {
        return PublicKeyCredentialRpEntity::create(
            $this->getRpName(),
            $this->getRpId(),
        );
    }

    /**
     * @return string
     * @throws \App\Error\Exception\WebauthnRelyingPartyIdInvalidException if the rp id is an IP address
     */
    public function getRpId(): string
    {
        $host = parse_url((string)Configure::read('App.fullBaseUrl'), PHP_URL_HOST);
        if (!is_string($host) || $host === '') {
            throw new RuntimeException('App.fullBaseUrl is not configured or invalid.');
        }
        if (Validation::ip(trim($host, '[]'))) {
            throw new WebauthnRelyingPartyIdInvalidException(
                __('Passkeys require Passbolt to be reached through a domain name, not an IP address.'),
            );
        }

        return $host;
    }

    /**
     * @return string
     */
    public function getRpName(): string
    {
        $title = Configure::read('passbolt.meta.title');

        return is_string($title) && $title !== '' ? $title : 'Passbolt';
    }

    /**
     * @return \Symfony\Component\Serializer\SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        return $this->buildSerializer();
    }

    /**
     * @return \Symfony\Component\Serializer\Normalizer\DenormalizerInterface
     */
    public function getDenormalizer(): DenormalizerInterface
    {
        return $this->buildSerializer();
    }

    /**
     * @return \Symfony\Component\Serializer\Serializer
     */
    private function buildSerializer(): Serializer
    {
        if ($this->serializer === null) {
            /** @var \Symfony\Component\Serializer\Serializer $serializer */
            $serializer = (new WebauthnSerializerFactory(
                $this->getAttestationStatementSupportManager(),
            ))->create();
            $this->serializer = $serializer;
        }

        return $this->serializer;
    }

    /**
     * @return \Webauthn\AttestationStatement\AttestationStatementSupportManager
     */
    public function getAttestationStatementSupportManager(): AttestationStatementSupportManager
    {
        if ($this->asm === null) {
            $this->asm = new AttestationStatementSupportManager();
            if ($this->policy->getAttestationConveyancePreference() === WebauthnPolicyInterface::ATTESTATION_DIRECT) {
                $algorithms = CoseManager::create()->add(new ES256(), new RS256());
                $this->asm->add(PackedAttestationStatementSupport::create($algorithms));
                $this->asm->add(TPMAttestationStatementSupport::create());
                $this->asm->add(AndroidKeyAttestationStatementSupport::create());
                $this->asm->add(AppleAttestationStatementSupport::create());
                $this->asm->add(FidoU2FAttestationStatementSupport::create());
            }
        }

        return $this->asm;
    }

    /**
     * @return \Webauthn\AuthenticatorAttestationResponseValidator
     */
    public function getAttestationValidator(): AuthenticatorAttestationResponseValidator
    {
        return AuthenticatorAttestationResponseValidator::create(
            $this->getCsmFactory()->creationCeremony(),
        );
    }

    /**
     * @return \Webauthn\AuthenticatorAssertionResponseValidator
     */
    public function getAssertionValidator(): AuthenticatorAssertionResponseValidator
    {
        return AuthenticatorAssertionResponseValidator::create(
            $this->getCsmFactory()->requestCeremony(),
        );
    }

    /**
     * @return \Webauthn\CeremonyStep\CeremonyStepManagerFactory
     */
    private function getCsmFactory(): CeremonyStepManagerFactory
    {
        return $this->csm ??= new CeremonyStepManagerFactory();
    }
}
