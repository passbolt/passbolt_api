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
 * @since         4.2.0
 */

namespace Passbolt\PasswordPoliciesUpdate\Test\TestCase\Service;

use App\Error\Exception\FormValidationException;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\ExtendedUserAccessControlTestTrait;
use App\Utility\UuidFactory;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\ForbiddenException;
use Passbolt\PasswordPolicies\Model\Dto\PassphraseGeneratorSettingsDto;
use Passbolt\PasswordPolicies\Model\Dto\PasswordGeneratorSettingsDto;
use Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto;
use Passbolt\PasswordPoliciesUpdate\Model\Dto\PasswordPoliciesUpdateSettingsDto;
use Passbolt\PasswordPoliciesUpdate\PasswordPoliciesUpdatePlugin;
use Passbolt\PasswordPoliciesUpdate\Service\PasswordPoliciesUpdateSetSettingsService;
use Passbolt\PasswordPoliciesUpdate\Test\Factory\PasswordPoliciesSettingFactory;

/**
 * @covers \Passbolt\PasswordPoliciesUpdate\Service\PasswordPoliciesUpdateSetSettingsService
 */
class PasswordPoliciesUpdateSetSettingsServiceTest extends AppTestCase
{
    use ExtendedUserAccessControlTestTrait;

    /**
     * @var \Passbolt\PasswordPoliciesUpdate\Service\PasswordPoliciesUpdateSetSettingsService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(PasswordPoliciesUpdatePlugin::class);
        $this->service = new PasswordPoliciesUpdateSetSettingsService();
        // Enable event tracking, required to test events.
        EventManager::instance()->setEventList(new EventList());
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testPasswordPoliciesSetSettingsService_Error_GuestForbidden()
    {
        $user = UserFactory::make()->guest()->persist();
        $uac = $this->makeExtendedUac($user, '127.0.0.1', 'phpunit');

        $this->expectException(ForbiddenException::class);
        $this->expectErrorMessage('Only administrators are allowed');

        $this->service->createOrUpdate($uac, []);
    }

    public function testPasswordPoliciesSetSettingsService_Error_UserForbidden()
    {
        $uac = $this->mockExtendedUserAccessControl();

        $this->expectException(ForbiddenException::class);
        $this->expectErrorMessage('Only administrators are allowed');

        $this->service->createOrUpdate($uac, []);
    }

    public function testPasswordPoliciesSetSettingsService_Success_CreateWithDefault()
    {
        $uac = $this->mockExtendedAdminAccessControl();
        $settingsDto = PasswordPoliciesUpdateSettingsDto::createFromDefault();

        /** @var \Passbolt\PasswordPoliciesUpdate\Model\Dto\PasswordPoliciesUpdateSettingsDto $settingsDto */
        $result = $this->service->createOrUpdate($uac, $settingsDto->toArray());

