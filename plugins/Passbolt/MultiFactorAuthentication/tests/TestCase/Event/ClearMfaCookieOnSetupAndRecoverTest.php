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

use App\Controller\Setup\RecoverAbortController;
use App\Controller\Setup\RecoverCompleteController;
use App\Controller\Setup\SetupCompleteController;
use App\Controller\Users\UsersRecoverController;
use App\Controller\Users\UsersRegisterController;
use App\Test\Factory\RoleFactory;
use Passbolt\MultiFactorAuthentication\Event\ClearMfaCookieOnSetupAndRecover;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaVerifiedCookie;

class ClearMfaCookieOnSetupAndRecoverTest extends MfaIntegrationTestCase
{
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

    /**
     * On a GET, the expired MFA cookie is not set
     */
    public function testClearMfaCookieOnSetupAndRecover_UsersRegisterGetSuccess()
    {
        $this->get('/users/register');
        $this->assertResponseOk();
        $this->assertCookieNotSet(MfaVerifiedCookie::MFA_COOKIE_ALIAS);
    }

    /**
     * On a POST, the expired MFA is set
     */
    public function testClearMfaCookieOnSetupAndRecover_UsersRegisterPostSuccess()
    {
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
