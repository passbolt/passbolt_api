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

use Cake\TestSuite\TestCase;
use Passbolt\MultiFactorAuthentication\Form\Webauthn\WebauthnCredentialPayloadForm;
use Passbolt\MultiFactorAuthentication\Test\Lib\Utility\WebauthnCredentialTestUtility;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Form\Webauthn\WebauthnCredentialPayloadForm
 */
class WebauthnCredentialPayloadFormTest extends TestCase
{
    private WebauthnCredentialPayloadForm $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new WebauthnCredentialPayloadForm();
    }

    public function tearDown(): void
    {
        unset($this->form);
        parent::tearDown();
    }

    public function testWebauthnCredentialPayloadForm_Validates_FullyPopulatedPayload(): void
    {
        $this->assertTrue($this->form->validate(WebauthnCredentialTestUtility::getDummyPayload()));
        $this->assertSame([], $this->form->getErrors());
    }

    public function testWebauthnCredentialPayloadForm_RejectsMissingId(): void
    {
        $payload = WebauthnCredentialTestUtility::getDummyPayload();
        unset($payload['id']);

        $this->assertFalse($this->form->validate($payload));
        $this->assertArrayHasKey('id', $this->form->getErrors());
    }

    public function testWebauthnCredentialPayloadForm_RejectsMissingRawId(): void
    {
        $payload = WebauthnCredentialTestUtility::getDummyPayload();
        unset($payload['rawId']);

        $this->assertFalse($this->form->validate($payload));
        $this->assertArrayHasKey('rawId', $this->form->getErrors());
    }

    public function testWebauthnCredentialPayloadForm_RejectsMissingType(): void
    {
        $payload = WebauthnCredentialTestUtility::getDummyPayload();
        unset($payload['type']);

        $this->assertFalse($this->form->validate($payload));
        $this->assertArrayHasKey('type', $this->form->getErrors());
    }

    public function testWebauthnCredentialPayloadForm_RejectsMissingResponse(): void
    {
        $payload = WebauthnCredentialTestUtility::getDummyPayload();
        unset($payload['response']);

        $this->assertFalse($this->form->validate($payload));
        $this->assertArrayHasKey('response', $this->form->getErrors());
    }

    public function testWebauthnCredentialPayloadForm_RejectsUnknownType(): void
    {
        $payload = WebauthnCredentialTestUtility::getDummyPayload(['type' => 'not-public-key']);

        $this->assertFalse($this->form->validate($payload));
        $this->assertArrayHasKey('inList', $this->form->getErrors()['type']);
    }

    public function testWebauthnCredentialPayloadForm_RejectsEmptyId(): void
    {
        $payload = WebauthnCredentialTestUtility::getDummyPayload(['id' => '']);

        $this->assertFalse($this->form->validate($payload));
        $this->assertArrayHasKey('id', $this->form->getErrors());
    }

    public function testWebauthnCredentialPayloadForm_RejectsEmptyResponseArray(): void
    {
        $payload = WebauthnCredentialTestUtility::getDummyPayload(['response' => []]);

        $this->assertFalse($this->form->validate($payload));
        $this->assertArrayHasKey('response', $this->form->getErrors());
    }
}
