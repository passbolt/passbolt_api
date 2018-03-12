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
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Healthcheck;

use App\Test\Lib\AppIntegrationTestCase;

class HealthcheckStatusControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/users', 'app.Base/roles', 'app.Base/profiles', 'app.Base/authentication_tokens'];

    public function testHealthcheckStatusOk()
    {
        $this->get('/healthcheck/status');
        $this->assertResponseOk();
        $this->assertResponseContains('OK');
    }

    public function testHealthcheckStatusJsonOk()
    {
        $this->getJson('/healthcheck/status.json?api-version=v1');
        $this->assertResponseSuccess();
        $this->assertEquals($this->_responseJson->header->message, 'OK');
        $this->assertEquals($this->_responseJson->body, 'OK');
    }
}
