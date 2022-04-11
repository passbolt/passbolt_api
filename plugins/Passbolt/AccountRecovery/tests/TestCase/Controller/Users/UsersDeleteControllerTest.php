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

namespace Passbolt\AccountRecovery\Test\TestCase\Controller\Users;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\RoleFactory;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryIntegrationTestCase;
use Passbolt\AccountRecovery\Test\Scenario\Request\ResponseCreateScenario;

class UsersDeleteControllerTest extends AccountRecoveryIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
    }

    public function testUsersDeleteController_DeleteAccountRecoveryInfo_Success(): void
    {
        [$request, $policy, $user] = $this->loadFixtureScenario(ResponseCreateScenario::class);

        $this->logInAsAdmin();
        $this->deleteJson('/users/' . $user->id . '.json');
        $this->assertSuccess();

        // Private key, passwords and settings are deleted
        $this->assertEquals(0, AccountRecoveryPrivateKeyFactory::count());
        $this->assertEquals(0, AccountRecoveryPrivateKeyPasswordFactory::count());
        $this->assertEquals(0, AccountRecoveryUserSettingFactory::count());

        // Pending requests are sent to disabled
        /** @var AccountRecoveryRequest $request */
        $request = AccountRecoveryRequestFactory::find()->firstOrFail();
        $this->assertTextEquals(AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_REJECTED, $request->status);

        // Authentication tokens are set to inactive
        /** @var AuthenticationToken $token */
        $token = AuthenticationTokenFactory::find()->firstOrFail();
        $this->assertTextEquals(AuthenticationToken::TYPE_RECOVER, $token->type);
        $this->assertEquals(false, $token->active);
    }
}
