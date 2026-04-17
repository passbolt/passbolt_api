<?php
declare(strict_types=1);

namespace Passbolt\AccountRecovery\Test\TestCase\Model\Table;

use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\CleanupTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;

/**
 * Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable Test Case
 */
class AccountRecoveryUserSettingsTableTest extends TestCase
{
    use CleanupTrait;
    use TruncateDirtyTables;

    /**
     * Test subject
     *
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable
     */
    protected $AccountRecoveryUserSettings;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->AccountRecoveryUserSettings = $this->getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryUserSettings');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->AccountRecoveryUserSettings);

        parent::tearDown();
    }

    /**
     * Test validationDefault method when status is not set
     *
     * @return void
     */
    public function testAccountRecoveryUserSettingsTable_Validation_No_Status(): void
    {
        $data = AccountRecoveryUserSettingFactory::make()->setField('status', null)->getEntity()->toArray();
        $entity = $this->AccountRecoveryUserSettings->newEntity($data);
        $this->assertTrue($entity->hasErrors());
        $this->assertSame(
            ['_empty' => 'The status should not be empty'],
            $entity->getError('status')
        );
    }

    /**
     * Test validationDefault method on a non-supported status
     *
     * @return void
     */
    public function testAccountRecoveryUserSettingsTable_Validation_Wrong_Status(): void
    {
        $data = AccountRecoveryUserSettingFactory::make()->setField('status', 'Foo')->getEntity()->toArray();
        $entity = $this->AccountRecoveryUserSettings->newEntity($data);
        $this->assertTrue($entity->hasErrors());
        $this->assertSame(
            ['inList' => 'The status should be one of the following: rejected, approved.'],
            $entity->getError('status')
        );
    }

    /**
     * Test validationDefault method on a supported status
     *
     * @return void
     */
    public function testAccountRecoveryUserSettingsTable_Validation_ValidStatus(): void
    {
        $data = AccountRecoveryUserSettingFactory::make()->getEntity()->toArray();
        $entity = $this->AccountRecoveryUserSettings->newEntity($data);
        $this->assertFalse($entity->hasErrors());
    }

    /**
     * Test buildRules on a user_id field that has no entry in the UsersTable
     */
    public function testAccountRecoveryUserSettingsTable_BuildRules_User_Id_Does_Not_Exist_In_UsersTable(): void
    {
        $data = AccountRecoveryUserSettingFactory::make()->getEntity()->toArray();
        $entity = $this->AccountRecoveryUserSettings->newEntity($data, [
            'accessibleFields' => [
                'status' => true,
                'user_id' => true,
                'created_by' => true,
                'modified_by' => true,
                'created' => true,
                'modified' => true,
            ],
        ]);
        $this->AccountRecoveryUserSettings->save($entity);
        $this->assertSame(
            ['_existsIn' => 'This value does not exist'],
            $entity->getError('user_id')
        );
        $this->assertSame(
            ['_existsIn' => 'This value does not exist'],
            $entity->getError('created_by')
        );
        $this->assertSame(
            ['_existsIn' => 'This value does not exist'],
            $entity->getError('modified_by')
        );
        $this->assertSame(0, AccountRecoveryUserSettingFactory::count());
    }

    /**
     * Test buildRules valid
     */
    public function testAccountRecoveryUserSettingsTable_BuildRules_User_Id_Exists_Success(): void
    {
        $user = UserFactory::make()->persist();
        $data = AccountRecoveryUserSettingFactory::make()
            ->patchData([
                'user_id' => $user->id,
                'created_by' => $user->id,
                'modified_by' => $user->id,
            ])
            ->getEntity()->toArray();

        $entity = $this->AccountRecoveryUserSettings->newEntity($data, [
            'accessibleFields' => [
                'status' => true,
                'user_id' => true,
                'created_by' => true,
                'modified_by' => true,
                'created' => true,
                'modified' => true,
            ],
        ]);
        $this->AccountRecoveryUserSettings->save($entity);
        $this->assertFalse($entity->hasErrors());
        $this->assertSame(1, AccountRecoveryUserSettingFactory::count());
    }

    /**
     * Test buildRules on a user with already a setting in the DB
     */
    public function testAccountRecoveryUserSettingsTable_BuildRules_User_Id_Duplicate(): void
    {
        $user = AccountRecoveryUserSettingFactory::make()->withUser()->persist()->user;
        $data = AccountRecoveryUserSettingFactory::make()
            ->patchData([
                'user_id' => $user->id,
                'created_by' => $user->id,
                'modified_by' => $user->id,
            ])
            ->getEntity()->toArray();

        $entity = $this->AccountRecoveryUserSettings->newEntity($data, [
            'accessibleFields' => [
                'status' => true,
                'user_id' => true,
                'created_by' => true,
                'modified_by' => true,
                'created' => true,
                'modified' => true,
            ],
        ]);
        $this->AccountRecoveryUserSettings->save($entity);
        $this->assertSame(
            ['_isUnique' => 'This user already has an account recovery setting'],
            $entity->getError('user_id')
        );
        $this->assertSame(1, AccountRecoveryUserSettingFactory::count());
    }

    public function testAccountRecoveryUserSettingsTable_CleanupSecretsSoftDeletedUsersSuccess()
    {
        $user = UserFactory::make()->user()->deleted()->persist();
        AccountRecoveryUserSettingFactory::make()
            ->setField('user_id', $user->id)
            ->persist();

        $this->assertEquals(1, UserFactory::count());
        $this->assertEquals(1, AccountRecoveryUserSettingFactory::count());
        $this->runCleanupChecks('Passbolt/AccountRecovery.AccountRecoveryUserSettings', 'cleanupSoftDeletedUsers', 0);
    }

    public function testAccountRecoveryUserSettingsTable_CleanupSecretsHardDeletedUsersSuccess()
    {
        AccountRecoveryUserSettingFactory::make()->persist();

        $this->assertEquals(0, UserFactory::count());
        $this->assertEquals(1, AccountRecoveryUserSettingFactory::count());
        $this->runCleanupChecks('Passbolt/AccountRecovery.AccountRecoveryUserSettings', 'cleanupHardDeletedUsers', 0);
    }
}
