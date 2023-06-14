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
 * @since         4.1.0
 */

namespace Passbolt\Folders\Test\TestCase\Service\FoldersRelations;

use App\Test\Factory\UserFactory;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Utility\FixtureProviderTrait;
use Passbolt\Folders\Service\FoldersRelations\FoldersRelationsHaveAndAreChildrenService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\FoldersRelations\FoldersRelationsHaveOrAreChildrenService Test Case
 *
 * @covers \Passbolt\Folders\Service\FoldersRelations\FoldersRelationsHaveAndAreChildrenService
 */
class FoldersRelationsHaveAndAreChildrenServiceTest extends FoldersTestCase
{
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;

    public $fixtures = [
        UsersFixture::class,
    ];

    /**
     * @var FoldersRelationsHaveAndAreChildrenService
     */
    private FoldersRelationsHaveAndAreChildrenService $service;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new FoldersRelationsHaveAndAreChildrenService();
    }

    /* ************************************************************************
     * Check single folder that have no parent and children
     ************************************************************************ */

    public function testFoldersRelationsHaveAndAreChildrenService_NoChildrenNoParent_Empty()
    {
        $userA = UserFactory::make()->persist();
        // Check the whole graph.
        $this->assertFalse($this->service->haveAndAreChildren([]));
        // Check for a given user.
        $this->assertFalse($this->service->haveAndAreChildren([], $userA->id));
    }

    public function testFoldersRelationsHaveAndAreChildrenService_NoChildrenNoParent_SingleFolderAtRoot()
    {
        [$folderA, $userA] = $this->providerSingleFolderAtRoot();
        // Check the whole graph.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id]));
        // Check for a given user.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id], $userA->id));
    }

    public function providerSingleFolderAtRoot(): array
    {
        $userA = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();

        return [$folderA, $userA];
    }

    public function testFoldersRelationsHaveAndAreChildrenService_NoChildrenNoParent_SingleFolderInMultipleTrees()
    {
        [$folderA, $userA, $userB] = $this->providerSingleFolderInMultipleTrees();
        // Check the whole graph.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id]));
        // Check for the given user A.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id], $userA->id));
        // Check for the given user B.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id], $userB->id));
    }

    public function providerSingleFolderInMultipleTrees(): array
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA, $userB])->persist();

        return [$folderA, $userA, $userB];
    }

    public function testFoldersRelationsHaveAndAreChildrenService_NoChildrenNoParent_FolderHavingSiblings()
    {
        [$folderA, $folderB, $userA] = $this->providerRootFoldersHavingSiblings();
        // Check the whole graph.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id]));
        // Check for the given user A.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id], $userA->id));
        // Check for the given user B.
        $this->assertFalse($this->service->haveAndAreChildren([$folderB->id], $userA->id));
    }

    public function providerRootFoldersHavingSiblings(): array
    {
        $userA = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderB = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();

        return [$folderA, $folderB, $userA];
    }

    public function testFoldersRelationsHaveAndAreChildrenService_NoChildrenNoParent_FolderHavingSiblingsInMultipleTrees()
    {
        [$folderA, $folderB, $userA, $userB] = $this->providerFolderHavingSiblingsInMultipleTrees();
        // Check the whole graph.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id]));
        // Check for the given user A.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id], $userA->id));
        $this->assertFalse($this->service->haveAndAreChildren([$folderB->id], $userA->id));
        // Check for the given user B.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id], $userB->id));
        $this->assertFalse($this->service->haveAndAreChildren([$folderB->id], $userB->id));
    }

    public function providerFolderHavingSiblingsInMultipleTrees(): array
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA, $userB])->persist();
        $folderB = FolderFactory::make()->withFoldersRelationsFor([$userA, $userB])->persist();

        return [$folderA, $folderB, $userA, $userB];
    }

    public function testFoldersRelationsHaveAndAreChildrenService_NoChildrenNoParent_RootFolderHavingChildrenInMultipleTrees()
    {
        [$folderA, $folderB, $userA, $userB] = $this->providerRootFolderHavingChildrenHavingChildrenInMultipleTrees();
        // Check the whole graph.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id]));
        // Check for the given user A.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id], $userA->id));
        // Check for the given user B.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id], $userB->id));
    }

    public function providerRootFolderHavingChildrenHavingChildrenInMultipleTrees(): array
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA, $userB])->persist();
        $folderB = FolderFactory::make()->withFoldersRelationsFor([$userA, $userB], $folderA)->persist();

        return [$folderA, $folderB, $userA, $userB];
    }

    public function testFoldersRelationsHaveAndAreChildrenService_NoChildrenNoParent_ChildFolderHavingNoChildren()
    {
        [$folderA, $folderB, $userA, $userB] = $this->providerRootFolderHavingChildrenHavingChildrenInMultipleTrees();
        // Check the whole graph.
        $this->assertFalse($this->service->haveAndAreChildren([$folderB->id]));
        // Check for the given user A.
        $this->assertFalse($this->service->haveAndAreChildren([$folderB->id], $userA->id));
        // Check for the given user B.
        $this->assertFalse($this->service->haveAndAreChildren([$folderB->id], $userB->id));
    }

    public function testFoldersRelationsHaveAndAreChildrenService_NoChildrenNoParent_RootFolderHavingChildrenInOneTreeOnly()
    {
        [$folderA, $folderB, $userA, $userB] = $this->providerRootFolderHavingChildrenInOneTreeOnly();
        // Check the whole graph.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id]));
        // Check for the given user A.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id], $userA->id));
    }

    public function providerRootFolderHavingChildrenInOneTreeOnly(): array
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderB = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderA)->persist();
        FoldersRelationFactory::make()->user($userB)
            ->foreignModelFolder($folderB)->persist();

        return [$folderA, $folderB, $userA, $userB];
    }

    public function testFoldersRelationsHaveAndAreChildrenService_NoChildrenNoParent_ChildHavingParentInOneTreeOnly()
    {
        [$folderA, $folderB, $userA, $userB] = $this->providerRootFolderHavingChildrenInOneTreeOnly();
        // Check the whole graph.
        $this->assertFalse($this->service->haveAndAreChildren([$folderB->id]));
        // Check for the given user A.
        $this->assertFalse($this->service->haveAndAreChildren([$folderB->id], $userA->id));
        // Check for the given user B.
        $this->assertFalse($this->service->haveAndAreChildren([$folderB->id], $userB->id));
    }

    /* ************************************************************************
     * Check multiple folders that have no parent and children
     ************************************************************************ */

    public function testFoldersRelationsHaveAndAreChildrenService_NoChildrenNoParent_MultipleFoldersAtRoot()
    {
        [$folderA, $folderB, $userA] = $this->providerRootFoldersHavingSiblings();
        // Check the whole graph.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id, $folderB->id]));
        // Check for a given user.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id, $folderB->id], $userA->id));
    }

    public function testFoldersRelationsHaveAndAreChildrenService_NoChildrenNoParent_MultipleFoldersAsLeaf()
    {
        [$folderA, $folderB, $userA] = $this->providerRootFoldersHavingSiblings();
        // Check the whole graph.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id, $folderB->id]));
        // Check for a given user.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id, $folderB->id], $userA->id));
    }

    public function testFoldersRelationsHaveAndAreChildrenService_NoChildrenNoParent_MultipleFoldersOneAtRootOneAsChild()
    {
        [$folderA, $folderB, $userA, $userB] = $this->providerRootFolderHavingChildrenHavingChildrenInMultipleTrees();
        // Check the whole graph.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id, $folderB->id]));
        // Check for the given user A.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id, $folderB->id], $userA->id));
        // Check for the given user B.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id, $folderB->id], $userB->id));
    }

    public function testFoldersRelationsHaveAndAreChildrenService_NoChildrenNoParent_MultipleRootAndChildrenFolders()
    {
        [$folderA, $folderB, $folderC, $folderD, $userA] = $this->providerMultipleRootAndChildrenFolders();
        // Check the whole graph.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id, $folderB->id, $folderC->id, $folderD->id]));
        // Check for the given user A.
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id, $folderB->id, $folderC->id, $folderD->id], $userA->id));
    }

    public function providerMultipleRootAndChildrenFolders(): array
    {
        $userA = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderB = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderA)->persist();
        $folderC = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderD = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderC)->persist();

        return [$folderA, $folderB, $folderC, $folderD, $userA];
    }

    /* ************************************************************************
     * Check folders that have parent and children
     ************************************************************************ */

    public function testFoldersRelationsHaveAndAreChildrenService_ChildrenAndParent_ChildAndParent()
    {
        [$folderA, $folderB, $folderC, $userA] = $this->providerChildAndParent();
        // Check the whole graph.
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id]));
        // Check for the given user A.
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id], $userA->id));
    }

    public function providerChildAndParent(): array
    {
        $userA = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderB = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderA)->persist();
        $folderC = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderB)->persist();

        return [$folderA, $folderB, $folderC, $userA];
    }

    public function testFoldersRelationsHaveAndAreChildrenService_ChildrenAndParent_ChildAndParentInMultipleTrees()
    {
        [$folderA, $folderB, $folderC, $userA, $userB] = $this->providerChildAndParentInMultipleTrees();
        // Check the whole graph.
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id]));
        // Check for the given user A.
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id], $userA->id));
        // Check for the given user B.
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id], $userB->id));
    }

    public function providerChildAndParentInMultipleTrees(): array
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA, $userB])->persist();
        $folderB = FolderFactory::make()->withFoldersRelationsFor([$userA, $userB], $folderA)->persist();
        $folderC = FolderFactory::make()->withFoldersRelationsFor([$userA, $userB], $folderB)->persist();

        return [$folderA, $folderB, $folderC, $userA, $userB];
    }

    public function testFoldersRelationsHaveAndAreChildrenService_ChildrenAndParent_ChildAndParentInOnlyOneTree()
    {
        [$folderA, $folderB, $folderC, $userA, $userB] = $this->providerChildAndParentInOnlyOneTree();
        // Check the whole graph.
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id]));
        // Check for the given user A.
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id], $userA->id));
        // Check for the given user B.
        $this->assertFalse($this->service->haveAndAreChildren([$folderB->id], $userB->id));
    }

    public function providerChildAndParentInOnlyOneTree(): array
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderB = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderA)->persist();
        FoldersRelationFactory::make()->user($userB)->foreignModelFolder($folderB)->folderParent(null)->persist();
        $folderC = FolderFactory::make()->withFoldersRelationsFor([$userA, $userB], $folderB)->persist();

        return [$folderA, $folderB, $folderC, $userA, $userB];
    }

    public function testFoldersRelationsHaveAndAreChildrenService_ChildrenAndParent_ChildInOneTreeParentInAnotherOne()
    {
        [$folderA, $folderB, $folderC, $userA, $userB] = $this->providerChildInOneTreeParentInAnotherOne();
        // Check the whole graph.
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id]));
        // Check for the given user A.
        $this->assertFalse($this->service->haveAndAreChildren([$folderB->id], $userA->id));
        // Check for the given user B.
        $this->assertFalse($this->service->haveAndAreChildren([$folderB->id], $userB->id));
    }

    public function providerChildInOneTreeParentInAnotherOne(): array
    {
        $userA = UserFactory::make()->persist();
        $userB = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderB = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderA)->persist();
        FoldersRelationFactory::make()->user($userB)->foreignModelFolder($folderB)->folderParent(null)->persist();
        $folderC = FolderFactory::make()->withFoldersRelationsFor([$userB], $folderB)->persist();

        return [$folderA, $folderB, $folderC, $userA, $userB];
    }

    /* ************************************************************************
     * Check multiple folders that have parent and children
     ************************************************************************ */

    public function testFoldersRelationsHaveAndAreChildrenService_ChildrenAndParent_MultipleChildrenAndParent()
    {
        [$folderA, $folderB, $folderC, $folderD, $userA] = $this->providerMultipleChildrenAndParent();
        // Check the whole graph.
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id, $folderC->id]));
        // Check for the given user A.
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id, $folderC->id], $userA->id));
    }

    public function providerMultipleChildrenAndParent(): array
    {
        $userA = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderB = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderA)->persist();
        $folderC = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderB)->persist();
        $folderD = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderC)->persist();

        return [$folderA, $folderB, $folderC, $folderD, $userA];
    }

    public function testFoldersRelationsHaveAndAreChildrenService_ChildrenAndParent_MultipleChildrenAndParentInMultipleTrees()
    {
        [$folderA, $folderB, $folderC, $folderD, $folderE, $folderF, $folderG, $folderH, $userA, $userB] = $this->providerMultipleChildrenAndParentInMultipleTrees();
        // Check the whole graph.
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id, $folderC->id, $folderF->id, $folderG->id]));
        // Check for the given user A.
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id, $folderC->id], $userA->id));
        $this->assertFalse($this->service->haveAndAreChildren([$folderA->id, $folderD->id], $userA->id));
        $this->assertFalse($this->service->haveAndAreChildren([$folderE->id, $folderH->id], $userA->id));
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id, $folderC->id, $folderF->id, $folderG->id], $userA->id));
        // Check for the given user B.
        $this->assertTrue($this->service->haveAndAreChildren([$folderF->id, $folderG->id], $userB->id));
        $this->assertFalse($this->service->haveAndAreChildren([$folderE->id, $folderH->id], $userB->id));
        $this->assertFalse($this->service->haveAndAreChildren([$folderB->id, $folderC->id], $userB->id));
        $this->assertTrue($this->service->haveAndAreChildren([$folderB->id, $folderC->id, $folderF->id, $folderG->id], $userB->id));
    }

    public function providerMultipleChildrenAndParentInMultipleTrees(): array
    {
        $userA = UserFactory::make()->persist();
        $folderA = FolderFactory::make()->withFoldersRelationsFor([$userA])->persist();
        $folderB = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderA)->persist();
        $folderC = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderB)->persist();
        $folderD = FolderFactory::make()->withFoldersRelationsFor([$userA], $folderC)->persist();

        $userB = UserFactory::make()->persist();
        $folderE = FolderFactory::make()->withFoldersRelationsFor([$userB])->persist();
        $folderF = FolderFactory::make()->withFoldersRelationsFor([$userB], $folderE)->persist();
        $folderG = FolderFactory::make()->withFoldersRelationsFor([$userB], $folderF)->persist();
        $folderH = FolderFactory::make()->withFoldersRelationsFor([$userB], $folderG)->persist();

        return [$folderA, $folderB, $folderC, $folderD, $folderE, $folderF, $folderG, $folderH, $userA, $userB];
    }
}
