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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Test\TestCase\Controller;

use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Test\Factory\UserFactory;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\I18n\Date;
use Passbolt\Scim\Middleware\ScimSettingsSecurityMiddleware;
use Passbolt\Scim\Model\Entity\ScimSetting;
use Passbolt\Scim\ScimPlugin;
use Passbolt\Scim\Service\ScimSetSettingsService;
use Passbolt\Scim\Test\Factory\ScimSettingFactory;
use Passbolt\Scim\Test\Utility\ScimSettingsIntegrationTestCase;
use Throwable;

/**
 * @covers \Passbolt\Scim\Controller\ScimSetSettingsController
 */
class ScimSetSettingsControllerTest extends ScimSettingsIntegrationTestCase
{
    use OpenPGPCommonServerOperationsTrait;

    protected ScimSetting $current;

    public function setupUpdate(): void
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $setting */
        $setting = ScimSettingFactory::make()->default()->persist();
        $this->current = $setting;
    }

    /**
     * Test setSettings method: create operation plugin disabled
     *
     * @return void
     */
    public function testScimSetSettingsController_Create_Error_PluginDisabled(): void
    {
        $this->disableFeaturePlugin(ScimPlugin::class);

        try {
            $this->postJson('/scim/setting.json');
        } catch (Throwable $t) {
        }

        $this->assertResponseCode(404);
    }

    public function testScimSetSettingsController_Endpoint_Disabled(): void
    {
        Configure::write(ScimSettingsSecurityMiddleware::PASSBOLT_SECURITY_SCIM_SETTINGS_ENDPOINTS_DISABLED, true);
        $this->postJson('/scim/settings.json');
        $this->assertResponseCode(403);
        $this->assertResponseContains('SCIM settings endpoints are disabled.');
    }

    /**
     * Test setSettings method: create operation guest forbidden
     *
     * @return void
     */
    public function testScimSetSettingsController_Create_Error_GuestForbidden(): void
    {
        $this->logInAsUser();

        $this->postJson('/scim/settings.json');

        $this->assertResponseCode(403);
    }

    /**
     * Test setSettings method: create operation unauthenticated
     *
     * @return void
     */
    public function testScimSetSettingsController_Create_Error_Unauthenticated()
    {
        $this->postJson('/scim/settings.json');

        $this->assertResponseCode(401);
    }

    /**
     * Test setSettings method: not a json request
     *
     * @return void
     */
    public function testGetSettings_Error_NotJson()
    {
        $this->logInAsAdmin();
        $this->post('/scim/settings');
        $this->assertResponseCode(404);
    }

    /**
     * Test setSettings method: create settings already set
     *
     * @return void
     */
    public function testScimSetSettingsController_Create_SettingsAlreadySet()
    {
        $this->setupUpdate();
        $this->logInAsAdmin();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];
        $this->postJson('/scim/settings.json', $data);

        $this->assertResponseCode(400);
        $this->assertSame('Please delete previous settings before creating again.', $this->_responseJsonHeader->message);
        $this->assertSame('error', $this->_responseJsonHeader->status);
    }

    /**
     * Test setSettings method: create operation success
     *
     * @return void
     */
    public function testScimSetSettingsController_Create_Success()
    {
        $this->logInAsAdmin();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];
        $this->postJson('/scim/settings.json', $data);

        $response = $this->_responseJsonBody;

        $this->assertSuccess();
        $this->assertSame($data['setting_id'], $response->setting_id);
        $this->assertSame($data['scim_user_id'], $response->scim_user_id);
        $this->assertTrue(!isset($response->secret_token));
        $this->assertObjectHasAttribute('id', $response);

        //Check if secret token was correctly set
        $this->current = $this->fetchTable('Passbolt/Scim.ScimSettings')->find()->first();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $values = json_decode($gpg->decrypt($this->current->value), associative: true);

        $this->assertTrue(password_verify($data['secret_token'], $values['secret_token']));
    }

    /**
     * Test that the bcrypt cost factor is configurable.
     */
    public function testScimSetSettingsController_Create_CustomCostFactor(): void
    {
        Configure::write('passbolt.plugins.scim.security.secretToken.cost', 10);
        $this->logInAsAdmin();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];
        $this->postJson('/scim/settings.json', $data);
        $this->assertSuccess();

        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $settings */
        $settings = ScimSettingFactory::find()->firstOrFail();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $values = json_decode($gpg->decrypt($settings->value), associative: true);

        // Verify cost=10 is embedded in the bcrypt hash ($2y$10$...)
        $this->assertStringStartsWith('$2y$10$', $values['secret_token']);
        $this->assertTrue(password_verify($data['secret_token'], $values['secret_token']));
    }

    /**
     * Test that default bcrypt cost factor is 12.
     */
    public function testScimSetSettingsController_Create_DefaultCostFactor(): void
    {
        $this->logInAsAdmin();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];
        $this->postJson('/scim/settings.json', $data);
        $this->assertSuccess();

        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $settings */
        $settings = ScimSettingFactory::find()->firstOrFail();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $values = json_decode($gpg->decrypt($settings->value), associative: true);

        // Default cost is 12
        $this->assertStringStartsWith('$2y$12$', $values['secret_token']);
        $this->assertTrue(password_verify($data['secret_token'], $values['secret_token']));
    }

    /**
     * Test setSettings method: update operation plugin disabled
     *
     * @return void
     */
    public function testScimSetSettingsController_Update_Error_PluginDisabled(): void
    {
        $this->setupUpdate();
        $this->disableFeaturePlugin(ScimPlugin::class);

        try {
            $this->putJson("/scim/settings/{$this->current->id}.json");
        } catch (Throwable $t) {
        }

        $this->assertResponseCode(404);
    }

    /**
     * Test setSettings method: update operation guest forbidden
     *
     * @return void
     */
    public function testScimSetSettingsController_Update_Error_GuestForbidden(): void
    {
        $this->setupUpdate();
        $this->logInAsUser();

        $this->putJson("/scim/settings/{$this->current->id}.json");

        $this->assertResponseCode(403);
    }

    /**
     * Test setSettings method: update operation unauthenticated
     *
     * @return void
     */
    public function testScimSetSettingsController_Update_Error_Unauthenticated()
    {
        $this->setupUpdate();
        $this->putJson("/scim/settings/{$this->current->id}.json");

        $this->assertResponseCode(401);
    }

    /**
     * Test setSettings method: update operation wrong UUID
     *
     * @return void
     */
    public function testScimSetSettingsController_Update_WrongUUID()
    {
        $this->setupUpdate();
        $this->logInAsAdmin();

        $wrongUuid = UuidFactory::uuid();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $this->putJson("/scim/settings/{$wrongUuid}.json", $data);

        $this->assertResponseCode(404);
    }

    /**
     * Test setSettings method: update operation not UUID
     *
     * @return void
     */
    public function testScimSetSettingsController_Update_NotUUID()
    {
        $this->setupUpdate();
        $this->logInAsAdmin();

        $wrongUuid = 'foo';

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $this->putJson("/scim/settings/{$wrongUuid}.json", $data);
        $this->assertBadRequestError('The SCIM setting identifier should be a valid UUID.');
    }

    /**
     * Test setSettings method: update operation success
     *
     * @return void
     */
    public function testScimSetSettingsController_Update_Success()
    {
        $this->setupUpdate();
        $this->logInAsAdmin();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];
        $this->putJson("/scim/settings/{$this->current->id}.json", $data);

        $response = $this->_responseJsonBody;

        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $previousValues = json_decode($gpg->decrypt($this->current->value), associative: true);
        $this->assertSuccess();
        $this->assertSame($previousValues['setting_id'], $response->setting_id);
        $this->assertSame($data['scim_user_id'], $response->scim_user_id);
        $this->assertTrue(!isset($response->secret_token));
        $this->assertObjectHasAttribute('id', $response);

        //Check if secret token was correctly updated
        $this->current = $this->fetchTable('Passbolt/Scim.ScimSettings')->find()->first();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $newValues = json_decode($gpg->decrypt($this->current->value), associative: true);

        $this->assertTrue(password_verify($data['secret_token'], $newValues['secret_token']));
    }

    /**
     * Test setSettings method: update operation success
     * No secret token passed, should keep previous value
     *
     * @return void
     * @throws \Exception
     */
    public function testScimSetSettingsController_Update_Success_NoSecretToken()
    {
        $this->setupUpdate();
        $this->logInAsAdmin();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'scim_user_id' => $user->id,
        ];
        $this->putJson("/scim/settings/{$this->current->id}.json", $data);

        $response = $this->_responseJsonBody;

        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $previousValues = json_decode($gpg->decrypt($this->current->value), associative: true);
        $this->assertSuccess();
        $this->assertSame($previousValues['setting_id'], $response->setting_id);
        $this->assertSame($data['scim_user_id'], $response->scim_user_id);
        $this->assertTrue(!isset($response->secret_token));
        $this->assertObjectHasAttribute('id', $response);

        //Check if secret token was correctly updated
        $this->current = ScimSettingFactory::find()->first();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $newValues = json_decode($gpg->decrypt($this->current->value), associative: true);

        $this->assertSame($previousValues['secret_token'], $newValues['secret_token']);
    }

    public function testScimSetSettingsController_Create_SetsExpiredDate(): void
    {
        $this->logInAsAdmin();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];
        $this->postJson('/scim/settings.json', $data);

        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $expectedExpired = Date::now()->modify('+1 year')->format('Y-m-d');
        $this->assertSame($expectedExpired, $response->expired);

        // Verify persisted in encrypted blob
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $settings */
        $settings = ScimSettingFactory::find()->firstOrFail();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $values = json_decode($gpg->decrypt($settings->value), associative: true);
        $this->assertSame($expectedExpired, $values['expired']);
    }

    public function testScimSetSettingsController_Update_TokenRotation_RecomputesExpired(): void
    {
        $this->setupUpdate();
        $this->logInAsAdmin();

        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $previousValues = json_decode($gpg->decrypt($this->current->value), associative: true);

        // Use a different expiry to confirm recomputation
        Configure::write('passbolt.plugins.scim.security.secretToken.expiry', '6 months');

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];
        $this->putJson("/scim/settings/{$this->current->id}.json", $data);

        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $expectedExpired = Date::now()->modify('+6 months')->format('Y-m-d');
        $this->assertSame($expectedExpired, $response->expired);
        $this->assertNotSame($previousValues['expired'], $response->expired);
    }

    public function testScimSetSettingsController_Update_NoTokenSent_PreservesExpired(): void
    {
        $this->setupUpdate();
        $this->logInAsAdmin();

        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $previousValues = json_decode($gpg->decrypt($this->current->value), associative: true);

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $data = [
            'scim_user_id' => $user->id,
        ];
        $this->putJson("/scim/settings/{$this->current->id}.json", $data);

        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $this->assertSame($previousValues['expired'], $response->expired);
    }

    public function testScimSetSettingsController_Update_SameTokenSent_PreservesExpired(): void
    {
        $this->setupUpdate();
        $this->logInAsAdmin();

        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $previousValues = json_decode($gpg->decrypt($this->current->value), associative: true);

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        // Client sends back the same plaintext token
        $data = [
            'scim_user_id' => $user->id,
            'secret_token' => 'pb_0000000000000000000000000000000000000000000',
        ];
        $this->putJson("/scim/settings/{$this->current->id}.json", $data);

        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $this->assertSame($previousValues['expired'], $response->expired);
    }
}
