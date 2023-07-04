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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Test\TestCase\Form;

use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Event\EventDispatcherTrait;
use Passbolt\Sso\Form\SsoSettingsAzureDataForm;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Test\Lib\SsoTestCase;

class SsoSettingsAzureDataFormTest extends SsoTestCase
{
    use EventDispatcherTrait;
    use FormatValidationTrait;

    /**
     * @var \Passbolt\Sso\Form\SsoSettingsAzureDataForm
     */
    protected $form;

    public function setUp(): void
    {
        parent::setUp();
        $this->form = new SsoSettingsAzureDataForm();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->form);
    }

    protected function getDummy(): array
    {
        $data = [
            'url' => 'https://login.microsoftonline.com',
            'client_id' => UuidFactory::uuid(),
            'tenant_id' => UuidFactory::uuid(),
            'client_secret' => UuidFactory::uuid(),
            'client_secret_expiry' => Chronos::now()->addDays(365),
            'email_claim' => SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_EMAIL,
        ];

        return [
            'provider' => 'azure',
            'data' => $data,
        ];
    }

    public function testSsoSettingsAzureDataForm_ValidateUrl(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(SsoSettingsAzureDataForm::SUPPORTED_AZURE_URLS),
        ];
        $this->assertFormFieldFormatValidation(SsoSettingsAzureDataForm::class, 'data.url', $this->getDummy(), $testCases);
    }

    public function testSsoSettingsAzureDataForm_ValidateClientId(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'uuid' => self::getUuidTestCases(),
        ];
        $this->assertFormFieldFormatValidation(SsoSettingsAzureDataForm::class, 'data.client_id', $this->getDummy(), $testCases);
    }

    public function testSsoSettingsAzureDataForm_ValidateTenantId(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'uuid' => self::getUuidTestCases(),
        ];
        $this->assertFormFieldFormatValidation(SsoSettingsAzureDataForm::class, 'data.tenant_id', $this->getDummy(), $testCases);
    }

    public function testSsoSettingsAzureDataForm_ValidateClientSecret(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'ascii' => self::getAsciiTestCases(),
        ];
        $this->assertFormFieldFormatValidation(SsoSettingsAzureDataForm::class, 'data.client_secret', $this->getDummy(), $testCases);
    }

    public function testSsoSettingsAzureDataForm_ValidateClientSecretExpiry(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            // 'date' => ?
        ];
        $this->assertFormFieldFormatValidation(SsoSettingsAzureDataForm::class, 'data.client_secret_expiry', $this->getDummy(), $testCases);
        $this->markTestIncomplete();
    }

    public function testSsoSettingsAzureDataForm_ValidateEmailClaim(): void
    {
        $testCases = [
            'notEmpty' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(SsoSettingsAzureDataForm::SUPPORTED_EMAIL_CLAIM_ALIASES),
        ];
        $this->assertFormFieldFormatValidation(SsoSettingsAzureDataForm::class, 'data.email_claim', $this->getDummy(), $testCases);
    }
}
