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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Share;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;

class ShareSearchControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;

    /*
     *
     * For each tests a "useless" variable has been created, before removing $fixtures everything was working well, but after getting rid of $fixtures every tests stop passing and I found out that adding a guest make the test pass. Needs to be investigate
     *
     */

    public function testShareSearchController_Success(): void
    {
        $user = UserFactory::make()->user()->persist();
        $this->loginAs($user);

        $readingUser = UserFactory::make()->user()->persist();
        $inactiveUser = UserFactory::make()->inactive()->persist();
        $deletedUser = UserFactory::make()->deleted()->persist();
        $useless = UserFactory::make()->guest()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $resourceId = ResourceFactory::make()->withPermissionsFor([$user], Permission::OWNER)->withPermissionsFor([$readingUser], Permission::READ)->persist()->get('id');

        $this->getJson("/share/search-users/resource/$resourceId.json");
        $aros = $this->_responseJsonBody;
        $this->assertNotEmpty($aros);
        $arosIds = Hash::extract($aros, '{n}.id');

        // Should find the user Edith
        $readingUserId = $readingUser->get('id');
        $userE = $aros[array_search($readingUserId, $arosIds)];
        $this->assertNotEmpty($userE);
        $this->assertUserAttributes($userE);

        // Should not return inactive users
        $inactiveUserId = $inactiveUser->get('id');
        $this->assertFalse(array_search($inactiveUserId, $arosIds));

        // Should not return deleted users
        $deletedUserId = $deletedUser->get('id');
        $this->assertFalse(array_search($deletedUserId, $arosIds));

        // Should find the group creative
        $groupId = $group->get('id');
        $groupCreated = $aros[array_search($groupId, $arosIds)];
        $this->assertNotEmpty($groupCreated);
        $this->assertGroupAttributes($groupCreated);
        // Contain user count field.
        $this->assertNotEmpty($groupCreated->user_count);

        // Should not return deleted groups
        $deletedGroupId = UuidFactory::uuid();
        $this->assertFalse(array_search($deletedGroupId, $arosIds));
    }

    public function testShareSearchController_Success_SearchUserWang(): void
    {
        $user = UserFactory::make()->user()->persist();
        $this->loginAs($user);

        $searchedUser = UserFactory::make()->user()->persist();
        $useless = UserFactory::make()->guest()->persist();
        $searchedUserId = $searchedUser->get('id');
        $searchedUserEmail = $searchedUser->get('username');

        $resourceId = ResourceFactory::make()->withPermissionsFor([$user], Permission::OWNER)->withPermissionsFor([$searchedUser], Permission::READ)->persist()->get('id');

        $filterParams = "filter[search]=$searchedUserEmail";

        $this->getJson("/share/search-users/resource/$resourceId.json?$filterParams&api-version=2");
        $aros = $this->_responseJsonBody;
        $this->assertNotEmpty($aros);
        $this->assertCount(1, $aros);
        $this->assertEquals($searchedUserId, $aros[0]->id);
    }

    public function testShareSearchController_Success_SearchGroupCreative(): void
    {
        $user = UserFactory::make()->user()->persist();
        $this->loginAs($user);
        $useless = UserFactory::make()->guest()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $groupId = $group->get('id');
        $groupName = $group->get('name');
        $resourceId = ResourceFactory::make()->withPermissionsFor([$user], Permission::OWNER)->persist()->get('id');
        $filterParams = "filter[search]=$groupName";

        $this->getJson("/share/search-users/resource/$resourceId.json?$filterParams&api-version=2");
        $aros = $this->_responseJsonBody;
        $this->assertNotEmpty($aros);
        $this->assertCount(1, $aros);
        $this->assertEquals($groupId, $aros[0]->id);
    }

    public function testShareSearchController_Error_NotAuthenticated(): void
    {
        $resourceOwner = UserFactory::make()->admin()->persist();
        $resourceId = ResourceFactory::make()->withCreatorAndPermission($resourceOwner)->persist()->get('id');

        $this->getJson("/share/search-users/resource/$resourceId.json");
        $this->assertAuthenticationError();
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testShareSearchController_Error_NotJson(): void
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $this->get("/share/search-users/resource/$resourceId");
        $this->assertResponseCode(404);
    }
}
