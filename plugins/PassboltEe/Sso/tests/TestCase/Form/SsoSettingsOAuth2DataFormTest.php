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
 * @since         4.4.0
 */

namespace Passbolt\Sso\Test\TestCase\Form;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Event\EventDispatcherTrait;
use Passbolt\Sso\Form\SsoSettingsOAuth2DataForm;
use Passbolt\Sso\Model\Entity\SsoSetting;

/**
 * @covers \Passbolt\Sso\Form\SsoSettingsOAuth2DataForm
 */
class SsoSettingsOAuth2DataFormTest extends AppTestCase
{
    use EventDispatcherTrait;
    use FormatValidationTrait;

    private function getOauth2DummySettingsData(): array
    {
        return [
            'id' => UuidFactory::uuid(),
            'status' => SsoSetting::STATUS_DRAFT,
            'provider' => SsoSetting::PROVIDER_OAUTH2,
            'data' => [
                'url' => 'https://login.generic-openid.test',
                'client_id' => UuidFactory::uuid(),
                'client_secret' => UuidFactory::uuid(),
                'openid_configuration_path' => 'https://company.openid.test/v2',
                'scope' => 'openid profile email',
            ],
            'created_by' => UuidFactory::uuid(),
            'modified_by' => UuidFactory::uuid(),
            'created' => Chronos::now()->subDays(3),
            'modified' => Chronos::now()->subDays(3),
        ];
    }

    public function testSsoSettingsOAuth2DataForm_ValidateUrl(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'maxLength' => self::getMaxLengthTestCases(256, 'secureUrl'),
            'custom' => [
                'rule_name' => 'custom',
                'test_cases' => [
                    'https://login.generic-openid.test' => true,
                    'login.generic-openid.test' => false,
                ],
            ],
        ];

        unset($testCases['maxLength']['test_cases']['h']);

        $this->assertFormFieldFormatValidation(SsoSettingsOAuth2DataForm::class, 'data.url', $this->getOauth2DummySettingsData(), $testCases);
    }

    public function testSsoSettingsOAuth2DataForm_ValidateOpenIdConfigurationPath(): void
    {
        $testCases = [
            'allowEmptyString' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
            'maxLength' => self::getMaxLengthTestCases(256, 'secureUrl'),
        ];

        unset($testCases['maxLength']['test_cases']['h']);

        $this->assertFormFieldFormatValidation(
            SsoSettingsOAuth2DataForm::class,
            'data.openid_configuration_path',
            $this->getOauth2DummySettingsData(),
            $testCases
        );
    }

    public function testSsoSettingsOAuth2DataForm_ValidateClientId(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
            'maxLength' => self::getMaxLengthTestCases(256),
        ];

        $this->assertFormFieldFormatValidation(
            SsoSettingsOAuth2DataForm::class,
            'data.client_id',
            $this->getOauth2DummySettingsData(),
            $testCases
        );
    }

    public function testSsoSettingsOAuth2DataForm_ValidateClientSecret(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
            'maxLength' => self::getMaxLengthTestCases(256),
        ];

        $this->assertFormFieldFormatValidation(
            SsoSettingsOAuth2DataForm::class,
            'data.client_secret',
            $this->getOauth2DummySettingsData(),
            $testCases
        );
    }

    public function testSsoSettingsOAuth2DataForm_ValidateScope(): void
    {
        $testCases = [
            'allowEmptyString' => self::getAllowEmptyTestCases(),
            'ascii' => self::getAsciiTestCases(),
            'maxLength' => self::getMaxLengthTestCases(256),
        ];

        $this->assertFormFieldFormatValidation(
            SsoSettingsOAuth2DataForm::class,
            'data.scope',
            $this->getOauth2DummySettingsData(),
            $testCases
        );
    }
}
