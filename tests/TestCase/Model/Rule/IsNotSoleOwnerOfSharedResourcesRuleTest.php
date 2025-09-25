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
 * @since         5.6.0
 */

namespace App\Test\TestCase\Model\Rule;

use App\Model\Entity\Permission;
use App\Model\Rule\IsNotSoleOwnerOfSharedResourcesRule;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class IsNotSoleOwnerOfSharedResourcesRuleTest extends TestCase
{
    use TruncateDirtyTables;

    private IsNotSoleOwnerOfSharedResourcesRule $rule;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->rule = new IsNotSoleOwnerOfSharedResourcesRule();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->rule);
        parent::setUp();
    }

    public function testIsNotSoleOwnerOfSharedResourcesRule_Share_With_One_Owner_Only()
    {
        $soleOwner = UserFactory::make()->persist();

        ResourceFactory::make()->withPermissionsFor([$soleOwner])->persist();
        $isNotSoleOwnerOfSharedResource = ($this->rule)($soleOwner);
        // Resource is shared with no other owner, but with no other users
        $this->assertTrue($isNotSoleOwnerOfSharedResource);
    }

    public function testIsNotSoleOwnerOfSharedResourcesRule_Share_With_Two_Owners()
    {
        $users = [$user1,] = UserFactory::make(2)->persist();

        ResourceFactory::make()->withPermissionsFor($users)->persist();
        $isNotSoleOwnerOfSharedResource = ($this->rule)($user1);
        // Resource is shared with two owners
        $this->assertTrue($isNotSoleOwnerOfSharedResource);
    }

    // The rule does not check if the owners are deleted, as permissions ar deleted on user delete

    public function testIsNotSoleOwnerOfSharedResourcesRule_Share_With_Two_Owners_With_One_Being_Deleted()
    {
        $user1 = UserFactory::make()->persist();
        $userDeleted = UserFactory::make()->deleted()->persist();

        ResourceFactory::make()->withPermissionsFor([$user1, $userDeleted])->persist();
        $isNotSoleOwnerOfSharedResource = ($this->rule)($user1);
        // Resource is shared with two owners, but one is deleted
        $this->assertTrue($isNotSoleOwnerOfSharedResource);
    }

    public function testIsNotSoleOwnerOfSharedResourcesRule_Share_With_One_Owner_And_One_Editor()
    {
        $owner = UserFactory::make()->persist();
        $editor = UserFactory::make()->persist();

        ResourceFactory::make()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$editor], Permission::UPDATE)
            ->persist();
        $isNotSoleOwnerOfSharedResource = ($this->rule)($owner);
        // Resource is shared with one sole owner and one editor
        $this->assertFalse($isNotSoleOwnerOfSharedResource);
    }

    /**
     * @Given that a resource is deleted
     * @And that resource is shared
     * @And has a sole owner
     * @When the rule checks if the user is a sole owner of a resource
     * @Then the result should be false, as this resource is ignored
     * @return void
     */
    public function testIsNotSoleOwnerOfSharedResourcesRule_ResourceIsNotSharedWithOtherOwnersButDeleted()
    {
        $owner = UserFactory::make()->persist();
        $editor = UserFactory::make()->persist();

        ResourceFactory::make()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$editor], Permission::UPDATE)
            ->deleted()
            ->persist();

        $isNotSoleOwnerOfSharedResource = ($this->rule)($owner);
        // Resource is shared, with one sole owner, but is deleted
        $this->assertTrue($isNotSoleOwnerOfSharedResource);
    }

    /**
     * @Given that a resource is of a deleted resource type
     * @And that resource is shared
     * @And has a sole owner
     * @When the rule checks if the user is a sole owner of a resource
     * @Then the result should be false, as this resource is ignored
     * @return void
     */
    public function testIsNotSoleOwnerOfSharedResourcesRule_ResourceIsNotSharedWithOtherOwnersButOfDeletedResourceType()
    {
        $owner = UserFactory::make()->persist();
        $editor = UserFactory::make()->persist();

        ResourceFactory::make()
            ->withPermissionsFor([$owner])
            ->withPermissionsFor([$editor], Permission::UPDATE)
            ->with('ResourceTypes', ResourceTypeFactory::make()->deleted())
            ->persist();

        $isNotSoleOwnerOfSharedResource = ($this->rule)($owner);
        // Resource is shared with one sole owner but is of deleted resource type
        $this->assertTrue($isNotSoleOwnerOfSharedResource);
    }
}
