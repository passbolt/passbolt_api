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
 * @since         5.8.0
 */

namespace App\Test\TestCase\Middleware;

use App\Middleware\SetUserIdentityInRequestMiddleware;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Http\TestRequestHandler;
use Authentication\Identity;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Middleware\UuidParserMiddleware
 */
class SetUserIdentityInRequestMiddlewareTest extends TestCase
{
    public function testSetUserIdentityInRequestMiddleware_User_Not_Logged_In()
    {
        $request = (new ServerRequest());

        $middleware = new SetUserIdentityInRequestMiddleware();
        $middleware->process($request, new TestRequestHandler());

        $this->assertNull($request->getAttribute('identity'));
    }

    public function testSetUserIdentityInRequestMiddleware_User_Logged_In_With_Session()
    {
        [$user] = UserFactory::make(10)->user()->persist();
        $request = (new ServerRequest())
            ->withAttribute('identity', new Identity(['user' => ['id' => $user->id]]));

        $middleware = new SetUserIdentityInRequestMiddleware();
        $handler = new TestRequestHandler();
        $middleware->process($request, $handler);

        $result = UserFactory::find('authIdentifier')->where(['Users.id' => $user->id])->firstOrFail();
        $this->assertEquals($result, $handler->getRequest()->getAttribute('identity'));
    }

    public function testSetUserIdentityInRequestMiddleware_User_Disabled_Logged_In_With_Session()
    {
        $user = UserFactory::make()->user()->disabled()->persist();
        $request = (new ServerRequest())
            ->withAttribute('identity', new Identity(['user' => ['id' => $user->id]]));

        $middleware = new SetUserIdentityInRequestMiddleware();
        $handler = new TestRequestHandler();
        $middleware->process($request, $handler);
        $this->assertNull($handler->getRequest()->getAttribute('identity'));
    }

    public function testSetUserIdentityInRequestMiddleware_User_Logged_In_With_Jwt()
    {
        $user = UserFactory::make()->user()->getEntity();
        $request = (new ServerRequest())
            ->withAttribute('identity', $user);

        $middleware = new SetUserIdentityInRequestMiddleware();
        $middleware->process($request, new TestRequestHandler());

        $this->assertSame($user, $request->getAttribute('identity'));
    }
}
