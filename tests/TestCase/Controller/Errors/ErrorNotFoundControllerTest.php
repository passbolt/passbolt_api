<?php
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
namespace App\Test\TestCase\Controller\Errors;

use App\Test\Lib\AppIntegrationTestCase;

class ErrorNotFoundControllerTest extends AppIntegrationTestCase
{
    public function testErrorNotSupported()
    {
        if (!file_exists(PLUGINS . 'Passbolt' . DS . 'AccountSettings')) {
            $this->get('/account/settings.json');
            $this->assertResponseError();
        } else {
            $this->markTestSkipped();
        }
    }
}
