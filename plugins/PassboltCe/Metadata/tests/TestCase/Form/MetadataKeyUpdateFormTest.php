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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Test\TestCase\Form;

use Cake\I18n\FrozenTime;
use Cake\TestSuite\TestCase;
use Passbolt\Metadata\Form\MetadataKeyUpdateForm;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

class MetadataKeyUpdateFormTest extends TestCase
{
    use GpgMetadataKeysTestTrait;

    /**
     * @var MetadataKeyUpdateForm $form
     */
    protected $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new MetadataKeyUpdateForm();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->form);
    }

    public function getDefaultData(): array
    {
        return [
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
            'expired' => FrozenTime::yesterday()->setTimezone('Asia/Kolkata')->toIso8601String(),
        ];
    }

    public function testMetadataKeyUpdateForm_Success(): void
    {
        $this->assertTrue($this->form->execute($this->getDefaultData()));
    }

    public function testMetadataKeyUpdateForm_Error_Required(): void
    {
        $this->assertFalse($this->form->execute([]));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['fingerprint']['_empty']));
        $this->assertTrue(isset($errors['armored_key']['_empty']));
        $this->assertTrue(isset($errors['expired']['_empty']));
    }

    public function testMetadataKeyUpdateForm_Error_Empty(): void
    {
        $data = [
            'fingerprint' => '',
            'armored_key' => '',
            'expired' => '',
        ];
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['fingerprint']['_empty']));
        $this->assertTrue(isset($errors['armored_key']['_empty']));
        $this->assertTrue(isset($errors['expired']['_empty']));
    }

    public function testMetadataKeyUpdateForm_Error_NotRandomString(): void
    {
        $data = [
            'fingerprint' => '🔥',
            'armored_key' => '🔥',
            'expired' => '🔥',
            'deleted' => '🔥',
        ];
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['fingerprint']['alphaNumeric']));
        $this->assertTrue(isset($errors['armored_key']['ascii']));
        $this->assertTrue(isset($errors['expired']['dateTime']));
        $this->assertTrue(isset($errors['deleted']['isNullOnCreate']));
    }

    public function testMetadataKeyUpdateForm_Error_NotGoodFormat(): void
    {
        $data = [
            'fingerprint' => 'RRR',
            'armored_key' => 'RRR',
        ];
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['fingerprint']['isValidFingerprint']));
        $this->assertTrue(isset($errors['armored_key']['isParsableArmoredPublicKey']));
    }

    public function testMetadataKeyUpdateForm_Error_NotLogical(): void
    {
        $data = [
            'armored_key' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAF',
            'expired' => FrozenTime::yesterday()->setTimezone('Asia/Kolkata')->toIso8601String(),
        ];
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['fingerprint']['isMatchingKeyFingerprint']));
        $this->assertTrue(isset($errors['armored_key']['isPublicKeyRevoked']));
    }
}
