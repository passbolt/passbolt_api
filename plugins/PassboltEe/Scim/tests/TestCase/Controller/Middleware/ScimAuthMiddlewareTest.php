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

use App\Test\Factory\UserFactory;
use Passbolt\JwtAuthentication\Test\Utility\JwtAuthTestTrait;
use Passbolt\Scim\Test\Utility\ScimApiIntegrationTestCase;

class ScimAuthMiddlewareTest extends ScimApiIntegrationTestCase
{
    use JwtAuthTestTrait;

    public function testScimAuthMiddleware_JWT_Authenticated_Should_Redirect(): void
    {
        $this->enableFeaturePlugin('JwtAuthentication');
        $user = UserFactory::make()->user()->persist();
        $this->createJwtTokenAndSetInHeader($user->get('id'));
        $this->get($this->getScimEndpoint('Users'));
        $this->assertRedirect();
    }

    public function testScimAuthMiddleware_Session_Authenticated_Should_Throw_An_Error(): void
    {
        $this->logInAsUser();
        $this->get($this->getScimEndpoint('Users'));
        $this->assertResponseCode(403);
        $this->assertResponseContains('Simultaneous SCIM and session authentication is not permitted.');
    }
}
