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

namespace Passbolt\Metadata\TestCase\Form;

use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\TestCase;
use Passbolt\Metadata\Form\MetadataKeyForm;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;

class MetadataKeyFormTest extends TestCase
{
    use GpgMetadataKeysTestTrait;

    /**
     * @var MetadataKeyForm $form
     */
    protected $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new MetadataKeyForm();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->form);
    }

    public function getDefaultData(): array
    {
        $data = $this->getMetadataKeyInfo();

        return [
            'fingerprint' => $data['fingerprint'],
            'armored_key' => $data['public_key'],
            'metadata_private_keys' => [[
                'user_id' => UuidFactory::uuid(),
                'data' => $this->getEncryptedMetadataPrivateKeyFoUser(),
            ]],
        ];
    }

    public function testMetadataKeyForm_Success(): void
    {
        $this->assertTrue($this->form->execute($this->getDefaultData()));
    }

    public function testMetadataKeyForm_Error_Required(): void
    {
        $this->assertFalse($this->form->execute([]));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['fingerprint']['_required']));
        $this->assertTrue(isset($errors['armored_key']['_required']));
        $this->assertTrue(isset($errors['metadata_private_keys']['_required']));
    }

    public function testMetadataKeyForm_Error_Empty(): void
    {
        $data = [
            'fingerprint' => '',
            'armored_key' => '',
            'metadata_private_keys' => [],
            'expired' => '',
            'deleted' => '',
        ];
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['fingerprint']['_empty']));
        $this->assertTrue(isset($errors['armored_key']['_empty']));
        $this->assertTrue(isset($errors['metadata_private_keys']['hasAtLeast']));
        $this->assertFalse(isset($errors['expired']));
        $this->assertFalse(isset($errors['deleted']));
    }

    public function testMetadataKeyForm_Error_ExpiredDeleteNotEmpty(): void
    {
        $data = [
            'expired' => FrozenTime::yesterday()->format('Y-m-d H:i:s'),
            'deleted' => FrozenTime::yesterday()->format('Y-m-d H:i:s'),
        ];
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['expired']['isNullOnCreate']));
        $this->assertTrue(isset($errors['deleted']['isNullOnCreate']));
    }

    public function testMetadataKeyForm_Error_NotRandomString(): void
    {
        $data = [
            'fingerprint' => '🔥',
            'armored_key' => '🔥',
            'metadata_private_keys' => '🔥',
        ];
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['fingerprint']['alphaNumeric']));
        $this->assertTrue(isset($errors['armored_key']['ascii']));
        $this->assertTrue(isset($errors['metadata_private_keys']['array']));
    }

    public function testMetadataKeyForm_Error_NotRandomStringNested(): void
    {
        $data = [
            'metadata_private_keys' => [[]],
        ];
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['metadata_private_keys'][0]['user_id']['_required']));
        $this->assertTrue(isset($errors['metadata_private_keys'][0]['data']['_required']));
    }

    public function testMetadataKeyForm_Error_NestedEmpty(): void
    {
        $data = [
            'metadata_private_keys' => [[
                'user_id' => null,
                'data' => '',
            ]],
        ];
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        // allow null, for server key
        $this->assertFalse(isset($errors['metadata_private_keys'][0]['user_id']));
        $this->assertTrue(isset($errors['metadata_private_keys'][0]['data']['_empty']));
    }

    public function testMetadataKeyForm_Error_NestNotRandomString(): void
    {
        $data = [
            'metadata_private_keys' => [[
                'user_id' => '🔥',
                'data' => '🔥',
            ]],
        ];
        $this->assertFalse($this->form->execute($data));
        $errors = $this->form->getErrors();
        $this->assertTrue(isset($errors['metadata_private_keys'][0]['user_id']['uuid']));
        $this->assertTrue(isset($errors['metadata_private_keys'][0]['data']['ascii']));
    }
}
