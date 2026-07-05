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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\Webauthn;

use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\TestSuite\TestCase;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService;
use Symfony\Component\Serializer\SerializerInterface;
use Webauthn\PublicKeyCredentialRpEntity;

/**
 * @group mfa
 * @group webauthn
 */
class MfaWebauthnRelyingPartyServiceTest extends TestCase
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnRelyingPartyService
     */
    protected MfaWebauthnRelyingPartyService $service;

    /**
     * @var mixed
     */
    protected $originalBaseUrl;

    /**
     * @var mixed
     */
    protected $originalMetaTitle;

    public function setUp(): void
    {
        parent::setUp();
        $this->originalBaseUrl = Configure::read('App.fullBaseUrl');
        $this->originalMetaTitle = Configure::read('passbolt.meta.title');
        $this->service = new MfaWebauthnRelyingPartyService();
    }

    public function tearDown(): void
    {
        Configure::write('App.fullBaseUrl', $this->originalBaseUrl);
        Configure::write('passbolt.meta.title', $this->originalMetaTitle);
        parent::tearDown();
    }

    public function testGetRpId_IsTheBaseUrlHost(): void
    {
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com');

        $this->assertSame('passbolt.example.com', $this->service->getRpId());
    }

    public function testGetOrigin_IncludesSchemeHostAndPort(): void
    {
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com:8443');

        $this->assertSame('https://passbolt.example.com:8443', $this->service->getOrigin());
        $this->assertSame('passbolt.example.com', $this->service->getRpId());
        // The ceremony host is the relying party id.
        $this->assertSame($this->service->getRpId(), $this->service->getHost());
    }

    public function testGetRpName_DefaultsToPassbolt(): void
    {
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com');
        Configure::delete('passbolt.meta.title');

        $this->assertSame('Passbolt', $this->service->getRpName());
    }

    public function testGetRpName_UsesConfiguredTitle(): void
    {
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com');
        Configure::write('passbolt.meta.title', 'ACME Passwords');

        $this->assertSame('ACME Passwords', $this->service->getRpName());
    }

    public function testGetRpEntity_UsesNameAndId(): void
    {
        Configure::write('App.fullBaseUrl', 'https://passbolt.example.com');
        Configure::write('passbolt.meta.title', 'ACME Passwords');

        $entity = $this->service->getRpEntity();

        $this->assertInstanceOf(PublicKeyCredentialRpEntity::class, $entity);
        $this->assertSame('passbolt.example.com', $entity->id);
        $this->assertSame('ACME Passwords', $entity->name);
    }

    public function testGetSerializer_ReturnsASerializer(): void
    {
        $this->assertInstanceOf(SerializerInterface::class, $this->service->getSerializer());
    }

    public function testGetRpId_ThrowsWhenBaseUrlHasNoHost(): void
    {
        // A non-empty base url with no host (so the Router fallback is not used) cannot yield an rp id.
        Configure::write('App.fullBaseUrl', 'not-a-url');

        $this->expectException(InternalErrorException::class);
        $this->service->getRpId();
    }
}
