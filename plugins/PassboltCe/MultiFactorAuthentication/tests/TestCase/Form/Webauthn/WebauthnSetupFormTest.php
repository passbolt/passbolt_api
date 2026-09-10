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
use App\Utility\UuidFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Form\Webauthn\WebauthnSetupForm;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnVerifyAttestationService;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Form\Webauthn\WebauthnSetupForm
 */
class WebauthnSetupFormTest extends TestCase
{
    use TruncateDirtyTables;

    private WebauthnSetupForm $form;
    private MfaWebauthnVerifyAttestationService $service;

    public function setUp(): void
    {
        parent::setUp();
        $uac = UserFactory::make()->user()->persistedUAC();
        $this->service = $this->createMock(MfaWebauthnVerifyAttestationService::class);
        $this->form = new WebauthnSetupForm($uac, $this->service);
    }

    public function tearDown(): void
    {
        unset($this->form, $this->service);
        parent::tearDown();
    }

    public function testWebauthnSetupForm_Validates_FullyPopulatedPayload(): void
    {
        $this->assertTrue($this->form->validate([
            'handle' => UuidFactory::uuid(),
            'credential' => ['id' => 'abc'],
            'name' => 'My Key',
        ]));
    }

    public function testWebauthnSetupForm_Validates_WithoutOptionalName(): void
    {
        $this->assertTrue($this->form->validate([
            'handle' => UuidFactory::uuid(),
            'credential' => ['id' => 'abc'],
        ]));
    }

    public function testWebauthnSetupForm_RejectsMissingHandle(): void
    {
        $this->assertFalse($this->form->validate([
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

    public function testWebauthnSetupForm_RejectsNameOver255Chars(): void
    {
        $this->assertFalse($this->form->validate([
            'handle' => UuidFactory::uuid(),
            'credential' => ['id' => 'abc'],
            'name' => str_repeat('a', 256),
        ]));
        $this->assertArrayHasKey('name', $this->form->getErrors());
    }
}
