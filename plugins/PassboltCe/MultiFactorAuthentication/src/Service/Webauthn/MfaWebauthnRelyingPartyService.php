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

use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\Routing\Router;
use Symfony\Component\Serializer\SerializerInterface;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\Denormalizer\WebauthnSerializerFactory;
use Webauthn\PublicKeyCredentialRpEntity;

/**
 * Centralises the WebAuthn relying party configuration and the web-auth/webauthn-lib building blocks
 * (serializer + ceremony validators) shared by the registration and authentication services.
 *
 * The relying party id, name and expected origin are derived from the application base url so that
 * no organization level configuration is required. This keeps the provider self-contained (unlike
 * Yubikey/Duo which rely on third party credentials).
 */
class MfaWebauthnRelyingPartyService
{
    /**
     * Default relying party name displayed by the authenticator when no application name is set.
     *
     * @var string
     */
    public const DEFAULT_RP_NAME = 'Passbolt';

    /**
     * Get the relying party id (the effective/registrable domain), e.g. "passbolt.example.com".
     *
     * @return string
     */
    public function getRpId(): string
    {
        $host = parse_url($this->getBaseUrl(), PHP_URL_HOST);
        if (!is_string($host) || $host === '') {
            throw new InternalErrorException('Could not determine the WebAuthn relying party id.');
        }

        return $host;
    }

    /**
     * Get the relying party display name.
     *
     * @return string
     */
    public function getRpName(): string
    {
        $name = Configure::read('passbolt.meta.title');
        if (!is_string($name) || trim($name) === '') {
            return self::DEFAULT_RP_NAME;
        }

        return $name;
    }

    /**
     * Get the relying party entity used to build the creation options.
     *
     * @return \Webauthn\PublicKeyCredentialRpEntity
     */
    public function getRpEntity(): PublicKeyCredentialRpEntity
    {
        return PublicKeyCredentialRpEntity::create($this->getRpName(), $this->getRpId());
    }

    /**
     * Get the expected origin, i.e. scheme://host[:port], e.g. "https://passbolt.example.com".
     *
     * @return string
     */
    public function getOrigin(): string
    {
        $parts = parse_url($this->getBaseUrl());
        if (!is_array($parts) || !isset($parts['scheme'], $parts['host'])) {
            throw new InternalErrorException('Could not determine the WebAuthn expected origin.');
        }
        $origin = $parts['scheme'] . '://' . $parts['host'];
        if (isset($parts['port'])) {
            $origin .= ':' . $parts['port'];
        }

        return $origin;
    }

    /**
     * Get the request host passed to the ceremony validators.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->getRpId();
    }

    /**
     * Get the webauthn-lib serializer able to (de)serialize options, credentials and credential records.
     *
     * @return \Symfony\Component\Serializer\SerializerInterface
     */
    public function getSerializer(): SerializerInterface
    {
        return (new WebauthnSerializerFactory($this->getAttestationStatementSupportManager()))->create();
    }

    /**
     * Get the attestation (registration) ceremony validator.
     *
     * @return \Webauthn\AuthenticatorAttestationResponseValidator
     */
    public function getAttestationValidator(): AuthenticatorAttestationResponseValidator
    {
        return AuthenticatorAttestationResponseValidator::create(
            $this->getCeremonyStepManagerFactory()->creationCeremony()
        );
    }

    /**
     * Get the assertion (authentication) ceremony validator.
     *
     * @return \Webauthn\AuthenticatorAssertionResponseValidator
     */
    public function getAssertionValidator(): AuthenticatorAssertionResponseValidator
    {
        return AuthenticatorAssertionResponseValidator::create(
            $this->getCeremonyStepManagerFactory()->requestCeremony()
        );
    }

    /**
     * Build the ceremony step manager factory configured with the expected origin.
     *
     * @return \Webauthn\CeremonyStep\CeremonyStepManagerFactory
     */
    protected function getCeremonyStepManagerFactory(): CeremonyStepManagerFactory
    {
        $factory = new CeremonyStepManagerFactory();
        $factory->setAllowedOrigins([$this->getOrigin()]);

        return $factory;
    }

    /**
     * We only accept the "none" attestation conveyance: this is a second factor for a self-hosted
     * application, so authenticator model attestation adds complexity without a security benefit
     * (origin, user verification and signature counter are enforced regardless).
     *
     * @return \Webauthn\AttestationStatement\AttestationStatementSupportManager
     */
    protected function getAttestationStatementSupportManager(): AttestationStatementSupportManager
    {
        return new AttestationStatementSupportManager([new NoneAttestationStatementSupport()]);
    }

    /**
     * Get the application base url.
     *
     * @return string
     */
    protected function getBaseUrl(): string
    {
        $baseUrl = Configure::read('App.fullBaseUrl');
        if (!is_string($baseUrl) || $baseUrl === '') {
            $baseUrl = Router::fullBaseUrl();
        }

        return $baseUrl;
    }
}
