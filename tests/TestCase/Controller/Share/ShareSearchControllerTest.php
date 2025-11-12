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
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;

/**
 * @covers \App\Controller\Share\ShareSearchController
 */
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
        UserFactory::make()->guest()->persist();
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

    public function testShareSearchController_Success_SearchUsername(): void
    {
        $user = UserFactory::make()->user()->persist();
        $this->loginAs($user);

        $searchedUser = UserFactory::make()->user()->persist();
        RoleFactory::make()->guest()->persist();
        $searchedUserId = $searchedUser->get('id');
        $searchedUserEmail = $searchedUser->get('username');

        $resourceId = ResourceFactory::make()->withPermissionsFor([$user], Permission::OWNER)->withPermissionsFor([$searchedUser], Permission::READ)->persist()->get('id');

        $filterParams = 'filter[search]=' . strtoupper($searchedUserEmail);

        $this->getJson("/share/search-users/resource/$resourceId.json?$filterParams");
        $aros = $this->_responseJsonBody;
        $this->assertNotEmpty($aros);
        $this->assertCount(1, $aros);
        $this->assertEquals($searchedUserId, $aros[0]->id);
    }

    public function testShareSearchController_Success_Search_Profile(): void
    {
        RoleFactory::make()->guest()->persist();
        $user = $this->logInAsUser();
        UserFactory::make()->withProfileName('Jjjjjohn', 'Doe')->user()->persist();
        UserFactory::make()->withProfileName('Sam', 'jJJJJim')->user()->persist();
        // Insert some noise
        UserFactory::make(5)->persist();

        $resourceId = ResourceFactory::make()->withPermissionsFor([$user])->persist()->get('id');

        $filterParams = 'filter[search]=jJj';

        $this->getJson("/share/search-users/resource/$resourceId.json?$filterParams");
        $this->assertResponseSuccess();
        $aros = $this->_responseJsonBody;
        $this->assertNotEmpty($aros);
        $this->assertCount(2, $aros);
    }

    public function testShareSearchController_Success_SearchGroupCreative(): void
    {
        $user = UserFactory::make()->user()->persist();
        $this->loginAs($user);
        UserFactory::make()->guest()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $groupId = $group->get('id');
        $groupName = $group->get('name');
        $resourceId = ResourceFactory::make()->withPermissionsFor([$user], Permission::OWNER)->persist()->get('id');
        $filterParams = 'filter[search]=' . strtoupper($groupName);

        $this->getJson("/share/search-users/resource/$resourceId.json?$filterParams");
        $aros = $this->_responseJsonBody;
        $this->assertNotEmpty($aros);
        $this->assertCount(1, $aros);
        $this->assertEquals($groupId, $aros[0]->id);
    }

    /**
     * Provider for testShareSearchController_Success_ContainWhitelist()
     *
     * @return array
     */
    public static function containWhitelistProvider(): array
    {
        return [
            [
                // Case-1: Empty contain, by default all existing contains will be present
                'contain' => [],
                'expected' => [
                    'keysPresent' => ['gpgkey', 'groups_users', 'role', 'profile'],
                    'keysAbsent' => [],
                ],
            ],
            [
                // Case-2: Disable specific contain
                'contain' => ['gpgkey' => 0],
                'expected' => [
                    'keysPresent' => ['profile'],
                    'keysAbsent' => ['gpgkey', 'groups_users', 'role'],
                ],
            ],
            [
                // Case-3: Enable + disable specific contains from whitelist
                'contain' => ['groups_users' => 1, 'gpgkey' => 0],
                'expected' => [
                    'keysPresent' => ['groups_users', 'profile'],
                    'keysAbsent' => ['gpgkey', 'role'],
                ],
            ],
            [
                // Case-4: `profile` always included because it is required for "search" query param
                'contain' => ['profile' => 0],
                'expected' => [
                    'keysPresent' => ['gpgkey', 'groups_users', 'role', 'profile'],
                    'keysAbsent' => [],
                ],
            ],
            [
                // Case-5: All contains by default false if any contain is provided
                'contain' => ['gpgkey' => 1],
                'expected' => [
                    'keysPresent' => ['gpgkey', 'profile'],
                    'keysAbsent' => ['role', 'groups_users'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider containWhitelistProvider
     */
    public function testShareSearchController_Success_ContainWhitelist(array $contain, array $expected): void
    {
        UserFactory::make()->guest()->with('Gpgkeys')->persist();
        $user = UserFactory::make()->user()->with('Gpgkeys')->persist();
        $this->loginAs($user);
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();
        $group->get('id');
        $group->get('name');
        ResourceFactory::make()->withPermissionsFor([$user])->persist()->get('id');

        $queryParams = http_build_query(['contain' => $contain, 'api-version' => 2]);
        $this->getJson("/share/search-aros.json?{$queryParams}");

        $resultArray = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($resultArray);
        $this->assertCount(2, $resultArray);
        foreach ($expected['keysPresent'] as $key) {
            $this->assertTrue(Hash::check($resultArray, "{n}.$key"));
        }
        foreach ($expected['keysAbsent'] as $key) {
            $this->assertFalse(Hash::check($resultArray, "{n}.$key"));
        }
    }

    public function testShareSearchController_Success_Limit(): void
    {
        RoleFactory::make()->guest()->persist();
        UserFactory::make(50)->user()->persist();
        $user = UserFactory::make()->user()->persist();
        GroupFactory::make(55)->withGroupsManagersFor([$user])->persist();
        $this->loginAs($user);

        $queryParams = http_build_query(['api-version' => 2, 'search' => 'a']);
        $this->getJson("/share/search-aros.json?{$queryParams}");

        $response = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($response);
        // This is the maximum number of results we'll get because of hard-coded limit (25) specified in the controller.
        $this->assertCount(50, $response);
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
