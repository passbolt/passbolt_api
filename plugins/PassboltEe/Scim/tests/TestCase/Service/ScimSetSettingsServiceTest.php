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
use Cake\Http\Exception\BadRequestException;
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
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
    }

    public function testGenerateToken()
    {
        $token = ScimSetSettingsService::generateToken();
        $this->assertStringStartsWith('pb_', $token);
        $this->assertSame(46, strlen($token));
    }

    public function testSaveSettingsCreate_Success()
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

    public function testSaveSettingsCreate_AlreadySet()
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

    public function testSaveSettingsCreate_ValidationError()
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

    public function testSaveSettingsUpdate_Success()
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

    public function testSaveSettingsUpdate_ValidationError()
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
}
