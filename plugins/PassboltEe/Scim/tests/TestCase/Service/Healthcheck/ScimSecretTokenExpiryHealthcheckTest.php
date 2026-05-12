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

namespace Passbolt\Scim\Test\TestCase\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\I18n\Date;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;
use Passbolt\Scim\Service\Healthcheck\ScimSecretTokenExpiryHealthcheck;
use Passbolt\Scim\Test\Factory\ScimSettingFactory;

/**
 * @covers \Passbolt\Scim\Service\Healthcheck\ScimSecretTokenExpiryHealthcheck
 */
class ScimSecretTokenExpiryHealthcheckTest extends AppTestCase
{
    use OpenPGPCommonServerOperationsTrait;

    private ScimSecretTokenExpiryHealthcheck $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ScimSecretTokenExpiryHealthcheck();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testScimSecretTokenExpiryHealthcheck_Pass_TokenNotExpired(): void
    {
        ScimSettingFactory::make()->default()->persist();
        Configure::write('Scim.settingId', ScimSettingFactory::SCIM_TEST_SETTING_ID);

        $result = $this->service->check();

        $this->assertTrue($result->isPassed());
        $this->assertMetadataInfo($result);
    }

    public function testScimSecretTokenExpiryHealthcheck_Fail_TokenExpired(): void
    {
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setEncryptKeyWithServerKey($gpg);

        $value = [
            'setting_id' => ScimSettingFactory::SCIM_TEST_SETTING_ID,
            'scim_user_id' => UserFactory::make()->admin()->persist()->get('id'),
            'secret_token' => password_hash(ScimSettingFactory::SCIM_TEST_SECRET_TOKEN, PASSWORD_BCRYPT, ['cost' => 4]),
            'expired' => Date::now()->modify('-1 day')->format('Y-m-d'),
        ];
        ScimSettingFactory::make()->setField('value', $gpg->encrypt(json_encode($value)))->persist();
        Configure::write('Scim.settingId', ScimSettingFactory::SCIM_TEST_SETTING_ID);

        $result = $this->service->check();

        $this->assertFalse($result->isPassed());
        $this->assertMetadataInfo($result);
    }

    public function testScimSecretTokenExpiryHealthcheck_Pass_NoSettings(): void
    {
        $result = $this->service->check();

        $this->assertTrue($result->isPassed());
        $this->assertMetadataInfo($result);
    }

    public function testScimSecretTokenExpiryHealthcheck_Pass_NoExpiredField(): void
    {
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setEncryptKeyWithServerKey($gpg);

        $value = [
            'setting_id' => ScimSettingFactory::SCIM_TEST_SETTING_ID,
            'scim_user_id' => UserFactory::make()->admin()->persist()->get('id'),
            'secret_token' => password_hash(ScimSettingFactory::SCIM_TEST_SECRET_TOKEN, PASSWORD_BCRYPT, ['cost' => 4]),
        ];
        ScimSettingFactory::make()->setField('value', $gpg->encrypt(json_encode($value)))->persist();
        Configure::write('Scim.settingId', ScimSettingFactory::SCIM_TEST_SETTING_ID);

        $result = $this->service->check();

        $this->assertTrue($result->isPassed());
        $this->assertMetadataInfo($result);
    }

    public function testScimSecretTokenExpiryHealthcheck_Pass_TokenExpiresExactlyToday(): void
    {
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setEncryptKeyWithServerKey($gpg);

        $value = [
            'setting_id' => ScimSettingFactory::SCIM_TEST_SETTING_ID,
            'scim_user_id' => UserFactory::make()->admin()->persist()->get('id'),
            'secret_token' => password_hash(ScimSettingFactory::SCIM_TEST_SECRET_TOKEN, PASSWORD_BCRYPT, ['cost' => 4]),
            'expired' => Date::now()->format('Y-m-d'),
        ];
        ScimSettingFactory::make()->setField('value', $gpg->encrypt(json_encode($value)))->persist();
        Configure::write('Scim.settingId', ScimSettingFactory::SCIM_TEST_SETTING_ID);

        $result = $this->service->check();

        // Today is not "greater than" today, so token is not yet expired
        $this->assertTrue($result->isPassed());
        $this->assertMetadataInfo($result);
    }

    public function testScimSecretTokenExpiryHealthcheck_SuccessMessage(): void
    {
        $this->assertSame(
            __('The SCIM secret token is not expired.'),
            $this->service->getSuccessMessage()
        );
    }

    public function testScimSecretTokenExpiryHealthcheck_FailureMessage(): void
    {
        $this->assertSame(
            __('The SCIM secret token is expired, you are requested to rotate it.'),
            $this->service->getFailureMessage()
        );
    }

    public function testScimSecretTokenExpiryHealthcheck_HelpMessage(): void
    {
        $this->assertSame(
            __('Rotate the SCIM secret token in the administration settings.'),
            $this->service->getHelpMessage()
        );
    }

    /**
     * Assert that metadata fields are correct.
     *
     * @param \Passbolt\Scim\Service\Healthcheck\ScimSecretTokenExpiryHealthcheck $result Result object.
     * @return void
     */
    private function assertMetadataInfo(ScimSecretTokenExpiryHealthcheck $result): void
    {
        $this->assertSame(EeHealthcheckServiceCollector::DOMAIN_SCIM, $result->domain());
        $this->assertSame(EeHealthcheckServiceCollector::DOMAIN_SCIM, $result->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_ERROR, $result->level());
        $this->assertSame('isScimTokenNotExpired', $result->getLegacyArrayKey());
    }
}
