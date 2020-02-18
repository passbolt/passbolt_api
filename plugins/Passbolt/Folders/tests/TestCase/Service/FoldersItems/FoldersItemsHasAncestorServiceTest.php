<?php

namespace Passbolt\Folders\Test\TestCase\Service;

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Passbolt\Folders\Service\FoldersItems\FoldersItemsHasAncestorService;
use Passbolt\Folders\Test\Fixture\FoldersFixture;
use Passbolt\Folders\Test\Fixture\FoldersRelationsFixture;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\FoldersItems\FoldersItemsHasAncestorService Test Case
 *
 * @covers \Passbolt\Folders\Service\FoldersItems\FoldersItemsHasAncestorService
 */
class FoldersItemsHasAncestorServiceTest extends FoldersTestCase
{
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use IntegrationTestTrait;
    use PermissionsModelTrait;

    public $fixtures = [
        FoldersFixture::class,
        FoldersRelationsFixture::class,
        GpgkeysFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        UsersFixture::class,
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Configure::write('passbolt.plugins.folders', ['enabled' => true]);
        $this->service = new FoldersItemsHasAncestorService();
    }

    public function testFolderHasItsParentHasAncestor()
    {
        list($folderA, $folderB, $folderC) = $this->insertFixtureCase1();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        $hasAncestor = $this->service->hasAncestor($folderB->id, $folderA->id, $uac->userId());
        $this->assertTrue($hasAncestor);

        $hasAncestor = $this->service->hasAncestor($folderC->id, $folderB->id, $uac->userId());
        $this->assertTrue($hasAncestor);

        $hasAncestor = $this->service->hasAncestor($folderC->id, $folderA->id, $uac->userId());
        $this->assertTrue($hasAncestor);

        $hasAncestor = $this->service->hasAncestor($folderA->id, $folderA->id, $uac->userId());
        $this->assertTrue($hasAncestor);
    }

    private function insertFixtureCase1()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // Ada has access to folder C as a OWNER
        // A (Ada:O)
        // |
        // B (Ada:O)
        // |
        // C (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);
        $folderC = $this->addFolderFor(['name' => 'C', 'folder_parent_id' => $folderB->id], [$userId => Permission::OWNER]);

        return [$folderA, $folderB, $folderC];
    }

    public function testFolderDoesNotHaveChildrenHasAncestor()
    {
        list($folderA, $folderB) = $this->insertFixtureCase2();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        $hasAncestor = $this->service->hasAncestor($folderA->id, $folderB->id, $uac->userId());
        $this->assertFalse($hasAncestor);
    }

    private function insertFixtureCase2()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // A (Ada:O)
        // |
        // B (Ada:O)
        $userId = UuidFactory::uuid('user.id.ada');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userId => Permission::OWNER]);

        return [$folderA, $folderB];
    }

    public function testFolderAncestorOfOtherUsersAreNotMyFolderAncestor()
    {
        list($folderA, $folderB) = $this->insertFixtureCase3();

        $userId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userId);

        $hasAncestor = $this->service->hasAncestor($folderB->id, $folderA->id, $uac->userId());
        $this->assertFalse($hasAncestor);
    }

    private function insertFixtureCase3()
    {
        // Ada has access to folder A as a OWNER
        // Ada has access to folder B as a OWNER
        // A (Betty:O)
        // |
        // B (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $folderA = $this->addFolderFor(['name' => 'A'], [$userBId => Permission::OWNER]);
        $folderB = $this->addFolderFor(['name' => 'B', 'folder_parent_id' => $folderA->id], [$userBId => Permission::OWNER]);
        $this->addFolderRelation(['foreign_model' => 'Folder', 'foreign_id' => $folderB->id, 'user_id' => $userAId]);
        $this->addPermission('Folder', $folderB->id, 'User', $userAId, Permission::OWNER);

        return [$folderA, $folderB];
    }
}
