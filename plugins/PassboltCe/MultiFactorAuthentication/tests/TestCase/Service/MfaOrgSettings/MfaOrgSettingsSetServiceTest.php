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

        $duoSdkClientMock = DuoSdkClientMock::createDefault($this, $user)->getClient();
        $service = new MfaOrgSettingsSetService();
        $returnedSettings = $service->setOrgSettings($data, $uac, $duoSdkClientMock);

        // Reshape providers, as it is saved in a different format
        $data['providers'] = ['totp', 'duo', 'yubikey'];

        $this->assertEquals($data, $returnedSettings);
        $this->assertSame($data, $this->getMfaOrganizationSettingValue());
    }
}
