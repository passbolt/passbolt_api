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
use Cake\TestSuite\TestCase;
use Passbolt\Sso\Form\SsoSettingsAzureDataForm;
use Passbolt\Sso\Model\Entity\SsoSetting;

class SsoSettingsFormTest extends TestCase
{
    use EventDispatcherTrait;
    use FormatValidationTrait;

    public function getAzureDummy(): array
    {
        return [
           'id' => UuidFactory::uuid(),
           'status' => SsoSetting::STATUS_DRAFT,
           'provider' => SsoSetting::PROVIDER_AZURE,
           'data' => [
               'url' => 'https://login.microsoftonline.com',
               'client_id' => UuidFactory::uuid(),
               'tenant_id' => UuidFactory::uuid(),
               'client_secret' => UuidFactory::uuid(),
               'client_secret_expiry' => Chronos::now()->addDays(365),
               'email_claim' => SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_EMAIL,
           ],
           'created_by' => UuidFactory::uuid(),
           'modified_by' => UuidFactory::uuid(),
           'created' => Chronos::now()->subDay(3),
           'modified' => Chronos::now()->subDay(3),
        ];
    }

    public function testSsoSettingsForm_ValidateProvider(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'inList' => self::getInListTestCases(SsoSetting::ALLOWED_PROVIDERS),
        ];
        $this->assertFormFieldFormatValidation(SsoSettingsAzureDataForm::class, 'provider', $this->getAzureDummy(), $testCases);
    }

    public function testSsoSettingsForm_ValidateStatus(): void
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            // TODO
            //'notEmpty' => self::getNotEmptyTestCases(),
            //'array' => ?
        ];
        $this->assertFormFieldFormatValidation(SsoSettingsAzureDataForm::class, 'data', $this->getAzureDummy(), $testCases);
        $this->markTestIncomplete();
    }
}