        $this->assertSame(1, PasswordPoliciesSettingFactory::find()->count());
        $this->assertInstanceOf(PasswordPoliciesSettingsDto::class, $result);
        $this->assertIsString($result->id);
        $this->assertSame($uac->getId(), $result->created_by);
        $this->assertSame($uac->getId(), $result->modified_by);
        $this->assertSame(PasswordPoliciesUpdateSettingsDto::SOURCE_DATABASE, $result->source);
        $this->assertArrayEqualsCanonicalizing(
            [
                'default_generator' => PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSWORD,
                'password_generator_settings' => [
                    'length' => 18,
                    'mask_upper' => true,
                    'mask_lower' => true,
                    'mask_digit' => true,
                    'mask_parenthesis' => true,
                    'mask_emoji' => false,
                    'mask_char1' => true,
                    'mask_char2' => true,
                    'mask_char3' => true,
                    'mask_char4' => true,
                    'mask_char5' => true,
                    'exclude_look_alike_chars' => true,
                ],
                'passphrase_generator_settings' => [
                    'words' => 9,
                    'word_separator' => ' ',
                    'word_case' => PassphraseGeneratorSettingsDto::PASSPHRASE_GENERATOR_WORDS_CASE_LOWER,
                ],
                'external_dictionary_check' => true,
            ],
            $result->toOrganizationSettingValueArray()
        );
        // Assert event is dispatched & contains valid data
        $this->assertEventFiredWith(
            PasswordPoliciesUpdateSetSettingsService::EVENT_SETTINGS_UPDATED,
            'passwordPoliciesSetting',
            $result
        );
        $this->assertEventFiredWith(PasswordPoliciesUpdateSetSettingsService::EVENT_SETTINGS_UPDATED, 'uac', $uac);
    }

    public function testPasswordPoliciesSetSettingsService_Success_CreateWithCustom()
    {
        $uac = $this->mockExtendedAdminAccessControl();
        $settingsDto = PasswordPoliciesUpdateSettingsDto::createFromDefault([
            'default_generator' => PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSPHRASE,
            'password_generator_settings' => [
                'length' => 9,
                'mask_upper' => false,
                'mask_lower' => false,
                'mask_digit' => false,
                'mask_parenthesis' => false,
                'mask_emoji' => true,
                'mask_char1' => false,
                'mask_char2' => false,
                'mask_char3' => false,
                'mask_char4' => false,
                'mask_char5' => false,
                'exclude_look_alike_chars' => false,
            ],
            'passphrase_generator_settings' => [
                'words' => 18,
                'word_separator' => '-',
                'word_case' => PassphraseGeneratorSettingsDto::PASSPHRASE_GENERATOR_WORDS_CASE_UPPER,
            ],
            'external_dictionary_check' => false,
        ]);

        /** @var \Passbolt\PasswordPoliciesUpdate\Model\Dto\PasswordPoliciesUpdateSettingsDto $settingsDto */
        $result = $this->service->createOrUpdate($uac, $settingsDto->toArray());

        $this->assertSame(1, PasswordPoliciesSettingFactory::find()->count());
        $this->assertInstanceOf(PasswordPoliciesSettingsDto::class, $result);
        $this->assertIsString($result->id);
        $this->assertSame($uac->getId(), $result->created_by);
        $this->assertSame($uac->getId(), $result->modified_by);
        $this->assertSame(PasswordPoliciesUpdateSettingsDto::SOURCE_DATABASE, $result->source);
        $this->assertArrayEqualsCanonicalizing(
            [
                'default_generator' => PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSPHRASE,
                'password_generator_settings' => [
                    'length' => 9,
                    'mask_upper' => false,
                    'mask_lower' => false,
                    'mask_digit' => false,
                    'mask_parenthesis' => false,
                    'mask_emoji' => true,
                    'mask_char1' => false,
                    'mask_char2' => false,
                    'mask_char3' => false,
                    'mask_char4' => false,
                    'mask_char5' => false,
                    'exclude_look_alike_chars' => false,
                ],
                'passphrase_generator_settings' => [
                    'words' => 18,
                    'word_separator' => '-',
                    'word_case' => PassphraseGeneratorSettingsDto::PASSPHRASE_GENERATOR_WORDS_CASE_UPPER,
                ],
                'external_dictionary_check' => false,
            ],
            $result->toOrganizationSettingValueArray()
        );
        // Assert event is dispatched & contains valid data
        $this->assertEventFiredWith(
            PasswordPoliciesUpdateSetSettingsService::EVENT_SETTINGS_UPDATED,
            'passwordPoliciesSetting',
            $result
        );
        $this->assertEventFiredWith(PasswordPoliciesUpdateSetSettingsService::EVENT_SETTINGS_UPDATED, 'uac', $uac);
    }

    public function testPasswordPoliciesSetSettingsService_Success_Update()
    {
        PasswordPoliciesSettingFactory::make()->persist();
        $uac = $this->mockExtendedAdminAccessControl();

        $settingsDto = PasswordPoliciesUpdateSettingsDto::createFromDefault([
            'default_generator' => PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSPHRASE,
            'password_generator_settings' => PasswordGeneratorSettingsDto::createFromDefault([
                'length' => 100,
                'mask_emoji' => true,
            ])->toArray(),
            'passphrase_generator_settings' => PassphraseGeneratorSettingsDto::createFromDefault([
                'word_separator' => '+',
            ])->toArray(),
            'external_dictionary_check' => false,
        ]);

        $result = $this->service->createOrUpdate($uac, $settingsDto->toArray());

        $this->assertInstanceOf(PasswordPoliciesSettingsDto::class, $result);
        $this->assertSame(1, PasswordPoliciesSettingFactory::find()->count());
        $this->assertIsString($result->id);
        $this->assertSame($uac->getId(), $result->modified_by);
        $this->assertArrayEqualsCanonicalizing(
            [
                'default_generator' => PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSPHRASE,
                'external_dictionary_check' => false,
                'password_generator_settings' => $settingsDto->password_generator_settings,
                'passphrase_generator_settings' => $settingsDto->passphrase_generator_settings,
                'source' => 'db',
            ],
            [
                'default_generator' => $result->default_generator,
                'external_dictionary_check' => $result->external_dictionary_check,
                'password_generator_settings' => $result->password_generator_settings,
                'passphrase_generator_settings' => $result->passphrase_generator_settings,
                'source' => $result->source,
            ]
        );
        // Assert event is dispatched & contains valid data
        $this->assertEventFiredWith(
            PasswordPoliciesUpdateSetSettingsService::EVENT_SETTINGS_UPDATED,
            'passwordPoliciesSetting',
            $result
        );
        $this->assertEventFiredWith(PasswordPoliciesUpdateSetSettingsService::EVENT_SETTINGS_UPDATED, 'uac', $uac);
    }

    public function testPasswordPoliciesSetSettingsService_Success_FilterOutUnwantedData()
    {
        PasswordPoliciesSettingFactory::make()->persist();
        $uac = $this->mockExtendedAdminAccessControl();

        $settingsDto = PasswordPoliciesUpdateSettingsDto::createFromDefault([
            'root_property' => PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSPHRASE,
            'password_generator_settings' => PasswordGeneratorSettingsDto::createFromDefault([
                'password_generator_settings_property' => true,
            ])->toArray(),
            'passphrase_generator_settings' => PassphraseGeneratorSettingsDto::createFromDefault([
                'passphrase_generator_settings_property' => true,
            ])->toArray(),
            'modified_by' => UuidFactory::uuid(),
            'created_by' => UuidFactory::uuid(),
        ]);

        $resultSettingsDto = $this->service->createOrUpdate($uac, $settingsDto->toArray());

        $this->assertInstanceOf(PasswordPoliciesSettingsDto::class, $resultSettingsDto);
        $this->assertObjectNotHasAttribute('root_property', $resultSettingsDto);
        $this->assertObjectNotHasAttribute('password_generator_settings_property', $resultSettingsDto->password_generator_settings);
        $this->assertObjectNotHasAttribute('passphrase_generator_settings_property', $resultSettingsDto->passphrase_generator_settings);
        $this->assertNotSame($settingsDto->created_by, $resultSettingsDto->created_by);
        $this->assertNotSame($settingsDto->modified_by, $resultSettingsDto->modified_by);
    }

    public function testPasswordPoliciesSetSettingsService_Error_InvalidData()
    {
        PasswordPoliciesSettingFactory::make()->persist();
        $uac = $this->mockExtendedAdminAccessControl();
        $settingsDto = PasswordPoliciesUpdateSettingsDto::createFromDefault([
            'default_generator' => 'invalid-default-generator',
        ]);

        $this->expectException(FormValidationException::class);
        $this->service->createOrUpdate($uac, $settingsDto->toArray());
    }
}
