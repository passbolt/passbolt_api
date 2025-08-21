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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Test\TestCase\Controller\Middleware;

use Passbolt\Scim\Test\Utility\ScimApiIntegrationTestCase;

class BodyParserMiddlewareTest extends ScimApiIntegrationTestCase
{
    public function testBodyParserMiddleware_RequestAsScimPlusJson_Scim_Plugin_Enabled()
    {
        $this->requestAsScimPlusJson();
        $this->configScimAuth();
        $body = json_encode($this->getUserPostData(self::USER_1_SCIM_NAME));
        $this->post($this->getScimEndpoint('Users'), $body);
        $this->assertResponseSuccess();
    }
}
