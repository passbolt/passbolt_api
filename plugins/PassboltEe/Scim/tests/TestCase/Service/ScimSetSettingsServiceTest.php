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

namespace Passbolt\Scim\Test\TestCase\Service;

use App\Error\Exception\FormValidationException;
use App\Service\OpenPGP\OpenPGPCommonServerOperationsTrait;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\Date;
use Exception;
use Passbolt\Scim\Service\ScimSetSettingsService;
use Passbolt\Scim\Test\Factory\ScimSettingFactory;

/**
 * ScimGetSettingsServiceTest class
 */
class ScimSetSettingsServiceTest extends AppTestCase
{
    use OpenPGPCommonServerOperationsTrait;

    /**
     * @var \Passbolt\Scim\Service\ScimSetSettingsService
     */
    protected ScimSetSettingsService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ScimSetSettingsService();
        // Ensure the SCIM expiry config is available (normally loaded by plugin bootstrap)
        if (Configure::read('passbolt.plugins.scim.security.secretToken.expiry') === null) {
            Configure::write('passbolt.plugins.scim.security.secretToken.expiry', '1 year');
        }
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
    }

    public function testScimSetSettingsService_GenerateToken()
    {
        $token = ScimSetSettingsService::generateToken();
        $this->assertStringStartsWith('pb_', $token);
        $this->assertSame(46, strlen($token));
    }

    public function testScimSetSettingsService_SaveSettingsCreate_Success()
    {
        $scimSettings = ScimSettingFactory::find()->first();
        $this->assertNull($scimSettings);

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $settings = $this->service->saveSettings($ua, $data);
        $this->assertSame($data['setting_id'], $settings['setting_id']);
        $this->assertSame($data['scim_user_id'], $settings['scim_user_id']);
        $this->assertArrayNotHasKey('secret_token', $settings);
        $this->assertStringContainsString('/scim/v2/' . $data['setting_id'], $settings['base_api_endpoint']);

        $scimSettings = ScimSettingFactory::find()->first();
        $this->assertNotNull($scimSettings);
    }

    public function testScimSetSettingsService_SaveSettingsCreate_AlreadySet()
    {
        ScimSettingFactory::make()->default()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Please delete previous settings before creating again.');
        $this->service->saveSettings($ua, $data);
    }

    public function testScimSetSettingsService_SaveSettingsCreate_ValidationError()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data);
        } catch (Exception $e) {
        }

        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'secret_token' => [
                '_empty' => 'The secret token should not be empty.',
            ],
            'scim_user_id' => [
                '_empty' => 'This field cannot be left empty',
            ],
            'setting_id' => [
                '_empty' => 'The ID for the SCIM settings should not be empty.',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsCreate_Validation_TokenError()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => 'WRONG_TOKEN',
        ];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data);
        } catch (Exception $e) {
        }

        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame(400, $e->getCode());
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'secret_token' => [
                'correctFormat' => 'The secret token format is incorrect.',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsCreate_Validation_UuidError()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => 'WRONG_UUID',
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data);
        } catch (Exception $e) {
        }

        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame(400, $e->getCode());
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'scim_user_id' => [
                'uuid' => 'The identifier of the default user should be a valid UUID.',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsCreate_Validation_InactiveUserError()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        /** @var \App\Model\Entity\User $userNotActive */
        $userNotActive = UserFactory::make()->inactive()->persist();

        $scimUserIdNotActive = $userNotActive->id;

        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $scimUserIdNotActive,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data);
        } catch (Exception $e) {
        }

        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame(400, $e->getCode());
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'scim_user_id' => [
                'activeAndEnabled' => 'The user is not active, disabled or does not exist.',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsCreate_Validation_DisabledUserError()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        /** @var \App\Model\Entity\User $userDisabled */
        $userDisabled = UserFactory::make()->disabled()->persist();

        $scimUserIdDisabled = $userDisabled->id;

        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $scimUserIdDisabled,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data);
        } catch (Exception $e) {
        }

        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame(400, $e->getCode());
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'scim_user_id' => [
                'activeAndEnabled' => 'The user is not active, disabled or does not exist.',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsCreate_Validation_NonExistingUserError()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => UuidFactory::uuid(),
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data);
        } catch (Exception $e) {
        }

        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame(400, $e->getCode());
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'scim_user_id' => [
                'activeAndEnabled' => 'The user is not active, disabled or does not exist.',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsCreate_Validation_InvalidSettingError()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'setting_id' => 'WRONG_UUID',
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data);
        } catch (Exception $e) {
        }

        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame(400, $e->getCode());
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'setting_id' => [
                'uuid' => 'The ID for the SCIM settings should be a valid UUID.',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsUpdate_Success()
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $existingSettings */
        $existingSettings = ScimSettingFactory::make()->default()->persist();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $existingData = json_decode($gpg->decrypt($existingSettings->value), associative: true);
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $newData = [
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $settings = $this->service->saveSettings($ua, $newData, $existingSettings->id);
        $this->assertSame($newData['scim_user_id'], $settings['scim_user_id']);
        $this->assertSame($existingData['setting_id'], $settings['setting_id']);
        $this->assertArrayNotHasKey('secret_token', $settings);
        $this->assertStringContainsString('/scim/v2/' . $existingData['setting_id'], $settings['base_api_endpoint']);

        $updatedScimSettings = ScimSettingFactory::find()->first();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $updatedData = json_decode($gpg->decrypt($updatedScimSettings->value), associative: true);

        $this->assertSame($existingSettings->id, $updatedScimSettings->id);
        $this->assertSame($updatedData['scim_user_id'], $newData['scim_user_id']);

        $this->assertSame($updatedData['setting_id'], $existingData['setting_id']);
        $this->assertNotSame($updatedData['scim_user_id'], $existingData['scim_user_id']);
        $this->assertNotSame($updatedData['secret_token'], $existingData['secret_token']);
    }

    public function testScimSetSettingsService_SaveSettingsUpdate_ValidationError()
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $scimSettings */
        $scimSettings = ScimSettingFactory::make()->default()->persist();

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data, $scimSettings->id);
        } catch (Exception $e) {
        }
        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'scim_user_id' => [
                '_empty' => 'This field cannot be left empty',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsUpdate_Validation_TokenError()
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $scimSettings */
        $scimSettings = ScimSettingFactory::make()->default()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'scim_user_id' => $user->id,
            'secret_token' => 'WRONG_TOKEN',
        ];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data, $scimSettings->id);
        } catch (Exception $e) {
        }

        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame(400, $e->getCode());
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'secret_token' => [
                'correctFormat' => 'The secret token format is incorrect.',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsUpdate_Validation_UuidError()
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $scimSettings */
        $scimSettings = ScimSettingFactory::make()->default()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'scim_user_id' => 'WRONG_UUID',
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data, $scimSettings->id);
        } catch (Exception $e) {
        }

        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame(400, $e->getCode());
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'scim_user_id' => [
                'uuid' => 'The identifier of the default user should be a valid UUID.',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsUpdate_Validation_InactiveUserError()
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $scimSettings */
        $scimSettings = ScimSettingFactory::make()->default()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        /** @var \App\Model\Entity\User $userNotActive */
        $userNotActive = UserFactory::make()->inactive()->persist();

        $scimUserIdNotActive = $userNotActive->id;

        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'scim_user_id' => $scimUserIdNotActive,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data, $scimSettings->id);
        } catch (Exception $e) {
        }

        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame(400, $e->getCode());
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'scim_user_id' => [
                'activeAndEnabled' => 'The user is not active, disabled or does not exist.',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsUpdate_Validation_DisabledUserError()
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $scimSettings */
        $scimSettings = ScimSettingFactory::make()->default()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        /** @var \App\Model\Entity\User $userDisabled */
        $userDisabled = UserFactory::make()->disabled()->persist();

        $scimUserIdDisabled = $userDisabled->id;

        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'scim_user_id' => $scimUserIdDisabled,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data, $scimSettings->id);
        } catch (Exception $e) {
        }

        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame(400, $e->getCode());
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'scim_user_id' => [
                'activeAndEnabled' => 'The user is not active, disabled or does not exist.',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsUpdate_Validation_NonExistingUserError()
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $scimSettings */
        $scimSettings = ScimSettingFactory::make()->default()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'scim_user_id' => UuidFactory::uuid(),
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data, $scimSettings->id);
        } catch (Exception $e) {
        }

        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame(400, $e->getCode());
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'scim_user_id' => [
                'activeAndEnabled' => 'The user is not active, disabled or does not exist.',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsUpdate_Validation_InvalidSettingsError()
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $scimSettings */
        $scimSettings = ScimSettingFactory::make()->default()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();

        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $e = null;
        try {
            $this->service->saveSettings($ua, $data, $scimSettings->id);
        } catch (Exception $e) {
        }

        $this->assertNotNull($e);
        $this->assertInstanceOf(FormValidationException::class, $e);
        $this->assertSame(400, $e->getCode());
        $this->assertSame('Could not validate the SCIM settings.', $e->getMessage());
        $this->assertSame([
            'setting_id' => [
                'ensureEmpty' => 'The Setting ID cannot be passed on update.',
            ],
        ], $e->getErrors());
    }

    public function testScimSetSettingsService_SaveSettingsCreate_SetsExpiredDate()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $settings = $this->service->saveSettings($ua, $data);

        $expectedExpired = Date::now()->modify('+1 year')->format('Y-m-d');
        $this->assertSame($expectedExpired, $settings['expired']);

        // Verify persisted in encrypted blob
        $scimSettings = ScimSettingFactory::find()->first();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $storedData = json_decode($gpg->decrypt($scimSettings->value), associative: true);
        $this->assertSame($expectedExpired, $storedData['expired']);
    }

    public function testScimSetSettingsService_SaveSettingsCreate_CustomExpiry()
    {
        Configure::write('passbolt.plugins.scim.security.secretToken.expiry', '30 days');

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $settings = $this->service->saveSettings($ua, $data);

        $expectedExpired = Date::now()->modify('+30 days')->format('Y-m-d');
        $this->assertSame($expectedExpired, $settings['expired']);
    }

    public function testScimSetSettingsService_SaveSettingsCreate_InvalidExpiryConfig()
    {
        Configure::write('passbolt.plugins.scim.security.secretToken.expiry', null);

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $data = [
            'setting_id' => UuidFactory::uuid(),
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The SCIM secret token expiry configuration is invalid.');
        $this->service->saveSettings($ua, $data);
    }

    public function testScimSetSettingsService_SaveSettingsUpdate_TokenRotation_RecomputesExpired()
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $existingSettings */
        $existingSettings = ScimSettingFactory::make()->default()->persist();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $existingData = json_decode($gpg->decrypt($existingSettings->value), associative: true);

        // Use a different expiry to verify recomputation
        Configure::write('passbolt.plugins.scim.security.secretToken.expiry', '6 months');

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $newData = [
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::generateToken(),
        ];

        $settings = $this->service->saveSettings($ua, $newData, $existingSettings->id);

        $expectedExpired = Date::now()->modify('+6 months')->format('Y-m-d');
        $this->assertSame($expectedExpired, $settings['expired']);
        $this->assertNotSame($existingData['expired'], $settings['expired']);
    }

    public function testScimSetSettingsService_SaveSettingsUpdate_NoTokenSent_PreservesExpired()
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $existingSettings */
        $existingSettings = ScimSettingFactory::make()->default()->persist();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $existingData = json_decode($gpg->decrypt($existingSettings->value), associative: true);

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        $newData = [
            'scim_user_id' => $user->id,
        ];

        $settings = $this->service->saveSettings($ua, $newData, $existingSettings->id);

        $this->assertSame($existingData['expired'], $settings['expired']);
    }

    /**
     * When a legacy SHA-256 hashed token is stored and the client sends the same
     * plaintext token on update, isTokenRotated() should detect that the token has
     * NOT changed and preserve the existing expired date.
     *
     * @return void
     */
    public function testScimSetSettingsService_SaveSettingsUpdate_LegacySha256SameToken_PreservesExpired(): void
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $existingSettings */
        $existingSettings = ScimSettingFactory::make()->legacySecretTokenFormat()->persist();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $existingData = json_decode($gpg->decrypt($existingSettings->value), associative: true);
        $originalExpired = $existingData['expired'];

        // Use a different expiry config so a recomputation would produce a visibly different date
        Configure::write('passbolt.plugins.scim.security.secretToken.expiry', '5 years');

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);

        // Send the SAME plaintext token that was used to create the legacy SHA-256 hash.
        // The token has not rotated — expired should be preserved.
        $newData = [
            'scim_user_id' => $user->id,
            'secret_token' => ScimSettingFactory::SCIM_TEST_SECRET_TOKEN,
        ];

        $settings = $this->service->saveSettings($ua, $newData, $existingSettings->id);

        // The expired date should remain unchanged because the token is the same
        $this->assertSame($originalExpired, $settings['expired']);
    }

    public function testScimSetSettingsService_SaveSettingsUpdate_DummyTokenSent_PreservesExpired()
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimSetting $existingSettings */
        $existingSettings = ScimSettingFactory::make()->default()->persist();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $existingData = json_decode($gpg->decrypt($existingSettings->value), associative: true);

        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $ua = new UserAccessControl($user->role->name, $user->id, $user->username);
        // Client sends the dummy token placeholder when the token should not change
        $newData = [
            'scim_user_id' => $user->id,
            'secret_token' => ScimSetSettingsService::SCIM_SECRET_TOKEN_DUMMY,
        ];

        $settings = $this->service->saveSettings($ua, $newData, $existingSettings->id);

        $this->assertSame($existingData['expired'], $settings['expired']);

        // Verify the stored token was not overwritten with the dummy hash
        $updatedSettings = ScimSettingFactory::find()->first();
        $gpg = OpenPGPBackendFactory::get();
        $gpg = $this->setDecryptKeyWithServerKey($gpg);
        $updatedData = json_decode($gpg->decrypt($updatedSettings->value), associative: true);
        $this->assertSame($existingData['secret_token'], $updatedData['secret_token']);
    }
}
