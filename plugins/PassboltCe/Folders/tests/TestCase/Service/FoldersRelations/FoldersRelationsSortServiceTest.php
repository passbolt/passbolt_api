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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\TestCase\Service\FoldersRelations;

use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsSortService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;

/**
 * @covers \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsSortService
 */
class FoldersRelationsSortServiceTest extends FoldersTestCase
{
    public $fixtures = [];

    /**
     * @var FoldersRelationsSortService
     */
    private $service;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new FoldersRelationsSortService();
    }

    /*
     * Sort by priority "In Operator Tree"
     */

    /*
     * Ensure the "in operator tree" has a better priority in a simple scenario:
     * A (Ada:O)
     * B (Ada:O)
     * C (Other:O)
     * With Ada as operator the sort should return A, B, C or B, A, C
     */

    public function testSortByPriorityInOperatorTree()
    {
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);
        $folderRelationA = FoldersRelationFactory::make()->user($userA)->persist();
        $folderRelationB = FoldersRelationFactory::make()->user($userA)->persist();
        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationB, $folderRelationC, $folderRelationA];

        $this->service->sort($foldersRelations, $uac);
        $this->assertContains($foldersRelations[0]->id, [$folderRelationA->id, $folderRelationB->id]);
        $this->assertContains($foldersRelations[1]->id, [$folderRelationA->id, $folderRelationB->id]);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Ensure the "in operator tree" has a better priority combined with most used rule.
     * A (Ada:O, Betty:O)
     * B (Ada:O)
     * C (Other:O)
     * With Ada as operator the sort should return A, B, C
     */

    public function testSortByPriorityInOperatorTree_CombinedWithMostUsed()
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);
        $folderA = FolderFactory::make()->persist();
        $folderB = FolderFactory::make()->persist();

        $folderRelationAForAda = FoldersRelationFactory::make()->user($userA)
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();
        $folderRelationBForAda = FoldersRelationFactory::make()->user($userB)
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();
        $folderRelationB = FoldersRelationFactory::make()->user($userA)->persist();
        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationB, $folderRelationC, $folderRelationAForAda];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($foldersRelations[0]->id, $folderRelationAForAda->id);
        $this->assertEquals($foldersRelations[1]->id, $folderRelationB->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Ensure the "in operator tree" has a better priority combined with the "In User Tree" priority
     * A (Ada:O, Betty:O)
     * B (Ada:O, Other:O)
     * C (Other:O)
     * With Ada as operator, and Betty as target user tree, the sort should return A, B, C
     */

    public function testSortByPriorityInOperatorTree_CombinedWithUserTree()
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        $folderA = FolderFactory::make()->persist();
        $folderB = FolderFactory::make()->persist();
        $folderRelationAForAda = FoldersRelationFactory::make()->user($userA)
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();
        $folderRelationBForBetty = FoldersRelationFactory::make()->user($userB)
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();

        $folderC = FolderFactory::make()->persist();
        $folderD = FolderFactory::make()->persist();
        $folderRelationBForAda = FoldersRelationFactory::make()->user($userA)
            ->foreignModelFolder($folderC)->folderParent($folderD)->persist();
        $folderRelationBForOther = FoldersRelationFactory::make()
            ->foreignModelFolder($folderC)->folderParent($folderD)->persist();

        $folderRelationB = FoldersRelationFactory::make()->user($userA)->persist();
        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationB, $folderRelationC, $folderRelationAForAda];

        $this->service->sort($foldersRelations, $uac, $userB->id);
        $this->assertEquals($foldersRelations[0]->id, $folderRelationAForAda->id);
        $this->assertEquals($foldersRelations[1]->id, $folderRelationB->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Ensure the "in operator tree" has a better priority combined with the "Grand Pa" priority
     * A (Ada:O, older than B)
     * B (Ada:O)
     * C (Other:O)
     * With Ada as operator the sort should return A, B, C
     */

    public function testSortByPriorityInOperatorTree_CombinedWithGrandPa()
    {
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);
        $folderRelationA = FoldersRelationFactory::make(['created' => '1970-01-01 00:00:00'])->persist();
        $folderRelationB = FoldersRelationFactory::make(['created' => '1970-01-02 00:00:00'])->persist();
        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationB, $folderRelationC, $folderRelationA];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($foldersRelations[0]->id, $folderRelationA->id);
        $this->assertEquals($foldersRelations[1]->id, $folderRelationB->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Sort by priority "Most Used"
     */

    /*
     * Ensure the "in operator tree" has a better priority in a simple scenario:
     * A (Any:O, Any:O, Any:O)
     * B (Ada:O, Any:O)
     * C (Other:O)
     * With Ada as operator the sort should return A, B, C
     */

    public function testSortByPriorityMostUsed()
    {
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        $folderA = FolderFactory::make()->persist();
        $folderB = FolderFactory::make()->persist();
        [$folderRelationAForOther1] = FoldersRelationFactory::make(3)
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();

        $folderC = FolderFactory::make()->persist();
        $folderD = FolderFactory::make()->persist();
        [$folderRelationBForOther1] = FoldersRelationFactory::make(2)
            ->foreignModelFolder($folderC)->folderParent($folderD)->persist();

        $folderRelationC = FoldersRelationFactory::make()->persist();

        $foldersRelations = [$folderRelationC, $folderRelationBForOther1, $folderRelationAForOther1];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($folderRelationAForOther1->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationBForOther1->id, $foldersRelations[1]->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Ensure the "most used" has a better priority combined with the "User tree" priority
     * A (Betty:O, Any:O)
     * B (Any:O, Any:O)
     * C (Any:O)
     * With Ada as operator the sort should return A, B, C
     */

    public function testSortByPriorityMostUsed_CombinedUserTree()
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        $folderA = FolderFactory::make()->persist();
        $folderB = FolderFactory::make()->persist();
        $folderRelationAForBetty = FoldersRelationFactory::make()
            ->foreignModelFolder($folderA)->folderParent($folderB)->user($userB)->persist();
        $folderRelationAForOther = FoldersRelationFactory::make()
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();

        $folderC = FolderFactory::make()->persist();
        $folderD = FolderFactory::make()->persist();
        [$folderRelationBForOther1] = FoldersRelationFactory::make(2)
            ->foreignModelFolder($folderC)->folderParent($folderD)->persist();

        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationC, $folderRelationBForOther1, $folderRelationAForBetty];

        $this->service->sort($foldersRelations, $uac, $userB->id);
        $this->assertEquals($folderRelationAForBetty->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationBForOther1->id, $foldersRelations[1]->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Ensure the "most used" has a better priority combined with the "Grand Pa" priority
     * A (Any:O, Any:O, older than B)
     * B (Any:O, Any:O)
     * C (Any:O)
     * With Ada as operator the sort should return A, B, C
     */

    public function testSortByPriorityMostUsed_CombinedGrandPa()
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);

        $folderA = FolderFactory::make()->persist();
        $folderB = FolderFactory::make()->persist();
        $folderRelationAForOther1 = FoldersRelationFactory::make(['created' => '1970-01-01 00:00:00'])
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();
        $folderRelationAForOther2 = FoldersRelationFactory::make(['created' => '1970-01-01 00:00:00'])
            ->foreignModelFolder($folderA)->folderParent($folderB)->persist();

        $folderC = FolderFactory::make()->persist();
        $folderD = FolderFactory::make()->persist();
        [$folderRelationBForOther1] = FoldersRelationFactory::make(2)
            ->foreignModelFolder($folderC)->folderParent($folderD)->persist();

        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationC, $folderRelationBForOther1, $folderRelationAForOther1];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($folderRelationAForOther1->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationBForOther1->id, $foldersRelations[1]->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Sort by priority "In user tree"
     */

    /*
     * Ensure the "in operator tree" has a better priority in a simple scenario:
     * A (Betty:O)
     * B (Betty:O)
     * C (Other:O)
     * With Ada as operator the sort should return A, B, C or B, A, C
     */

    public function testSortByPriorityInUserTree()
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);
        $folderRelationA = FoldersRelationFactory::make()->user($userB)->persist();
        $folderRelationB = FoldersRelationFactory::make()->user($userB)->persist();
        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationC, $folderRelationB, $folderRelationA];

        $this->service->sort($foldersRelations, $uac, $userB->id);
        $this->assertContains($foldersRelations[0]->id, [$folderRelationA->id, $folderRelationB->id]);
        $this->assertContains($foldersRelations[1]->id, [$folderRelationA->id, $folderRelationB->id]);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
      * Ensure the "in operator tree" has a better priority in a simple scenario:
      * A (Betty:O older than B)
      * B (Betty:O)
      * C (Other:O)
      * With Ada as operator the sort should return A, B, C
      */

    public function testSortByPriorityInUserTree_CombinedGrandPa()
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);
        $folderRelationA = FoldersRelationFactory::make(['created' => '1970-01-01 00:00:00'])->user($userB)->persist();
        $folderRelationB = FoldersRelationFactory::make()->user($userB)->persist();
        $folderRelationC = FoldersRelationFactory::make()->persist();
        $foldersRelations = [$folderRelationC, $folderRelationB, $folderRelationA];

        $this->service->sort($foldersRelations, $uac, $userB->id);
        $this->assertEquals($foldersRelations[0]->id, $folderRelationA->id);
        $this->assertEquals($foldersRelations[1]->id, $folderRelationB->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }

    /*
     * Sort by priority "Grand Pa"
     */

    /*
     * Ensure the "Grand Pa" has a better priority in a simple scenario:
     * A (Any:O older than B)
     * B (Any:O older than C)
     * C (Any:O)
     * With Ada as operator the sort should return A, B, C
     */
    public function testSortByPriorityCreatedOldestTree()
    {
        $userA = UserFactory::make()->persist();
        $uac = new UserAccessControl($userA->role->name, $userA->id);
        $folderRelationA = FoldersRelationFactory::make(['created' => '1970-01-01 00:00:00'])->persist();
        $folderRelationB = FoldersRelationFactory::make(['created' => '1970-01-02 00:00:00'])->persist();
        $folderRelationC = FoldersRelationFactory::make(['created' => '1970-01-03 00:00:00'])->persist();
        $foldersRelations = [$folderRelationB, $folderRelationC, $folderRelationA];

        $this->service->sort($foldersRelations, $uac);
        $this->assertEquals($folderRelationA->id, $foldersRelations[0]->id);
        $this->assertEquals($folderRelationB->id, $foldersRelations[1]->id);
        $this->assertEquals($folderRelationC->id, $foldersRelations[2]->id);
    }
}
