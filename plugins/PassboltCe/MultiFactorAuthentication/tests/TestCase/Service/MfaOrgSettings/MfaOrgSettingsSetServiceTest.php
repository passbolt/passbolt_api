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
 * @since         3.7.3
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\MfaOrgSettings;

use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsSetService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaOrgSettingsTestTrait;
use Passbolt\MultiFactorAuthentication\Test\Mock\DuoSdkClientMock;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaOrgSettingsSetServiceTest extends TestCase
{
    use MfaOrgSettingsTestTrait;
    use TruncateDirtyTables;

    public function setUp(): void
    {
        parent::setUp();
        MfaSettings::clear();
    }

    public function testMfaOrgSettingsSetService_Valid()
    {
        $user = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $data = $this->getDefaultMfaOrgSettings();

        $duoSdkClientMock = DuoSdkClientMock::createDefault($this->getMockBuilder(Client::class), $user)->getClient();
        $service = new MfaOrgSettingsSetService();
        $returnedSettings = $service->setOrgSettings($data, $uac, $duoSdkClientMock);

        $expectedReturnedSettings = ['totp', 'duo', 'yubikey'];

        $this->assertEqualsCanonicalizing($expectedReturnedSettings, $returnedSettings['providers']);
    }

    public function testMfaOrgSettingsSetService_Valid_NoProvidersDisabled()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);
        $data = $this->getDefaultMfaOrgSettings();

        $duoSdkClientMock = DuoSdkClientMock::createDefault($this->getMockBuilder(Client::class), $user)->getClient();
        $service = new MfaOrgSettingsSetService();
        $returnedSettings = $service->setOrgSettings($data, $uac, $duoSdkClientMock);

        $expectedReturnedSettings = ['totp', 'duo', 'yubikey'];

        $this->assertEqualsCanonicalizing($expectedReturnedSettings, $returnedSettings['providers']);
    }

    public function testMfaOrgSettingsSetService_Valid_OneProviderDisabled()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);

        $config['providers'] = [MfaSettings::PROVIDER_TOTP => true, MfaSettings::PROVIDER_DUO => true];
        $this->mockMfaOrgSettings($config, 'database', $uac);
        $data = $this->getDefaultMfaOrgSettings();

        // Remove totp
        $data[MfaSettings::PROVIDERS] = array_merge($data[MfaSettings::PROVIDERS], [MfaSettings::PROVIDER_TOTP => false]);

        $duoSdkClientMock = DuoSdkClientMock::createDefault($this->getMockBuilder(Client::class), $user)->getClient();
        $service = new MfaOrgSettingsSetService();
        $returnedSettings = $service->setOrgSettings($data, $uac, $duoSdkClientMock);

        $expectedReturnedSettings = ['duo', 'yubikey'];

        $this->assertEqualsCanonicalizing($expectedReturnedSettings, $returnedSettings['providers']);
    }

    public function testMfaOrgSettingsSetService_Valid_AllProvidersDisabled()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);

        $config['providers'] = [MfaSettings::PROVIDER_TOTP => true, MfaSettings::PROVIDER_DUO => true];
        $this->mockMfaOrgSettings($config, 'database', $uac);
        $data = $this->getDefaultMfaOrgSettings();

        // Remove totp and duo
        $data[MfaSettings::PROVIDERS] = array_merge(
            $data[MfaSettings::PROVIDERS],
            [
                MfaSettings::PROVIDER_TOTP => false,
                MfaSettings::PROVIDER_DUO => false,
            ],
        );
        unset($data[MfaSettings::PROVIDER_DUO]);

        $duoSdkClientMock = DuoSdkClientMock::createDefault($this->getMockBuilder(Client::class), $user)->getClient();
        $service = new MfaOrgSettingsSetService();
        $returnedSettings = $service->setOrgSettings($data, $uac, $duoSdkClientMock);

        $expectedReturnedSettings = ['yubikey'];

        $this->assertEqualsCanonicalizing($expectedReturnedSettings, $returnedSettings['providers']);
    }

    public function testMfaOrgSettingsSetService_Valid_MultipleProvidersDisabled()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->admin()->persist();
        $uac = new UserAccessControl(Role::ADMIN, $user->id);

        $config['providers'] = [MfaSettings::PROVIDER_TOTP => true, MfaSettings::PROVIDER_DUO => true, MfaSettings::PROVIDER_YUBIKEY => true];
        $this->mockMfaOrgSettings($config, 'database', $uac);
        $data = $this->getDefaultMfaOrgSettings();

        // Remove totp and duo
        $data[MfaSettings::PROVIDERS] = array_merge(
            $data[MfaSettings::PROVIDERS],
            [
                MfaSettings::PROVIDER_TOTP => false,
                MfaSettings::PROVIDER_DUO => false,
            ],
        );
        unset($data[MfaSettings::PROVIDER_DUO]);

        $duoSdkClientMock = DuoSdkClientMock::createDefault($this->getMockBuilder(Client::class), $user)->getClient();
        $service = new MfaOrgSettingsSetService();
        $returnedSettings = $service->setOrgSettings($data, $uac, $duoSdkClientMock);

        $expectedReturnedSettings = ['yubikey'];

        $this->assertEqualsCanonicalizing($expectedReturnedSettings, $returnedSettings['providers']);
    }
}
