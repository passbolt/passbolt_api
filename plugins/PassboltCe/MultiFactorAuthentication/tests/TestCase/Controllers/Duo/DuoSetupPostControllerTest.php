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

use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;

class DuoSetupPostControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaSetup
     */
    public function testMfaSetupPostDuo_NotAuthenticated()
    {
        $this->post('/mfa/setup/duo.json?api-version=v2', []);
        $this->assertResponseCode(401);
    }

    /**
     * @group mfa
     */
    public function testMfaSetupPostDuo_Success()
    {
        $this->logInAsUser();
        $this->post('/mfa/setup/duo?api-version=v2');
        $this->assertResponseCode(410);
    }
}
