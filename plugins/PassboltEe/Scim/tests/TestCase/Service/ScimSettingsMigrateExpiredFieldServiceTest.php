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

namespace Passbolt\Scim\Test\TestCase\Service;

use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Cake\I18n\Date;
use Passbolt\Scim\Service\ScimSettingsMigrateExpiredFieldService;
use Passbolt\Scim\Test\Factory\ScimSettingFactory;

class ScimSettingsMigrateExpiredFieldServiceTest extends AppTestCase
{
    use OpenPGPCommonServerOperationsTrait;

    private ScimSettingsMigrateExpiredFieldService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ScimSettingsMigrateExpiredFieldService();
        Configure::write('passbolt.plugins.scim.security.secretToken.expiry', '1 year');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
    }

    public function testMigrate_NoSettings()
    {
        $this->service->migrate();
        $this->assertSame(0, ScimSettingFactory::count());
    }

    public function testMigrate_ExpiredAlreadySet()
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $setting */
        $setting = ScimSettingFactory::make()->default()->persist();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $originalData = json_decode($gpg->decrypt($setting->value), associative: true);
        $originalExpired = $originalData['expired'];

        $this->service->migrate();

        $updated = ScimSettingFactory::find()->firstOrFail();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $updatedData = json_decode($gpg->decrypt($updated->value), associative: true);
        $this->assertSame($originalExpired, $updatedData['expired']);
    }

    public function testMigrate_ExpiredNotSet_SetsIt()
    {
        // Create settings without the expired field
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setEncryptKeyWithServerKey($gpg);
        $value = [
            'setting_id' => ScimSettingFactory::SCIM_TEST_SETTING_ID,
            'scim_user_id' => UserFactory::make()->admin()->persist()->get('id'),
            'secret_token' => password_hash(ScimSettingFactory::SCIM_TEST_SECRET_TOKEN, PASSWORD_BCRYPT, ['cost' => 4]),
        ];
        ScimSettingFactory::make()->setField('value', $gpg->encrypt(json_encode($value)))->persist();

        $this->service->migrate();

        $updated = ScimSettingFactory::find()->firstOrFail();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $updatedData = json_decode($gpg->decrypt($updated->value), associative: true);
        $expectedExpired = Date::now()->modify('+1 year')->format('Y-m-d');
        $this->assertSame($expectedExpired, $updatedData['expired']);
        // Verify other fields are untouched
        $this->assertSame($value['setting_id'], $updatedData['setting_id']);
        $this->assertSame($value['scim_user_id'], $updatedData['scim_user_id']);
        $this->assertSame($value['secret_token'], $updatedData['secret_token']);
    }

    public function testMigrate_ExpiredNotSet_NullExpiryConfig_DoesNothing()
    {
        Configure::write('passbolt.plugins.scim.security.secretToken.expiry', null);

        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setEncryptKeyWithServerKey($gpg);
        $value = [
            'setting_id' => ScimSettingFactory::SCIM_TEST_SETTING_ID,
            'scim_user_id' => UserFactory::make()->admin()->persist()->get('id'),
            'secret_token' => password_hash(ScimSettingFactory::SCIM_TEST_SECRET_TOKEN, PASSWORD_BCRYPT, ['cost' => 4]),
        ];
        ScimSettingFactory::make()->setField('value', $gpg->encrypt(json_encode($value)))->persist();

        $this->service->migrate();

        $updated = ScimSettingFactory::find()->firstOrFail();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $updatedData = json_decode($gpg->decrypt($updated->value), associative: true);
        $this->assertArrayNotHasKey('expired', $updatedData);
    }
}
