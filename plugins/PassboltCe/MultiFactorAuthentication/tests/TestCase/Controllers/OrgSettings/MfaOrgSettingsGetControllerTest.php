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
 * @since         2.6.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\OrgSettings;

use App\Model\Entity\Role;
use Cake\Core\Configure;
use Passbolt\MultiFactorAuthentication\Service\Duo\MfaDuoVerifyDuoCodeService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaOrganizationSettingFactory;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use PHPUnit\Framework\Attributes\DataProvider;

class MfaOrgSettingsGetControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsGet
     */
    public function testMfaOrgSettingsGetControllerNotLoggedIn()
    {
        $this->getJson('/mfa/settings.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsGet
     */
    public function testMfaOrgSettingsGetControllerNotJson()
    {
        $this->authenticateAs('admin');
        $this->get('/mfa/settings');
        $this->assertResponseError('This is not a valid Ajax/Json request.');
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsGet
     */
    public function testMfaOrgSettingsGetControllerNotAllowed()
    {
        $this->authenticateAs('ada');
        $this->getJson('/mfa/settings.json?api-version=v2');
        $this->assertResponseError('You are not allowed to access this location.');
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsGet
     */
    public function testMfaOrgSettingsGetControllerSuccess_NoConfigureNoDatabase()
    {
        $this->mockMfaOrgSettings(['providers' => [MfaSettings::PROVIDER_TOTP => 0]]);
        $this->authenticateAs('admin');
        $this->getJson('/mfa/settings.json?api-version=v2');
        $this->assertResponseSuccess();

        $this->assertNotNull($this->_responseJsonBody);
        $this->assertNotNull($this->_responseJsonBody->providers);
        $this->assertTrue(count($this->_responseJsonBody->providers) === 0);
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsGet
     */
    public function testMfaOrgSettingsGetControllerSuccess_ConfigureDrivenOnly()
    {
        $config['providers'] = [MfaSettings::PROVIDER_TOTP => true];
        $this->mockMfaOrgSettings($config, 'configure');
        $this->authenticateAs('admin');
        $this->getJson('/mfa/settings.json?api-version=v2');
        $this->assertResponseSuccess();

        $this->assertNotNull($this->_responseJsonBody);
        $this->assertNotNull($this->_responseJsonBody->providers);
        $this->assertTrue(in_array(MfaSettings::PROVIDER_TOTP, $this->_responseJsonBody->providers));
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsGet
     */
    public function testMfaOrgSettingsGetControllerSuccess_DatabaseDrivenAllDisabled()
    {
        $config['providers'] = [MfaSettings::PROVIDER_TOTP => true];
        $this->mockMfaOrgSettings($config, 'configure');
        $config2['providers'] = [];
        $this->mockMfaOrgSettings($config2, 'database', $this->mockUserAccessControl('admin', Role::ADMIN));
        $this->authenticateAs('admin');
        $this->getJson('/mfa/settings.json?api-version=v2');
        $this->assertResponseSuccess();

        $this->assertNotNull($this->_responseJsonBody);
        $this->assertTrue(count($this->_responseJsonBody->providers) === 0);
    }

    /**
     * @group mfa
     * @group mfaOrgSettings
     * @group mfaOrgSettingsGet
     */
    public function testMfaOrgSettingsGetControllerSuccess_DatabaseOnly()
    {
        $config2['providers'] = [];
        $this->mockMfaOrgSettings($config2, 'database', $this->mockUserAccessControl('admin', Role::ADMIN));
        $this->authenticateAs('admin');
        $this->getJson('/mfa/settings.json?api-version=v2');
        $this->assertResponseSuccess();

        $this->assertNotNull($this->_responseJsonBody);
        $this->assertNotNull($this->_responseJsonBody->providers);
        $this->assertTrue(count($this->_responseJsonBody->providers) === 0);
    }

    public function testMfaOrgSettingsGetController_Success_DuoStoredVerifySubscriberFieldIsReturned(): void
    {
        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, true);
        MfaOrganizationSettingFactory::make()->duo(verifySubscriber: false)->persist();
        $this->logInAsAdmin();

        $this->getJson('/mfa/settings.json');

        $this->assertResponseSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertFalse($response['duo']['verifySubscriber']);
        $this->assertArrayHasKey('integrationKey', $response['duo']);
    }

    public static function legacyFlagProvider(): array
    {
        return [
            'legacy flag true' => [true],
            'legacy flag false' => [false],
        ];
    }

    #[DataProvider('legacyFlagProvider')]
    public function testMfaOrgSettingsGetController_Success_FallbacksOnConfigurationValue(bool $legacyFlag): void
    {
        Configure::write(MfaDuoVerifyDuoCodeService::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER, $legacyFlag);
        MfaOrganizationSettingFactory::make()->duo()->persist();
        $this->logInAsAdmin();

        $this->getJson('/mfa/settings.json');

        $this->assertResponseSuccess();
        $response = $this->getResponseBodyAsArray();
        $this->assertSame($legacyFlag, $response['duo']['verifySubscriber']);
    }
}
