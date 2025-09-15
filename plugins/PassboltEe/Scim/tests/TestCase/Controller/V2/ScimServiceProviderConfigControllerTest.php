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

namespace Passbolt\Scim\Test\TestCase\Controller\V2;

use Passbolt\Scim\Test\Utility\ScimApiIntegrationTestCase;

/**
 * ScimControllerTest class
 */
class ScimServiceProviderConfigControllerTest extends ScimApiIntegrationTestCase
{
    /**
     * Test case
     */
    public function testScimControllerServiceProviderConfig_Success()
    {
        $this->configScimAuth();
        $this->get($this->getScimEndpoint('ServiceProviderConfig'));
        $this->assertResponseCode(200);
        $this->assertResponseEquals($this->getScimFixtureData(self::FIXTURE_RESPONSE_SERVICE_PROVIDER_CONFIG));
    }
}
