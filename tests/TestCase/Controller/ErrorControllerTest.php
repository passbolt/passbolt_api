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
 * @since         4.7.0
 */
namespace App\Test\TestCase\Controller;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;

/**
 * @covers \App\Controller\ErrorController
 */
class ErrorControllerTest extends AppIntegrationTestCase
{
    public function testErrorController_HTML_404(): void
    {
        Configure::write('debug', false);

        $this->get('/a-route-that-is-not-found');

        $resultHtml = $this->_getBodyAsString();
        $this->assertResponseError();
        $this->assertResponseCode(404);
        $this->assertTextContains('<title>Passbolt | Error</title>', $resultHtml);
        $this->assertTextContains('<h2>Not Found</h2>', $resultHtml);
        $this->assertTextContains('The requested address was not found on this server.', $resultHtml);
    }

    public function testErrorController_HTML_400_TitleAndErrorMessagePurified(): void
    {
        $this->get('/users/?sort=1</title></br></br><h1>Defaced</h1>');

        $this->assertResponseError();
        $this->assertResponseCode(400);
        $resultHtml = $this->_getBodyAsString();
        $this->assertTextContains('<title>Passbolt | Error</title>', $resultHtml);
        $expectedFilteredMsg = 'Invalid order. ' . h('"1</title></br></br><h1>Defaced</h1>"') . ' is not in the list of allowed order';
        $this->assertTextContains($expectedFilteredMsg, $resultHtml);
    }

    public function testErrorController_HTML_500(): void
    {
        Configure::write('passbolt.healthcheck.error', true);

        $this->get('/healthcheck/error');

        $this->assertResponseCode(500);
        $resultHtml = $this->_getBodyAsString();
        $this->assertTextContains('<title>Passbolt | Error</title>', $resultHtml);
        $this->assertTextContains('<h2>An Internal Error Has Occurred</h2>', $resultHtml);
    }
}
