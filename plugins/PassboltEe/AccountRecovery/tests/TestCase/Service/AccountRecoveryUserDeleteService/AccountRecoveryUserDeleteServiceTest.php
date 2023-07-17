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

namespace Passbolt\AccountRecovery\Test\TestCase\Service\AccountRecoveryUserDeleteService;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use Passbolt\AccountRecovery\AccountRecoveryPlugin;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryRequest;
use Passbolt\AccountRecovery\Service\AccountRecoveryUserDelete\AccountRecoveryUserDeleteService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;
use Passbolt\AccountRecovery\Test\Scenario\Request\ResponseCreateScenario;

class AccountRecoveryUserDeleteServiceTest extends AccountRecoveryTestCase
{
    use ScenarioAwareTrait;

    public function setUp(): void
    {
        parent::setUp();
        (new AccountRecoveryPlugin())->addAssociationsToUsersTable();
    }

    public function testAccountRecoveryUserDeleteService_Error_InvalidId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new AccountRecoveryUserDeleteService())->deleteInfo('nope');
    }

    public function testAccountRecoveryUserDeleteService_Success(): void
    {
        [$request, $policy, $user] = $this->loadFixtureScenario(ResponseCreateScenario::class);
        (new AccountRecoveryUserDeleteService())->deleteInfo($user->id);

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

    public function testAccountRecoveryUserDeleteService_Success2Users(): void
    {
        [$request, $policy, $user] = $this->loadFixtureScenario(ResponseCreateScenario::class);

        $user2 = UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->withValidOpenPGPKey())
            ->withAvatar()
            ->persist();

        AccountRecoveryUserSettingFactory::make()
            ->setField('status', 'approved')
            ->setField('user_id', $user2->id)
            ->persist();

        AccountRecoveryPrivateKeyFactory::make()
            ->with('AccountRecoveryPrivateKeyPasswords', AccountRecoveryPrivateKeyPasswordFactory::make()
                ->setField('recipient_fingerprint', '67BFFCB7B74AF4C85E81AB26508850525CD78BBB')
                ->setField('recipient_foreign_model', AccountRecoveryPrivateKeyPassword::RECIPIENT_FOREIGN_MODEL_ORGANIZATION_KEY))
            ->setField('user_id', $user2->id)
            ->persist();

        $token = AuthenticationTokenFactory::make()
            ->type(AuthenticationToken::TYPE_RECOVER)
            ->setField('user_id', $user2->id)
            ->active()
            ->persist();

        $request = AccountRecoveryRequestFactory::make()
            ->rsa4096Key_2()
            ->setField('status', AccountRecoveryRequest::ACCOUNT_RECOVERY_REQUEST_PENDING)
            ->setField('user_id', $user2->id)
            ->setField('authentication_token_id', $token->id)
            ->persist();

        (new AccountRecoveryUserDeleteService())->deleteInfo($user->id);

        // Not all private key, passwords and settings are deleted
        $this->assertEquals(1, AccountRecoveryPrivateKeyFactory::count());
        $this->assertEquals(1, AccountRecoveryPrivateKeyPasswordFactory::count());
        $this->assertEquals(1, AccountRecoveryUserSettingFactory::count());
    }
}
