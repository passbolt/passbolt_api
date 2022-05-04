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
use Cake\TestSuite\TestCase;
use Passbolt\MultiFactorAuthentication\Event\ClearMfaCookieOnSetupAndRecover;

class ClearMfaCookieOnSetupAndRecoverTest extends TestCase
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
}
