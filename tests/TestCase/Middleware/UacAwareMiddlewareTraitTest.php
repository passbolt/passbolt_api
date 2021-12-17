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

namespace App\Test\TestCase\Middleware;

use App\Middleware\UacAwareMiddlewareTrait;
use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;

/**
 * Test for SessionPreventExtensionMiddleware
 */
class UacAwareMiddlewareTraitTest extends TestCase
{
    use UacAwareMiddlewareTrait;

    public function testUacAwareMiddlewareTraitTest_Should_Return_Complete_UAC()
    {
        $user = UserFactory::make(['id' => UuidFactory::uuid()])->user()->getEntity();
        $request = (new ServerRequest())->withAttribute('identity', $user);

        $uac = $this->getUacInRequest($request);

        $this->assertSame($user->id, $uac->getId());
        $this->assertSame(Role::USER, $uac->roleName());
        $this->assertSame($user->username, $uac->getUsername());
    }
}
