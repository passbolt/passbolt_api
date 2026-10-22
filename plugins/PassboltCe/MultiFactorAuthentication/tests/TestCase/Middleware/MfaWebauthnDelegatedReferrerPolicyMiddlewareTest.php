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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequestFactory;
use Cake\TestSuite\TestCase;
use Passbolt\MultiFactorAuthentication\Middleware\MfaWebauthnDelegatedReferrerPolicyMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MfaWebauthnDelegatedReferrerPolicyMiddlewareTest extends TestCase
{
    private MfaWebauthnDelegatedReferrerPolicyMiddleware $middleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->middleware = new MfaWebauthnDelegatedReferrerPolicyMiddleware();
    }

    public function tearDown(): void
    {
        unset($this->middleware);
        parent::tearDown();
    }

    /**
     * Returns a handler whose response already carries the application-wide policy, which this middleware replaces.
     *
     * @return \Psr\Http\Server\RequestHandlerInterface
     */
    private function handlerWithSameOriginPolicy(): RequestHandlerInterface
    {
        return new class implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return (new Response())->withHeader('Referrer-Policy', 'same-origin');
            }
        };
    }

    public function testMfaWebauthnDelegatedReferrerPolicyMiddleware_Success_SetsNoReferrerOnThePage(): void
    {
        $request = ServerRequestFactory::fromGlobals(['REQUEST_URI' => '/mfa/verify/webauthn/delegated']);

        $response = $this->middleware->process($request, $this->handlerWithSameOriginPolicy());

        $this->assertSame('no-referrer', $response->getHeaderLine('Referrer-Policy'));
    }

    public function testMfaWebauthnDelegatedReferrerPolicyMiddleware_Success_SetsNoReferrerOnTheHtmlExtension(): void
    {
        $request = ServerRequestFactory::fromGlobals(['REQUEST_URI' => '/mfa/verify/webauthn/delegated.html']);

        $response = $this->middleware->process($request, $this->handlerWithSameOriginPolicy());

        $this->assertSame('no-referrer', $response->getHeaderLine('Referrer-Policy'));
    }

    public function testMfaWebauthnDelegatedReferrerPolicyMiddleware_Success_LeavesOtherRoutesAlone(): void
    {
        $request = ServerRequestFactory::fromGlobals(['REQUEST_URI' => '/mfa/verify/webauthn/delegated/begin.json']);

        $response = $this->middleware->process($request, $this->handlerWithSameOriginPolicy());

        $this->assertSame('same-origin', $response->getHeaderLine('Referrer-Policy'));
    }
}
