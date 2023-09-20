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

use App\Test\Lib\Model\FormatValidationTrait;
use Cake\Event\EventDispatcherTrait;
use Cake\TestSuite\TestCase;
use Faker\Factory;
use Passbolt\SmtpSettings\Form\EmailConfigurationForm;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Passbolt\SmtpSettings\Form\EmailConfigurationForm
 */
class EmailConfigurationFormTest extends TestCase
{
    use SmtpSettingsTestTrait;
    use EventDispatcherTrait;
    use FormatValidationTrait;

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

    /**
     * @dataProvider dataForClientFieldValidUnchanged
     */
    public function testEmailConfigurationForm_Client_Success($value)
    {
        $data = $this->getSmtpSettingsData('client', $value);
        $result = $this->form->execute($data);
        $this->assertTrue($result);
        $this->assertSame($value, $this->form->getData('client'));
    }

    /**
     * @dataProvider dataForClientFieldValidMappedToNull
     */
    public function testEmailConfigurationForm_Client_Success_Mapped_To_Null($value)
    {
        $data = $this->getSmtpSettingsData('client', $value);
        $result = $this->form->execute($data);
        $this->assertTrue($result);
        $this->assertSame(null, $this->form->getData('client'));
    }

    /**
     * @dataProvider dataForClientFieldInvalid
     */
    public function testEmailConfigurationForm_Client_Invalid($value, string $errorMessage)
    {
        $data = $this->getSmtpSettingsData('client', $value);
        $result = $this->form->execute($data);
        $this->assertFalse($result);
        $this->assertSame(['client' => ['isClientValid' => $errorMessage]], $this->form->getErrors());
    }

    public function testEmailConfigurationForm_Client_Is_Optional()
    {
        $data = $this->getSmtpSettingsData();
        unset($data['client']);
        $result = $this->form->execute($data);
        $this->assertTrue($result);
        $this->assertSame(null, $this->form->getData('client'));
    }

    public function testEmailConfigurationForm_Success_OnWebInstaller()
    {
        $data = $this->getSmtpSettingsData('authentication_method', 'invalid');
        $data['authentication_method'] = 'none';

        $result = $this->form->execute($data, ['validate' => 'webInstaller']);

        $this->assertTrue($result);
    }

    public function testEmailConfigurationForm_Error_OnWebInstaller_AuthenticationMethodFieldIsRequired()
    {
        $data = $this->getSmtpSettingsData();

        $result = $this->form->execute($data, ['validate' => 'webInstaller']);

        $this->assertFalse($result);
        $this->assertArrayHasKey('_required', $this->form->getErrors()['authentication_method']);
    }

    public function testEmailConfigurationForm_Error_OnWebInstaller_AuthenticationMethodFieldInList()
    {
        $data = $this->getSmtpSettingsData('authentication_method', 'invalid');

        $result = $this->form->execute($data, ['validate' => 'webInstaller']);

        $this->assertFalse($result);
        $this->assertArrayHasKey('inList', $this->form->getErrors()['authentication_method']);
    }

    /**
     * @dataProvider dataForUsernamePasswordValuesAuthenticationMethod
     */
    public function testEmailConfigurationForm_UsernamePasswordValues_AuthenticationMethod($authMethod, array $data)
    {
        $formData = $this->getSmtpSettingsData();
        $formData['authentication_method'] = $authMethod;
        $formData['username'] = $data['input']['username'];
        $formData['password'] = $data['input']['password'];

        $result = $this->form->execute($formData);

        $this->assertTrue($result);
        $this->assertSame($data['expected']['username'], $this->form->getData('username'));
        $this->assertSame($data['expected']['password'], $this->form->getData('password'));
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
            [new \stdClass(), null],
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

    public function dataForClientFieldValidUnchanged(): array
    {
        $faker = Factory::create();

        return [
            ['passbolt.com'],
            [null],
            [$faker->ipv4()],
            [$faker->localIpv4()],
            [$faker->ipv6()],
        ];
    }

    public function dataForClientFieldValidMappedToNull(): array
    {
        return [
            [''],
            [0],
            [[]],
            [false],
        ];
    }

    public function dataForClientFieldInvalid(): array
    {
        return [
            ['passbolt', 'The client should be a valid IP or a valid domain.'],
            [1, 'The client should be a valid IP or a valid domain.'],
            [$this, 'The client should be a valid IP or a valid domain.'],
        ];
    }

    public function dataForUsernamePasswordValuesAuthenticationMethod(): array
    {
        return [
            [
                'username_and_password',
                'data' => [
                    'input' => ['username' => '', 'password' => ''],
                    'expected' => ['username' => '', 'password' => ''],
                ],
            ],
            [
                'username_only',
                'data' => [
                    'input' => ['username' => 'ada', 'password' => ''],
                    'expected' => ['username' => 'ada', 'password' => null],
                ],
            ],
            [
                'username_only',
                'data' => [
                    'input' => ['username' => '', 'password' => null],
                    'expected' => ['username' => '', 'password' => null],
                ],
            ],
            [
                'none',
                'data' => [
                    'input' => ['username' => '', 'password' => ''],
                    'expected' => ['username' => null, 'password' => null],
                ],
            ],
        ];
    }
}
