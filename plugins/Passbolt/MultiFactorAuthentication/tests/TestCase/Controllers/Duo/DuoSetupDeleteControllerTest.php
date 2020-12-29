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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers\Duo;

use Passbolt\MultiFactorAuthentication\Test\Lib\MfaDuoSettingsTestTrait;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class DuoSetupDeleteControllerTest extends MfaIntegrationTestCase
{
    use MfaDuoSettingsTestTrait;

    /**
     * @var array
     */
    public $fixtures = [
        'plugin.Passbolt/AccountSettings.AccountSettings',
        'app.Base/Users',
        'app.Base/Roles',
    ];

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupDelete
     * @group mfaSetupDeleteDuo
     */
    public function testMfaSetupDeleteDuoNotAuthenticated()
    {
        $this->delete('/mfa/setup/duo.json?api-version=v2');
        $this->assertResponseError('You need to login to access this location.');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupDelete
     * @group mfaSetupDeleteDuo
     */
    public function testMfaSetupDeleteDuoSuccessNothingToDelete()
    {
        $this->authenticateAs('ada');
        $this->delete('/mfa/setup/duo.json?api-version=v2');
        $this->assertResponseSuccess();
        $this->assertResponseContains('Nothing to delete');
    }

    /**
     * @group mfa
     * @group mfaSetup
     * @group mfaSetupDelete
     * @group mfaSetupDeleteDuo
     */
    public function testMfaSetupDeleteDuoSuccessDeleted()
    {
        $this->mockMfaVerified('ada', MfaSettings::PROVIDER_DUO);
        $this->mockMfaDuoSettings('ada', 'valid');
        $this->authenticateAs('ada');
        $this->delete('/mfa/setup/duo.json?api-version=v2');
        $this->assertResponseSuccess();
        $this->assertResponseContains('The configuration was deleted.');
    }
}
