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
 * @since         4.6.0
 */

namespace Passbolt\Sso\Test\TestCase\Form;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Event\EventDispatcherTrait;
use Passbolt\Sso\Form\SsoSettingsAdfsDataForm;
use Passbolt\Sso\Model\Entity\SsoSetting;

/**
 * @covers \Passbolt\Sso\Form\SsoSettingsAdfsDataForm
 */
class SsoSettingsAdfsDataFormTest extends AppTestCase
{
    use EventDispatcherTrait;
    use FormatValidationTrait;

    private function getAdfsDummySettingsData(): array
    {
        return [
            'id' => UuidFactory::uuid(),
            'status' => SsoSetting::STATUS_DRAFT,
            'provider' => SsoSetting::PROVIDER_ADFS,
            'data' => [
                'url' => 'https://adfs.passbolt.test',
                'client_id' => UuidFactory::uuid(),
                'client_secret' => UuidFactory::uuid(),
                'openid_configuration_path' => '/.well-known/openid-configuration',
                'scope' => 'openid profile email',
                'email_claim' => SsoSetting::ADFS_EMAIL_CLAIM_UPN,
            ],
            'created_by' => UuidFactory::uuid(),
            'modified_by' => UuidFactory::uuid(),
            'created' => Chronos::now()->subDays(3),
            'modified' => Chronos::now()->subDays(3),
        ];
    }

    public function testSsoSettingsAdfsDataForm_ValidateUrl(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'maxLength' => self::getMaxLengthTestCases(256, 'secureUrl'),
            'custom' => [
                'rule_name' => 'custom',
                'test_cases' => [
                    'https://adfs.passbolt.test' => true,
                    'adfs.passbolt.test' => false,
                ],
            ],
        ];

        unset($testCases['maxLength']['test_cases']['h']);

        $this->assertFormFieldFormatValidation(
            SsoSettingsAdfsDataForm::class,
            'data.url',
            $this->getAdfsDummySettingsData(),
            $testCases
        );
    }

    public function testSsoSettingsAdfsDataForm_ValidateOpenIdConfigurationPath(): void
    {
        $testCases = [
            'allowEmptyString' => self::getAllowEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
            'maxLength' => self::getMaxLengthTestCases(256, 'secureUrl'),
        ];

        unset($testCases['maxLength']['test_cases']['h']);

        $this->assertFormFieldFormatValidation(
            SsoSettingsAdfsDataForm::class,
            'data.openid_configuration_path',
            $this->getAdfsDummySettingsData(),
            $testCases
        );
    }

    public function testSsoSettingsAdfsDataForm_ValidateClientId(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
            'maxLength' => self::getMaxLengthTestCases(256),
        ];

        $this->assertFormFieldFormatValidation(
            SsoSettingsAdfsDataForm::class,
            'data.client_id',
            $this->getAdfsDummySettingsData(),
            $testCases
        );
    }

    public function testSsoSettingsAdfsDataForm_ValidateClientSecret(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
            'maxLength' => self::getMaxLengthTestCases(256),
        ];

        $this->assertFormFieldFormatValidation(
            SsoSettingsAdfsDataForm::class,
            'data.client_secret',
            $this->getAdfsDummySettingsData(),
            $testCases
        );
    }

    public function testSsoSettingsAdfsDataForm_ValidateScope(): void
    {
        $testCases = [
            'allowEmptyString' => self::getAllowEmptyTestCases(),
            'ascii' => self::getAsciiTestCases(),
            'maxLength' => self::getMaxLengthTestCases(256),
        ];

        $this->assertFormFieldFormatValidation(
            SsoSettingsAdfsDataForm::class,
            'data.scope',
            $this->getAdfsDummySettingsData(),
            $testCases
        );
    }

    public function testSsoSettingsAdfsDataForm_ValidateEmailClaim(): void
    {
        $testCases = [
            'notEmptyString' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(SsoSettingsAdfsDataForm::SUPPORTED_EMAIL_CLAIM),
            //'maxLength' => self::getMaxLengthTestCases(64), // Failing because inList taking precedence
        ];

        $this->assertFormFieldFormatValidation(
            SsoSettingsAdfsDataForm::class,
            'data.email_claim',
            $this->getAdfsDummySettingsData(),
            $testCases
        );
    }
}
