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
 * @since         3.3.0
 */
namespace Passbolt\JwtAuthentication\Test\TestCase\Middleware;

use Passbolt\JwtAuthentication\Test\Utility\JwtAuthenticationIntegrationTestCase;

class SkipGpgAuthHeadersMiddlewareTest extends JwtAuthenticationIntegrationTestCase
{
    public function testSkipGpgAuthHeaders_DoNotSkip()
    {
        $this->logInAsUser();
        $this->getJson('/auth/is-authenticated.json');
        $this->assertSuccess();

        $hasHeader = $this->_response->hasHeader('X-GPGAuth-Version');
        $this->assertTrue($hasHeader);
    }

    public function testSkipGpgAuthHeaders_DoSkip()
    {
        $this->createJwtTokenAndSetInHeader();
        $this->getJson('/auth/is-authenticated.json');
        $this->assertSuccess();

        $hasHeader = $this->_response->hasHeader('X-GPGAuth-Version');
        $this->assertFalse($hasHeader);
    }
}
