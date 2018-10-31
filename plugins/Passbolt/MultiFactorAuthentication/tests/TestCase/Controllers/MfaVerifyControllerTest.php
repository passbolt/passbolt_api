<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers;

use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;

class MfaVerifyControllerTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaVerify
     */
    public function testMfaVerifyGetWrongProvider()
    {
        $this->get('/mfa/verify/nope.json?api-version=v2');
        $this->assertResponseError();
    }

    /**
     * @group mfa
     * @group mfaVerify
     */
    public function testMfaVerifyPostWrongProvider()
    {
        $this->post('/mfa/verify/nope.json?api-version=v2', []);
        $this->assertResponseError();
    }
}
