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

use App\Test\Lib\AppTestCaseV5;
use Cake\Routing\Router;
use Passbolt\Metadata\Form\MetadataCleartextPrivateKeyForm;

class MetadataCleartextPrivateKeyFormTest extends AppTestCaseV5
{
    public function getDefaultData(): array
    {
        return [
            'object_type' => MetadataCleartextPrivateKeyForm::PASSBOLT_METADATA_PRIVATE_KEY,
            'domain' => Router::url('/', true),
            'armored_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key'),
            'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
            'passphrase' => '',
        ];
    }

    public function testMetadataCleartextPrivateKeyForm_Success(): void
    {
        $sut = new MetadataCleartextPrivateKeyForm();
        $data = $this->getDefaultData();
        $result = $sut->execute($data);
        $this->assertTrue($result);
    }

    public function testMetadataCleartextPrivateKeyForm_SuccessSanitized(): void
    {
        $sut = new MetadataCleartextPrivateKeyForm();
        $data = $this->getDefaultData();
        $data['useless'] = '🔥';
        $result = $sut->execute($data);
        $this->assertTrue($result);
        $this->assertEquals($this->getDefaultData(), $sut->getData());
    }

    public function testMetadataCleartextPrivateKeyForm_Error_Required(): void
    {
        $sut = new MetadataCleartextPrivateKeyForm();
        $data = [];
        $result = $sut->execute($data);
        $this->assertFalse($result);

        $notEmptyProps = ['object_type', 'armored_key', 'fingerprint', 'domain'];
        $errors = $sut->getErrors();
        foreach ($notEmptyProps as $prop) {
            $this->assertTrue(isset($errors[$prop]['_empty']));
        }
    }

    public function testMetadataCleartextPrivateKeyForm_Error_ObjectType(): void
    {
        $sut = new MetadataCleartextPrivateKeyForm();
        $data = $this->getDefaultData();

        $data['object_type'] = '🔥';
        $sut->execute($data);
        $this->assertNotEmpty($sut->getErrors()['object_type']['equals']);
    }

    public function testMetadataCleartextPrivateKeyForm_Error_Passphrase(): void
    {
        $sut = new MetadataCleartextPrivateKeyForm();
        $data = $this->getDefaultData();

        $data['passphrase'] = [];
        $sut->execute($data);
        $this->assertNotEmpty($sut->getErrors()['passphrase']['utf8Extended']);
    }

    public function testMetadataCleartextPrivateKeyForm_Error_Fingerprint(): void
    {
        $sut = new MetadataCleartextPrivateKeyForm();
        $data = $this->getDefaultData();

        $data['fingerprint'] = str_repeat('🔥', 52);
        $sut->execute($data);
        $this->assertNotEmpty($sut->getErrors()['fingerprint']['alphaNumeric']);
        $this->assertNotEmpty($sut->getErrors()['fingerprint']['custom']);
        $this->assertNotEmpty($sut->getErrors()['fingerprint']['maxLength']);
        $this->assertNotEmpty($sut->getErrors()['armored_key']['matchPrivateFingerprints']);
    }

    public function testMetadataCleartextPrivateKeyForm_Error_Domain(): void
    {
        $sut = new MetadataCleartextPrivateKeyForm();
        $data = $this->getDefaultData();

        $data['domain'] = '🔥';
        $sut->execute($data);
        $this->assertNotEmpty($sut->getErrors()['domain']['urlWithProtocol']);
        $this->assertNotEmpty($sut->getErrors()['domain']['equals']);
    }

    public function testMetadataCleartextPrivateKeyForm_Error_ArmoredKey(): void
    {
        $sut = new MetadataCleartextPrivateKeyForm();
        $data = $this->getDefaultData();

        $data['armored_key'] = '🔥';
        $sut->execute($data);
        $this->assertNotEmpty($sut->getErrors()['armored_key']['isPrivateKey']);
        $this->assertNotEmpty($sut->getErrors()['armored_key']['matchPrivateFingerprints']);
        $this->assertNotEmpty($sut->getErrors()['armored_key']['utf8']);
    }
}
