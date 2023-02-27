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

class DuoVerifyPostControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     */
    public function testMfaVerifyPostDuo_NotAuthenticated()
    {
        $this->post('/mfa/verify/duo.json?api-version=v2');
        $this->assertResponseCode(401);
    }

    /**
     * @group mfa
     * @group mfaVerify
     * @group mfaVerifyPost
     */
    public function testMfaVerifyPostDuo_Success()
    {
        $this->logInAsUser();
        $this->post('/mfa/verify/duo?api-version=v2');
        $this->assertResponseCode(410);
    }
}
