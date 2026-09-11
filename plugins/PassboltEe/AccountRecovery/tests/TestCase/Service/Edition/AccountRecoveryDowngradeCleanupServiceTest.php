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
 * @since         5.13.0
 */

namespace Passbolt\AccountRecovery\Test\TestCase\Service\Edition;

use App\Model\Entity\AuthenticationToken;
use App\Test\Factory\AuthenticationTokenFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\AccountRecovery\Service\Edition\AccountRecoveryDowngradeCleanupService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryResponseFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;

/**
 * @covers \Passbolt\AccountRecovery\Service\Edition\AccountRecoveryDowngradeCleanupService
 */
class AccountRecoveryDowngradeCleanupServiceTest extends TestCase
{
    use TruncateDirtyTables;

    public function testAccountRecoveryDowngradeCleanupService_Cleanup_TruncatesAllPluginTables(): void
    {
        AccountRecoveryOrganizationPolicyFactory::make()->persist();
        AccountRecoveryOrganizationPublicKeyFactory::make()->persist();
        AccountRecoveryUserSettingFactory::make()->persist();
        AccountRecoveryPrivateKeyFactory::make()->persist();
        AccountRecoveryPrivateKeyPasswordFactory::make()->persist();
        AccountRecoveryRequestFactory::make()->persist();
        AccountRecoveryResponseFactory::make()->persist();

        (new AccountRecoveryDowngradeCleanupService())->cleanup();

        $this->assertSame(0, AccountRecoveryOrganizationPolicyFactory::count());
        $this->assertSame(0, AccountRecoveryOrganizationPublicKeyFactory::count());
        $this->assertSame(0, AccountRecoveryUserSettingFactory::count());
        $this->assertSame(0, AccountRecoveryPrivateKeyFactory::count());
        $this->assertSame(0, AccountRecoveryPrivateKeyPasswordFactory::count());
        $this->assertSame(0, AccountRecoveryRequestFactory::count());
        $this->assertSame(0, AccountRecoveryResponseFactory::count());
    }

    public function testAccountRecoveryDowngradeCleanupService_Cleanup_DeletesRecoverTokensOnly(): void
    {
        AuthenticationTokenFactory::make()->type(AuthenticationToken::TYPE_RECOVER)->persist();
        AuthenticationTokenFactory::make()->type(AuthenticationToken::TYPE_RECOVER)->persist();
        AuthenticationTokenFactory::make()->type(AuthenticationToken::TYPE_LOGIN)->persist();
        AuthenticationTokenFactory::make()->type(AuthenticationToken::TYPE_REFRESH_TOKEN)->persist();

        (new AccountRecoveryDowngradeCleanupService())->cleanup();

        $this->assertSame(2, AuthenticationTokenFactory::count());
        $remainingTypes = AuthenticationTokenFactory::find()
            ->all()
            ->extract('type')
            ->toList();
        $this->assertEqualsCanonicalizing(
            [AuthenticationToken::TYPE_LOGIN, AuthenticationToken::TYPE_REFRESH_TOKEN],
            $remainingTypes,
        );
    }

    public function testAccountRecoveryDowngradeCleanupService_Cleanup_IsIdempotentWhenNoDataExists(): void
    {
        (new AccountRecoveryDowngradeCleanupService())->cleanup();

        $this->assertSame(0, AccountRecoveryOrganizationPolicyFactory::count());
        $this->assertSame(0, AuthenticationTokenFactory::count());
    }
}
