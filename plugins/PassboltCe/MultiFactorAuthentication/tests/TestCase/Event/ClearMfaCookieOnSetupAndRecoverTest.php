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
 * @since         3.6.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Event;

use App\Controller\ErrorController;
use App\Controller\Setup\RecoverAbortController;
use App\Controller\Setup\RecoverCompleteController;
use App\Controller\Setup\SetupCompleteController;
use App\Controller\Users\UsersRecoverController;
use App\Controller\Users\UsersRegisterController;
use App\Test\Factory\RoleFactory;
use Cake\Event\Event;
use Cake\Http\ServerRequest;
use Passbolt\MultiFactorAuthentication\Event\ClearMfaCookieOnSetupAndRecover;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;
use Passbolt\SelfRegistration\SelfRegistrationPlugin;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

class ClearMfaCookieOnSetupAndRecoverTest extends MfaIntegrationTestCase
{
    use SelfRegistrationTestTrait;

    public function testClearMfaCookieOnSetupAndRecover_getListOfControllers()
    {
        $controllersConcerned = (new ClearMfaCookieOnSetupAndRecover())->getListOfControllers();
        $this->assertSame([
            UsersRegisterController::class,
            UsersRecoverController::class,
            SetupCompleteController::class,
            RecoverCompleteController::class,
            RecoverAbortController::class,
        ], $controllersConcerned);
    }

    public function testClearMfaCookieOnSetupAndRecover_clearMfaCookieInResponse_Post_In_List_Should_Set_Expired_Cookie()
    {
        $request = (new ServerRequest([
            'environment' => ['REQUEST_METHOD' => 'POST'],
        ]));
        $controller = new UsersRegisterController($request);
        $event = new Event('Foo', $controller);

        (new ClearMfaCookieOnSetupAndRecover())->clearMfaCookieInResponse($event);

        $cookie = $controller->getResponse()->getCookieCollection()->get(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertTrue($cookie->isExpired());
    }

    public function testClearMfaCookieOnSetupAndRecover_clearMfaCookieInResponse_Post_In_List_No_User_Component_Should_Not_Set_Expired_Cookie()
    {
        $request = (new ServerRequest([
            'environment' => ['REQUEST_METHOD' => 'POST'],
        ]));
        $controller = new UsersRegisterController($request);
        $event = new Event('Foo', $controller);
        unset($controller->User);

        (new ClearMfaCookieOnSetupAndRecover())->clearMfaCookieInResponse($event);

        $hasCookie = $controller->getResponse()->getCookieCollection()->has(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertFalse($hasCookie);
    }

    public function testClearMfaCookieOnSetupAndRecover_clearMfaCookieInResponse_GET_In_List_Should_Not_Set_Expired_Cookie()
    {
        $request = (new ServerRequest([
            'environment' => ['REQUEST_METHOD' => 'GET'],
        ]));
        $controller = new UsersRegisterController($request);
        $event = new Event('Foo', $controller);

        (new ClearMfaCookieOnSetupAndRecover())->clearMfaCookieInResponse($event);

        $hasCookie = $controller->getResponse()->getCookieCollection()->has(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertFalse($hasCookie);
    }

    public function testClearMfaCookieOnSetupAndRecover_clearMfaCookieInResponse_Post_Not_In_List_No_Cookie()
    {
        $request = (new ServerRequest([
            'environment' => ['REQUEST_METHOD' => 'POST'],
        ]));
        $controller = new ErrorController($request);
        $event = new Event('Foo', $controller);

        (new ClearMfaCookieOnSetupAndRecover())->clearMfaCookieInResponse($event);

        $hasCookie = $controller->getResponse()->getCookieCollection()->has(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
        $this->assertFalse($hasCookie);
    }

    /**
     * On a GET, the expired MFA cookie is not set
     */
    public function testClearMfaCookieOnSetupAndRecover_UsersRegisterGetSuccess()
    {
        $this->enableFeaturePlugin(SelfRegistrationPlugin::class);
        $this->setSelfRegistrationSettingsData();
        $this->get('/users/register');
        $this->assertResponseOk();
        $this->assertCookieNotSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }

    /**
     * On a POST, the expired MFA is set
     */
    public function testClearMfaCookieOnSetupAndRecover_UsersRegisterPostSuccess()
    {
        $this->enableFeaturePlugin(SelfRegistrationPlugin::class);
        $this->setSelfRegistrationSettingsData();
        RoleFactory::make()->user()->persist();
        $data = [
            'username' => 'ping.fu@passbolt.com',
            'profile' => [
                'first_name' => '傅',
                'last_name' => '苹',
            ],
        ];
        $this->postJson('/users/register.json', $data);
        $this->assertResponseSuccess();
        $this->assertCookieExpired(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }
}
