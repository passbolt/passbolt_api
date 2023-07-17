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

namespace Passbolt\AccountRecovery\Test\TestCase\Model\Table;

use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Test\Lib\Utility\CleanupTrait;
use Cake\ORM\TableRegistry;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

/**
 * @covers \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable
 */
class AccountRecoveryPrivateKeysTableTest extends AccountRecoveryTestCase
{
    use CleanupTrait;
    use FormatValidationTrait;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable
     */
    protected $AccountRecoveryPrivateKeys;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->AccountRecoveryPrivateKeys = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryPrivateKeys');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->AccountRecoveryPrivateKeys);
        parent::tearDown();
    }

    /**
     * Check id field validation rules
     */
    public function testAccountRecoveryPrivateKeysTable_ValidationId()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check user id field validation rules
     */
    public function testAccountRecoveryPrivateKeysTable_ValidationUserId()
    {
        $this->markTestIncomplete();
    }

    /**
     * Check data field validation rules
     */
    public function testAccountRecoveryPrivateKeysTable_ValidationData()
    {
        $this->markTestIncomplete();
    }

    public function testAccountRecoveryPrivateKeysTable_CleanupSecretsSoftDeletedUsersSuccess()
    {
        $user = UserFactory::make()->user()->deleted()->persist();
        AccountRecoveryPrivateKeyFactory::make()
            ->setField('user_id', $user->id)
            ->persist();

        $this->assertEquals(1, UserFactory::count());
        $this->assertEquals(1, AccountRecoveryPrivateKeyFactory::count());
        $this->runCleanupChecks('Passbolt/AccountRecovery.AccountRecoveryPrivateKeys', 'cleanupSoftDeletedUsers', 0);
    }

    public function testAccountRecoveryPrivateKeysTable_CleanupSecretsHardDeletedUsersSuccess()
    {
        AccountRecoveryPrivateKeyFactory::make()->persist();

        $this->assertEquals(0, UserFactory::count());
        $this->assertEquals(1, AccountRecoveryPrivateKeyFactory::count());
        $this->runCleanupChecks('Passbolt/AccountRecovery.AccountRecoveryPrivateKeys', 'cleanupHardDeletedUsers', 0);
    }
}
