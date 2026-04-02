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
use Cake\Utility\Text;
use Faker\Factory;
use Passbolt\SmtpSettings\Form\EmailConfigurationForm;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;
use stdClass;

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
        unset($data['authentication_method']);

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

    public static function data_Valid_Field(): array
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

    public static function data_For_Tls_Mapping(): array
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
            [new stdClass(), null],
        ];
    }

    public static function data_Invalid(): array
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

    public static function data_Invalid_On_Update(): array
    {
        return [
            ['sender_email', 'foo'],
        ];
    }

    public static function dataForClientFieldValidUnchanged(): array
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

    public static function dataForClientFieldValidMappedToNull(): array
    {
        return [
            [''],
            [0],
            [[]],
            [false],
        ];
    }

    public static function dataForClientFieldInvalid(): array
    {
        return [
            ['passbolt', 'The client should be a valid IP or a valid domain.'],
            [1, 'The client should be a valid IP or a valid domain.'],
            [new stdClass(), 'The client should be a valid IP or a valid domain.'],
        ];
    }

    public static function dataForUsernamePasswordValuesAuthenticationMethod(): array
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

    // ---------------------------
    // OAuth2 tests
    // ---------------------------

    public function testEmailConfigurationForm_Oauth2Execute_AllFieldsValid(): void
    {
        $data = $this->getSmtpSettingsData();
        $data['authentication_method'] = 'oauth2_client_credentials';
        $data['tenant_id'] = Text::uuid();
        $data['client_id'] = Text::uuid();
        $data['client_secret'] = 'my-secret';
        $data['oauth_username'] = 'user@example.com';

        $result = $this->form->execute($data);
        $this->assertTrue($result);
    }

    public static function emptyOAuth2FieldsProvider(): array
    {
        return [
            [
                [
                    'tenant_id' => '',
                    'client_id' => '',
                    'client_secret' => '',
                    'oauth_username' => '',
                ],
            ],
            [
                [
                    'tenant_id' => null,
                    'client_id' => null,
                    'client_secret' => null,
                    'oauth_username' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider emptyOAuth2FieldsProvider
     * @param array $emptyData Data to override.
     * @return void
     */
    public function testEmailConfigurationForm_Oauth2Fields_PermissiveWhenEmpty(array $emptyData): void
    {
        $data = array_merge($this->getSmtpSettingsData(), $emptyData);
        $result = $this->form->execute($data);
        $this->assertTrue($result);
    }

    public static function invalidOAuth2ValuesProvider(): array
    {
        return [
            [
                [
                    'tenant_id' => 'not-a-uuid',
                    'client_id' => 'not-a-uuid',
                    'client_secret' => 'super-secret',
                    'oauth_username' => 'user@example.com',
                ],
                ['tenant_id', 'client_id'],
            ],
            [
                [
                    'tenant_id' => Text::uuid(),
                    'client_id' => Text::uuid(),
                    'client_secret' => str_repeat('a', 257),
                    'oauth_username' => 'user@example.com',
                ],
                ['client_secret'],
            ],
            [
                [
                    'tenant_id' => Text::uuid(),
                    'client_id' => Text::uuid(),
                    'client_secret' => 'super-secret',
                    'oauth_username' => 'not-an-email',
                ],
                ['oauth_username'],
            ],
            [
                [
                    'tenant_id' => Text::uuid(),
                    'client_id' => Text::uuid(),
                    'client_secret' => 'super-secret',
                    'oauth_username' => str_repeat('a', 246) . '@example.com',
                ],
                ['oauth_username'],
            ],
        ];
    }

    /**
     * @dataProvider invalidOAuth2ValuesProvider
     * @param array $invalidData Invalid data to test.
     * @param array $errorFields Field keys with errors.
     * @return void
     */
    public function testEmailConfigurationForm_InvalidOauth2Data(array $invalidData, array $errorFields): void
    {
        $data = array_merge($this->getSmtpSettingsData(), $invalidData);
        $result = $this->form->execute($data);
        $this->assertFalse($result);
        $errors = $this->form->getErrors();
        $this->assertCount(count($errorFields), $errors);
        foreach ($errorFields as $field) {
            $this->assertArrayHasKey($field, $errors);
        }
    }

    public function testEmailConfigurationForm_Oauth2Execute_RequiresAllFields(): void
    {
        $data = $this->getSmtpSettingsData();
        $data['authentication_method'] = 'oauth2_client_credentials';
        // Missing all OAuth2 fields
        $result = $this->form->execute($data);
        $this->assertFalse($result);
        $errors = $this->form->getErrors();
        $this->assertArrayHasKey('tenant_id', $errors);
        $this->assertArrayHasKey('client_id', $errors);
        $this->assertArrayHasKey('client_secret', $errors);
        $this->assertArrayHasKey('oauth_username', $errors);
    }

    public static function dataForOauth2MissingRequiredField(): array
    {
        return [
            ['tenant_id'],
            ['client_id'],
            ['client_secret'],
            ['oauth_username'],
        ];
    }

    /**
     * @dataProvider dataForOauth2MissingRequiredField
     */
    public function testEmailConfigurationForm_Oauth2Execute_MissingField(string $missingField): void
    {
        $data = $this->getSmtpSettingsData();
        $data['authentication_method'] = 'oauth2_client_credentials';
        $data['tenant_id'] = Text::uuid();
        $data['client_id'] = Text::uuid();
        $data['client_secret'] = 'my-secret';
        $data['oauth_username'] = 'user@example.com';
        unset($data[$missingField]);

        $result = $this->form->execute($data);
        $this->assertFalse($result);
        $this->assertArrayHasKey($missingField, $this->form->getErrors());
    }

    public function testEmailConfigurationForm_FilterOauth2Fields_Oauth2NullifiesUsernamePassword(): void
    {
        $data = $this->getSmtpSettingsData();
        $data['authentication_method'] = 'oauth2_client_credentials';
        $data['tenant_id'] = Text::uuid();
        $data['client_id'] = Text::uuid();
        $data['client_secret'] = 'my-secret';
        $data['oauth_username'] = 'user@example.com';
        $data['username'] = 'should-be-nullified';
        $data['password'] = 'should-be-nullified';

        $this->form->execute($data);
        $this->assertNull($this->form->getData('username'));
        $this->assertNull($this->form->getData('password'));
    }

    public function testEmailConfigurationForm_FilterOauth2Fields_NonOauth2NullifiesOauth2Fields(): void
    {
        $data = $this->getSmtpSettingsData();
        $data['authentication_method'] = 'username_and_password';
        $data['tenant_id'] = Text::uuid();
        $data['client_id'] = Text::uuid();
        $data['client_secret'] = 'my-secret';
        $data['oauth_username'] = 'user@example.com';

        $this->form->execute($data);
        $this->assertNull($this->form->getData('tenant_id'));
        $this->assertNull($this->form->getData('client_id'));
        $this->assertNull($this->form->getData('client_secret'));
        $this->assertNull($this->form->getData('oauth_username'));
    }

    public function testEmailConfigurationForm_FilterOauth2Fields_NoAuthMethodLeavesAllUnchanged(): void
    {
        $data = $this->getSmtpSettingsData();
        $tenantId = Text::uuid();
        $data['tenant_id'] = $tenantId;
        // No authentication_method key

        $this->form->execute($data);
        $this->assertSame($tenantId, $this->form->getData('tenant_id'));
    }

    public function testEmailConfigurationForm_BackwardCompat_ExistingDataWithoutOauth2Fields(): void
    {
        $data = $this->getSmtpSettingsData();
        // No OAuth2 fields at all - should pass validation
        $result = $this->form->execute($data);
        $this->assertTrue($result);
    }
}
