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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Form\Webauthn;

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Form\Webauthn\WebauthnSetupForm;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAttestationService;
use Passbolt\MultiFactorAuthentication\Test\Lib\Utility\WebauthnCredentialTestUtility;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Form\Webauthn\WebauthnSetupForm
 */
class WebauthnSetupFormTest extends TestCase
{
    use TruncateDirtyTables;

    private WebauthnSetupForm $form;
    private MfaWebauthnVerifyAttestationService&MockObject $service;
    private UserAccessControl $uac;

    public function setUp(): void
    {
        parent::setUp();
        $this->uac = UserFactory::make()->user()->persistedUAC();
        $this->service = $this->createMock(MfaWebauthnVerifyAttestationService::class);
        $this->form = new WebauthnSetupForm($this->uac, $this->service);
    }

    public function tearDown(): void
    {
        unset($this->form, $this->service, $this->uac);
        parent::tearDown();
    }

    public function testWebauthnSetupForm_Validates_FullyPopulatedPayload(): void
    {
        $this->assertTrue($this->form->validate([
            'handle' => UuidFactory::uuid(),
            'credential' => WebauthnCredentialTestUtility::getDummyPayload(),
            'name' => 'My Key',
        ]));
    }

    public function testWebauthnSetupForm_RejectsMissingName(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => UuidFactory::uuid(),
            'credential' => ['id' => 'abc'],
        ]));
        $this->assertArrayHasKey('name', $this->form->getErrors());
    }

    public function testWebauthnSetupForm_RejectsEmptyName(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => UuidFactory::uuid(),
            'credential' => ['id' => 'abc'],
            'name' => '',
        ]));
        $this->assertArrayHasKey('name', $this->form->getErrors());
    }

    public function testWebauthnSetupForm_RejectsMissingHandle(): void
    {
        $this->assertFalse($this->form->validate([
            'credential' => ['id' => 'abc'],
        ]));
        $this->assertArrayHasKey('handle', $this->form->getErrors());
    }

    public function testWebauthnSetupForm_RejectsEmptyHandle(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => '',
            'credential' => ['id' => 'abc'],
        ]));
        $this->assertArrayHasKey('handle', $this->form->getErrors());
    }

    public function testWebauthnSetupForm_RejectsNonUuidHandle(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => 'not-a-uuid',
            'credential' => ['id' => 'abc'],
        ]));
        $this->assertArrayHasKey('handle', $this->form->getErrors());
    }

    public function testWebauthnSetupForm_RejectsMissingCredential(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => UuidFactory::uuid(),
        ]));
        $this->assertArrayHasKey('credential', $this->form->getErrors());
    }

    public function testWebauthnSetupForm_RejectsNonArrayCredential(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => UuidFactory::uuid(),
            'credential' => 'not-an-array',
        ]));
        $this->assertArrayHasKey('credential', $this->form->getErrors());
    }

    public function testWebauthnSetupForm_RejectsEmptyCredentialArray(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => UuidFactory::uuid(),
            'credential' => [],
        ]));
        $this->assertArrayHasKey('credential', $this->form->getErrors());
    }

    public function testWebauthnSetupForm_RejectsNameOver255Chars(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => UuidFactory::uuid(),
            'credential' => ['id' => 'abc'],
            'name' => str_repeat('a', 256),
        ]));
        $this->assertArrayHasKey('name', $this->form->getErrors());
    }

    public function testWebauthnSetupForm_RejectsNonUtf8Name(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => UuidFactory::uuid(),
            'credential' => ['id' => 'abc'],
            'name' => "\xC3\x28",
        ]));
        $this->assertArrayHasKey('name', $this->form->getErrors());
    }

    public function testWebauthnSetupForm_Execute_ThrowsWebauthnSpecificMessageOnValidationFailure(): void
    {
        $this->service->expects($this->never())->method('verify');

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('The WebAuthn setup payload is not valid.');
        $this->form->execute(['credential' => ['id' => 'abc']]);
    }

    public function testWebauthnSetupForm_Execute_PassesDataToServiceWithoutCasts(): void
    {
        $handle = UuidFactory::uuid();
        $credential = WebauthnCredentialTestUtility::getDummyPayload(['rawId' => 'zzz']);

        $this->service->expects($this->once())
            ->method('verify')
            ->with($this->uac, $handle, $credential, 'My Key');

        $this->assertTrue($this->form->execute([
            'handle' => $handle,
            'credential' => $credential,
            'name' => 'My Key',
        ]));
    }
}
