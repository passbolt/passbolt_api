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
 * @since         5.11.0
 */

namespace Passbolt\Sso\Test\TestCase\Form;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Event\EventDispatcherTrait;
use Passbolt\Sso\Form\SsoSettingsPingOneDataForm;
use Passbolt\Sso\Model\Entity\SsoSetting;

/**
 * @covers \Passbolt\Sso\Form\SsoSettingsPingOneDataForm
 */
class SsoSettingsPingOneDataFormTest extends AppTestCase
{
    use EventDispatcherTrait;
    use FormatValidationTrait;

    private function getPingOneDummySettingsData(): array
    {
        return [
            'id' => UuidFactory::uuid(),
            'status' => SsoSetting::STATUS_DRAFT,
            'provider' => SsoSetting::PROVIDER_PINGONE,
            'data' => [
                'url' => 'https://auth.pingone.com',
                'client_id' => UuidFactory::uuid(),
                'client_secret' => UuidFactory::uuid(),
                'email_claim' => SsoSetting::PINGONE_EMAIL_CLAIM_EMAIL,
            ],
            'created_by' => UuidFactory::uuid(),
            'modified_by' => UuidFactory::uuid(),
            'created' => Chronos::now()->subDays(3),
            'modified' => Chronos::now()->subDays(3),
        ];
    }

    public function testSsoSettingsPingOneDataForm_ValidateUrl(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            // maxLength not tested here because isPingOneUrl domain check takes precedence
            'custom' => [
                'rule_name' => 'custom',
                'test_cases' => [
                    'https://auth.pingone.com' => true,
                    'auth.pingone.com' => false,
                ],
            ],
        ];

        $this->assertFormFieldFormatValidation(
            SsoSettingsPingOneDataForm::class,
            'data.url',
            $this->getPingOneDummySettingsData(),
            $testCases
        );
    }

    public function testSsoSettingsPingOneDataForm_ValidateUrl_SupportedPingOneDomains(): void
    {
        $form = new SsoSettingsPingOneDataForm();

        // All 6 regional domains should pass
        foreach (SsoSettingsPingOneDataForm::SUPPORTED_PINGONE_URLS as $host) {
            $data = $this->getPingOneDummySettingsData();
            $data['data']['url'] = 'https://' . $host;
            $this->assertTrue($form->execute($data), "Expected {$host} to be valid");
        }
    }

    public function testSsoSettingsPingOneDataForm_ValidateUrl_InvalidPingOneDomains(): void
    {
        $form = new SsoSettingsPingOneDataForm();

        // Non-PingOne domains should fail
        $invalidUrls = [
            'https://evil.com',
            'https://auth.pingone.fake.com',
            'https://login.microsoftonline.com',
            'https://accounts.google.com',
        ];
        foreach ($invalidUrls as $url) {
            $data = $this->getPingOneDummySettingsData();
            $data['data']['url'] = $url;
            $this->assertFalse($form->execute($data), "Expected {$url} to be rejected");
        }
    }

    public function testSsoSettingsPingOneDataForm_ValidateClientId(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
            'maxLength' => self::getMaxLengthTestCases(256),
        ];

        $this->assertFormFieldFormatValidation(
            SsoSettingsPingOneDataForm::class,
            'data.client_id',
            $this->getPingOneDummySettingsData(),
            $testCases
        );
    }

    public function testSsoSettingsPingOneDataForm_ValidateClientSecret(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'utf8' => self::getUtf8TestCases(),
            'maxLength' => self::getMaxLengthTestCases(256),
        ];

        $this->assertFormFieldFormatValidation(
            SsoSettingsPingOneDataForm::class,
            'data.client_secret',
            $this->getPingOneDummySettingsData(),
            $testCases
        );
    }

    public function testSsoSettingsPingOneDataForm_ValidateEmailClaim(): void
    {
        $testCases = [
            'notEmptyString' => self::getNotEmptyTestCases(),
            'maxLength' => self::getMaxLengthTestCases(64),
        ];

        $this->assertFormFieldFormatValidation(
            SsoSettingsPingOneDataForm::class,
            'data.email_claim',
            $this->getPingOneDummySettingsData(),
            $testCases
        );
    }

    public function testSsoSettingsPingOneDataForm_ValidateSuccess(): void
    {
        $form = new SsoSettingsPingOneDataForm();
        $data = $this->getPingOneDummySettingsData();
        $this->assertTrue($form->execute($data));
    }
}
