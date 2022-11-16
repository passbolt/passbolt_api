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
 * @since         3.8.0
 */
namespace Passbolt\SmtpSettings\Test\TestCase\Form;

use Cake\TestSuite\TestCase;
use Passbolt\SmtpSettings\Form\EmailConfigurationForm;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

class EmailConfigurationFormTest extends TestCase
{
    use SmtpSettingsTestTrait;

    /**
     * @var \Passbolt\SmtpSettings\Form\EmailConfigurationForm
     */
    private $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new EmailConfigurationForm();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->form);
    }

    public function testEmailConfigurationForm_Valid()
    {
        $data = $this->getSmtpSettingsData();
        $result = $this->form->execute($data);
        $this->assertTrue($result);
    }

    /**
     * @dataProvider data_Valid_Field
     */
    public function testEmailConfigurationForm_ValidField(string $field, $value)
    {
        $data = $this->getSmtpSettingsData($field, $value);
        $result = $this->form->execute($data);
        $this->assertTrue($result);
        $this->assertSame($value, $this->form->getData($field));
    }

    /**
     * @dataProvider data_For_Tls_Mapping
     */
    public function testEmailConfigurationForm_mapTlsToTrueOrNull($value = null, $expectedValue = null)
    {
        $data = $this->getSmtpSettingsData('tls', $value);
        $this->form->execute($data);
        $this->assertSame($expectedValue, $this->form->getData('tls'));
    }

    /**
     * @dataProvider data_Invalid
     */
    public function testEmailConfigurationForm_InvalidFields(string $failingField, $value)
    {
        $data = $this->getSmtpSettingsData($failingField, $value);
        $result = $this->form->execute($data);
        $this->assertFalse($result);
        $this->assertArrayHasKey($failingField, $this->form->getErrors());
    }

    /**
     * @dataProvider data_Invalid
     * @dataProvider data_Invalid_On_Update
     */
    public function testEmailConfigurationForm_InvalidFields_On_Update(string $failingField, $value)
    {
        $data = $this->getSmtpSettingsData($failingField, $value);
        $result = $this->form->execute($data, ['validate' => 'update']);
        $this->assertFalse($result);
        $this->assertArrayHasKey($failingField, $this->form->getErrors());
    }

    public function data_Valid_Field(): array
    {
        return [
            ['username', null],
            ['sender_email', 'foo'],
            ['password', null],
            ['password', 'passwordwith"'],
            ['password', "passwordwith'"],
            ['port', '123'],
            ['port', 123],
            ['tls', true],
            ['tls', null],
        ];
    }

    public function data_For_Tls_Mapping(): array
    {
        return [
            [1, true],
            ['1', true],
            [true, true],
            [false, null],
            ['true', true],
            [null, null],
            [0, null],
            ['0', null],
            ['false', null],
            ['foo', null],
            [2, null],
            [new \StdClass(), null],
        ];
    }

    public function data_Invalid(): array
    {
        return [
            ['sender_name', ''],
            ['sender_email', ''],
            ['host', ''],
            ['port', 'abc'],
            ['port', 0],
            ['port', 1.2],
        ];
    }

    public function data_Invalid_On_Update(): array
    {
        return [
            ['sender_email', 'foo'],
        ];
    }
}
