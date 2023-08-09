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

namespace Passbolt\PasswordPolicies\Test\TestCase\Service;

use App\Error\Exception\FormValidationException;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Passbolt\PasswordGenerator\PasswordGeneratorPlugin;
use Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto;
use Passbolt\PasswordPolicies\PasswordPoliciesPlugin;
use Passbolt\PasswordPolicies\Service\PasswordPoliciesGetSettingsService;

/**
 * @covers \Passbolt\PasswordPolicies\Service\PasswordPoliciesGetSettingsService
 */
class PasswordPoliciesGetSettingsServiceTest extends AppTestCase
{
    /**
     * @var \Passbolt\PasswordPolicies\Service\PasswordPoliciesGetSettingsService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new PasswordPoliciesGetSettingsService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        Configure::delete(PasswordPoliciesPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY);
        putenv(PasswordPoliciesPlugin::DEFAULT_PASSWORD_GENERATOR_ENV_KEY . '=');
        Configure::delete(PasswordGeneratorPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY);
        putenv(PasswordGeneratorPlugin::DEFAULT_PASSWORD_GENERATOR_ENV_KEY . '=');

        parent::tearDown();
    }

    public function testPasswordPoliciesGetSettingsService_Success_DefaultValues()
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

    public function testPasswordPoliciesGetSettingsService_Success_FromFile()
    {
        Configure::write(PasswordPoliciesPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY, 'passphrase');
        putenv(PasswordPoliciesPlugin::DEFAULT_PASSWORD_GENERATOR_ENV_KEY . '=invalid-env');
        Configure::write(PasswordGeneratorPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY, 'invalid-legacy-file');
        putenv(PasswordGeneratorPlugin::DEFAULT_PASSWORD_GENERATOR_ENV_KEY . '=invalid-legacy-env');

        $result = $this->service->get();

        $this->assertInstanceOf(PasswordPoliciesSettingsDto::class, $result);
        $this->assertSame(PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSPHRASE, $result->default_generator);
        $this->assertSame(PasswordPoliciesSettingsDto::SOURCE_FILE, $result->source);
    }

    public function testPasswordPoliciesGetSettingsService_Success_FromEnv()
    {
        putenv(PasswordPoliciesPlugin::DEFAULT_PASSWORD_GENERATOR_ENV_KEY . '=passphrase');
        Configure::write(PasswordGeneratorPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY, 'invalid-legacy-file');
        putenv(PasswordGeneratorPlugin::DEFAULT_PASSWORD_GENERATOR_ENV_KEY . '=invalid-legacy-env');

        $result = $this->service->get();

        $this->assertInstanceOf(PasswordPoliciesSettingsDto::class, $result);
        $this->assertSame(PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSPHRASE, $result->default_generator);
        $this->assertSame(PasswordPoliciesSettingsDto::SOURCE_ENV, $result->source);
    }

    public function testPasswordPoliciesGetSettingsService_Success_FromLegacyFile()
    {
        Configure::write(PasswordGeneratorPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY, 'passphrase');
        putenv(PasswordGeneratorPlugin::DEFAULT_PASSWORD_GENERATOR_ENV_KEY . '=invalid-legacy-env');

        $result = $this->service->get();

        $this->assertInstanceOf(PasswordPoliciesSettingsDto::class, $result);
        $this->assertSame(PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSPHRASE, $result->default_generator);
        $this->assertSame(PasswordPoliciesSettingsDto::SOURCE_LEGACY_FILE, $result->source);
    }

    public function testPasswordPoliciesGetSettingsService_Success_FromLegacyEnv()
    {
        putenv(PasswordGeneratorPlugin::DEFAULT_PASSWORD_GENERATOR_ENV_KEY . '=passphrase');

        $result = $this->service->get();

        $this->assertInstanceOf(PasswordPoliciesSettingsDto::class, $result);
        $this->assertSame(PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSPHRASE, $result->default_generator);
        $this->assertSame(PasswordPoliciesSettingsDto::SOURCE_LEGACY_ENV, $result->source);
    }

    public function testPasswordPoliciesGetSettingsService_Error_InvalidGeneratorType()
    {
        Configure::write(PasswordPoliciesPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY, 'im_invalid');

        $this->expectException(FormValidationException::class);

        $this->service->get();
    }
}
