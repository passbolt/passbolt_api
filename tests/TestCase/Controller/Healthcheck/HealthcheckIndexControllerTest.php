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

use Cake\Core\Configure;
use App\Test\Lib\AppIntegrationTestCase;

class HealthcheckIndexControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.users', 'app.roles', 'app.profiles', 'app.authentication_tokens'];

    public function setUp()
    {
        Configure::write('passbolt.test.isRunning', true);
        parent::setUp();
    }

    public function testHealthcheckIndexOk()
    {
        $this->get('/healthcheck');
        $this->assertResponseContains('Passbolt API Status');
        $this->assertResponseOk();
    }

    public function testHealthcheckIndexJsonOk()
    {
        $this->getJson('/healthcheck.json');
        $this->assertResponseSuccess();
        $attributes = [
            'ssl','application', 'gpg', 'core', 'configFile', 'environment', 'database'
        ];
        foreach ($attributes as $attr) {
            $this->assertObjectHasAttribute($attr, $this->_responseJsonBody);
        }
    }

}
