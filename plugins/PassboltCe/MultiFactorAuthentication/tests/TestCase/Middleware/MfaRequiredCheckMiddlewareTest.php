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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Middleware;

use App\Authenticator\SessionIdentificationService;
use App\Authenticator\SessionIdentificationServiceInterface;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Core\Container;
use Cake\Http\ServerRequest;
use Cake\Http\Session;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use Passbolt\MultiFactorAuthentication\Middleware\MfaRequiredCheckMiddleware;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaRequiredCheckMiddlewareTest extends AppTestCase
{
    use ScenarioAwareTrait;
    use UserAccessControlTrait;

    public $middleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->middleware = new MfaRequiredCheckMiddleware();
    }

    public function tearDown(): void
    {
        unset($this->middleware);
        MfaSettings::clear();
        parent::tearDown();
    }

    public function dataForWhiteListUrl()
    {
        return [
            ['/mfa/verify', false,],
            ['/auth/logout', false,],
            ['/logout', false,],
            ['/auth/login', false,],
            ['/login', false,],
            ['/users', true,],
        ];
    }

    /**
     * @dataProvider dataForWhiteListUrl
     */
    public function testMfaRequiredCheckMiddlewareIsMfaCheckRequired_WhiteListed_Route(string $url, bool $expected)
    {
        $user = UserFactory::make()->user()->persist();
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
        $request = new ServerRequest([
            'url' => $url,
            'session' => new Session(['Auth' => $user]),
        ]);
        $container = new Container();
        $container->add(SessionIdentificationServiceInterface::class, SessionIdentificationService::class);
        $request = $request->withAttribute('identity', $user);
        $request = $request->withAttribute('container', $container);
        $isMfaRequired = $this->middleware->isMfaCheckRequired($request);
        $this->assertSame($expected, $isMfaRequired);
    }
}
