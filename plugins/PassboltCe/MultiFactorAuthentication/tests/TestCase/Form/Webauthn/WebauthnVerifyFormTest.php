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

use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Form\Webauthn\WebauthnVerifyForm;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAssertionService;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Form\Webauthn\WebauthnVerifyForm
 */
class WebauthnVerifyFormTest extends TestCase
{
    use TruncateDirtyTables;

    private WebauthnVerifyForm $form;
    private MfaWebauthnVerifyAssertionService&MockObject $service;

    public function setUp(): void
    {
        parent::setUp();
        $uac = UserFactory::make()->user()->persistedUAC();
        $this->service = $this->createMock(MfaWebauthnVerifyAssertionService::class);
        $this->form = new WebauthnVerifyForm($uac, $this->service);
    }

    public function tearDown(): void
    {
        unset($this->form, $this->service);
        parent::tearDown();
    }

    public function testWebauthnVerifyForm_Validates_FullyPopulatedPayload(): void
    {
        $this->assertTrue($this->form->validate([
            'handle' => UuidFactory::uuid(),
            'credential' => ['id' => 'abc'],
        ]));
    }

    public function testWebauthnVerifyForm_RejectsMissingHandle(): void
    {
        $this->assertFalse($this->form->validate([
            'credential' => ['id' => 'abc'],
        ]));
        $this->assertArrayHasKey('handle', $this->form->getErrors());
    }

    public function testWebauthnVerifyForm_RejectsNonUuidHandle(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => 'not-a-uuid',
            'credential' => ['id' => 'abc'],
        ]));
        $this->assertArrayHasKey('handle', $this->form->getErrors());
    }

    public function testWebauthnVerifyForm_RejectsEmptyHandle(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => '',
            'credential' => ['id' => 'abc'],
        ]));
        $this->assertArrayHasKey('handle', $this->form->getErrors());
    }

    public function testWebauthnVerifyForm_RejectsMissingCredential(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => UuidFactory::uuid(),
        ]));
        $this->assertArrayHasKey('credential', $this->form->getErrors());
    }

    public function testWebauthnVerifyForm_RejectsNonArrayCredential(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => UuidFactory::uuid(),
            'credential' => 'not-an-array',
        ]));
        $this->assertArrayHasKey('credential', $this->form->getErrors());
    }

    public function testWebauthnVerifyForm_RejectsEmptyCredentialArray(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => UuidFactory::uuid(),
            'credential' => [],
        ]));
        $this->assertArrayHasKey('credential', $this->form->getErrors());
    }

    public function testWebauthnVerifyForm_Execute_DispatchesToServiceWithArgs(): void
    {
        $handle = UuidFactory::uuid();
        $credential = ['id' => 'abc', 'response' => ['clientDataJSON' => 'x']];
        $this->service->expects($this->once())
            ->method('verify')
            ->with(
                $this->isInstanceOf(UserAccessControl::class),
                $handle,
                $credential,
            );

        $this->assertTrue($this->form->execute([
            'handle' => $handle,
            'credential' => $credential,
        ]));
    }
}
