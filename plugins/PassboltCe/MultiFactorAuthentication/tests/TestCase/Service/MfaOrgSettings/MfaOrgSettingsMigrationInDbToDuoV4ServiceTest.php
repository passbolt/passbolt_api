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
 * @since         3.11.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\MfaOrgSettings;

use App\Error\Exception\CustomValidationException;
use App\Test\Factory\UserFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsMigrationInDbToDuoV4Service;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaOrganizationSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaOrgSettingsTestTrait;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaOrgSettingsMigrationInDbToDuoV4ServiceTest extends TestCase
{
    use MfaOrgSettingsTestTrait;
    use TruncateDirtyTables;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsMigrationInDbToDuoV4Service
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        MfaSettings::clear();
        $this->service = new MfaOrgSettingsMigrationInDbToDuoV4Service();
        $this->loadPlugins(['Passbolt/MultiFactorAuthentication' => []]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
    }

    public function testMfaOrgSettingsMigrationInDbToDuoV4Service_Success()
    {
        UserFactory::make()->admin()->persist();
        $settings = $this->getDefaultMfaOrgSettings();
        $this->mockMfaOrgSettings($settings);

        $this->service->migrate();

        $settings['providers'] = ['totp', 'duo', 'yubikey'];
        $settingsInDB = $this->getMfaOrganizationSettingValue();
        $this->assertEquals($settings, $settingsInDB);
    }

    public function testMfaOrgSettingsMigrationInDbToDuoV4Service_Success_No_Existing_Settings()
    {
        UserFactory::make()->admin()->persist();

        $this->service->migrate();

        $settings['providers'] = [];
        $settingsInDB = $this->getMfaOrganizationSettingValue();
        $this->assertEquals($settings, $settingsInDB);
    }

    public function testMfaOrgSettingsMigrationInDbToDuoV4Service_Success_From_V2()
    {
        UserFactory::make()->admin()->persist();
        $settings = $this->getDefaultMfaOrgSettings();
        $settings[MfaSettings::PROVIDER_DUO] = $this->getDefaultDuoV2OrgSettings();
        $this->mockMfaOrgSettings($settings);

        $this->service->migrate();

        $settings['providers'] = ['totp', 'duo', 'yubikey'];
        $settingsInDB = $this->getMfaOrganizationSettingValue();
        $settings[MfaSettings::PROVIDER_DUO] = $this->getDefaultDuoV4OrgSettings();
        $this->assertEquals($settings, $settingsInDB);
    }

    public function testMfaOrgSettingsMigrationInDbToDuoV4Service_Success_From_V4()
    {
        UserFactory::make()->admin()->persist();

        $settings = $this->getDefaultMfaOrgSettings();
        $settings[MfaSettings::PROVIDER_DUO] = $this->getDefaultDuoV4OrgSettings();
        $this->mockMfaOrgSettings($settings);

        $this->service->migrate();

        $settings['providers'] = ['totp', 'duo', 'yubikey'];
        $settingsInDB = $this->getMfaOrganizationSettingValue();
        $this->assertEquals($settings, $settingsInDB);
    }

    public function testMfaOrgSettingsMigrationInDbToDuoV4Service_Missing_Settings()
    {
        UserFactory::make()->admin()->persist();

        $settings = $this->getDefaultMfaOrgSettings();
        $settings[MfaSettings::PROVIDER_DUO] = [];
        $this->mockMfaOrgSettings($settings);

        $this->service->migrate();

        $settingsInDB = $this->getMfaOrganizationSettingValue();
        $settings['providers'] = ['totp', 'yubikey'];
        unset($settings[MfaSettings::PROVIDER_DUO]);
        $this->assertEquals($settings, $settingsInDB);
    }

    public function testMfaOrgSettingsMigrationInDbToDuoV4Service_Invalid_Provider_Instead_Of_Duo()
    {
        UserFactory::make()->admin()->persist();

        $settings = $this->getDefaultMfaOrgSettings();
        $settings['providers'] = ['totp', 'not_duo', 'yubikey'];
        $this->mockMfaOrgSettings($settings);

        $this->service->migrate();

        $settingsInDB = $this->getMfaOrganizationSettingValue();
        $settings['providers'] = ['totp', 'yubikey'];
        unset($settings[MfaSettings::PROVIDER_DUO]);
        $this->assertEquals($settings, $settingsInDB);
    }

    public function testMfaOrgSettingsMigrationInDbToDuoV4Service_Invalid_Settings_From_V2()
    {
        UserFactory::make()->admin()->persist();

        $settings = $this->getDefaultMfaOrgSettings();
        $settings[MfaSettings::PROVIDER_DUO] = $this->getDefaultDuoV2OrgSettings();
        $settings[MfaSettings::PROVIDER_DUO]['secretKey'] = 'invalid';
        $this->mockMfaOrgSettings($settings);

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Could not validate multi-factor authentication provider configuration.');
        $this->service->migrate();
    }

    public function testMfaOrgSettingsMigrationInDbToDuoV4Service_Invalid_Settings_From_V4()
    {
        UserFactory::make()->admin()->persist();

        $settings = $this->getDefaultMfaOrgSettings();
        $settings[MfaSettings::PROVIDER_DUO] = $this->getDefaultDuoV4OrgSettings();
        $settings[MfaSettings::PROVIDER_DUO]['clientSecret'] = 'invalid';
        $this->mockMfaOrgSettings($settings);

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('Could not validate multi-factor authentication provider configuration.');
        $this->service->migrate();
    }

    public function testMfaOrgSettingsMigrationInDbToDuoV4Service_NoAdmin_Should_Not_Save_Settings()
    {
        $this->service->migrate();
        $this->assertSame(0, MfaOrganizationSettingFactory::count());
    }
}
