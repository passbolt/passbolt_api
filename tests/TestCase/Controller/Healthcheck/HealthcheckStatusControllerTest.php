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
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Healthcheck;

use App\Test\Lib\AppIntegrationTestCase;

/**
 * @covers \App\Controller\Healthcheck\HealthcheckStatusController
 */
class HealthcheckStatusControllerTest extends AppIntegrationTestCase
{
    public array $fixtures = ['app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles',];

    public function testHealthcheckStatusOk(): void
    {
        $this->get('/healthcheck/status');
        $this->assertResponseOk();
        $this->assertResponseContains('OK');
    }

    public function testHealthcheckStatusJsonOk(): void
    {
        $this->getJson('/healthcheck/status.json');
        $this->assertResponseSuccess();
        $this->assertSame('OK', $this->_responseJson->header->message);
        $this->assertSame('OK', $this->_responseJson->body);
    }

    public function testHealthcheckStatusHeadOk(): void
    {
        $this->head('/healthcheck/status.json');

        $this->assertResponseSuccess();
        $body = json_decode($this->_getBodyAsString(), true);
        $this->assertSame('OK', $body['header']['message']);
        $this->assertSame('OK', $body['body']);
    }
}
