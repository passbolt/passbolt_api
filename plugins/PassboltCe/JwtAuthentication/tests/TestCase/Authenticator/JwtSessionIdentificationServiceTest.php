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
namespace Passbolt\JwtAuthentication\Test\TestCase\Authenticator;

use Authentication\AuthenticationService;
use Authentication\Authenticator\ResultInterface;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Passbolt\JwtAuthentication\Authenticator\JwtSessionIdentificationService;
use Passbolt\JwtAuthentication\Service\Middleware\JwtAuthenticationService;

class JwtSessionIdentificationServiceTest extends TestCase
{
    public function dataProviderForAuthentication()
    {
        return [
            [true], [false],
        ];
    }

    /**
     * @dataProvider dataProviderForAuthentication
     */
    public function testJwtSessionIdentificationService_Authenticated($isAuthenticated)
    {
        $resultStub = $this->getMockBuilder(ResultInterface::class)->getMock();
        $resultStub->expects($this->any())->method('isValid')->willReturn($isAuthenticated);
        $authStub = $this->getMockBuilder(AuthenticationService::class)->getMock();
        $authStub->expects($this->any())->method('getResult')->willReturn($resultStub);

        $accessToken = 'Foo';

        $request = (new ServerRequest())
            ->withAttribute('authentication', $authStub)
            ->withHeader(JwtAuthenticationService::JWT_HEADER, $accessToken);

        $SessionService = new JwtSessionIdentificationService();
        $expected = $isAuthenticated ? $accessToken : null;
        $this->assertSame($expected, $SessionService->getSessionIdentifier($request));
    }

    public function testJwtSessionIdentificationService_No_Authentication_Service()
    {
        $accessToken = 'Foo';

        $request = new ServerRequest();
        $request->withHeader(JwtAuthenticationService::JWT_HEADER, $accessToken);

        $SessionService = new JwtSessionIdentificationService();
        $this->assertNull($SessionService->getSessionIdentifier($request));
    }
}
