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
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto;
use Passbolt\PasswordPolicies\PasswordPoliciesPlugin;
use Passbolt\PasswordPoliciesUpdate\Model\Dto\PasswordPoliciesUpdateSettingsDto;
use Passbolt\PasswordPoliciesUpdate\PasswordPoliciesUpdatePlugin;
use Passbolt\PasswordPoliciesUpdate\Service\PasswordPoliciesUpdateGetSettingsService;
use Passbolt\PasswordPoliciesUpdate\Test\Factory\PasswordPoliciesSettingFactory;

/**
 * @covers \Passbolt\PasswordPoliciesUpdate\Service\PasswordPoliciesUpdateGetSettingsService
 */
class PasswordPoliciesUpdateGetSettingsServiceTest extends AppTestCase
{
    /**
     * @var \Passbolt\PasswordPoliciesUpdate\Service\PasswordPoliciesUpdateGetSettingsService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->enableFeaturePlugin(PasswordPoliciesUpdatePlugin::class);
        $this->service = new PasswordPoliciesUpdateGetSettingsService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testPasswordPoliciesGetSettingsService_Success_FromConfigOrEnv()
    {
        $result = $this->service->get();

        $this->assertInstanceOf(PasswordPoliciesSettingsDto::class, $result);
        $expected = [
            'default_generator' => 'password',
            'source' => 'default',
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
                'word_case' => 'lowercase',
            ],
            'external_dictionary_check' => true,
        ];
        $this->assertEqualsCanonicalizing($expected, $result->toArray());
    }

    public function testPasswordPoliciesGetSettingsService_Success_FromDatabase()
    {
        /** @var \Passbolt\PasswordPoliciesUpdate\Model\Dto\PasswordPoliciesUpdateSettingsDto $settingsDto */
        $settingsDto = PasswordPoliciesUpdateSettingsDto::createFromDefault();
        $passwordPolicy = PasswordPoliciesSettingFactory::make($settingsDto->toOrganizationSettingValueArray())->persist();

        $result = $this->service->get();

        $this->assertInstanceOf(PasswordPoliciesSettingsDto::class, $result);
        $passwordPolicyArray = $passwordPolicy->toArray();
        $resultArray = $result->toArray();
        $this->assertSame(PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSWORD, $resultArray['default_generator']);
        $this->assertSame($passwordPolicyArray['created']->toIso8601String(), $resultArray['created']->toIso8601String());
        $this->assertSame($passwordPolicyArray['modified']->toIso8601String(), $resultArray['modified']->toIso8601String());
        // Unset dates as it causing problem when directly passing it to assertEquals
        unset(
            $passwordPolicyArray['created'],
            $passwordPolicyArray['modified'],
            $resultArray['created'],
            $resultArray['modified']
        );
        $this->assertArrayEqualsCanonicalizing(
            [
                'default_generator' => $passwordPolicyArray['value']['default_generator'],
                'external_dictionary_check' => $passwordPolicyArray['value']['external_dictionary_check'],
                'password_generator_settings' => $passwordPolicyArray['value']['password_generator_settings'],
                'passphrase_generator_settings' => $passwordPolicyArray['value']['passphrase_generator_settings'],
                'source' => 'db',
                'id' => $passwordPolicyArray['id'],
                'created_by' => $passwordPolicyArray['created_by'],
                'modified_by' => $passwordPolicyArray['modified_by'],
            ],
            $resultArray
        );
    }

    public function testPasswordPoliciesGetSettingsService_Error_FromConfigOrEnv()
    {
        Configure::write(PasswordPoliciesPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY, 'im_invalid');

        $this->expectException(FormValidationException::class);
        $this->service->get();
    }

    public function testPasswordPoliciesGetSettingsService_Error_FromDatabase()
    {
        /** @var \Passbolt\PasswordPoliciesUpdate\Model\Dto\PasswordPoliciesUpdateSettingsDto $settingsDto */
        $settingsDto = PasswordPoliciesUpdateSettingsDto::createFromDefault([
            'default_generator' => 'im_invalid',
        ]);
        PasswordPoliciesSettingFactory::make(['value' => $settingsDto->toOrganizationSettingValueArray()])->persist();

        $this->expectException(FormValidationException::class);
        $this->service->get();
    }
}
