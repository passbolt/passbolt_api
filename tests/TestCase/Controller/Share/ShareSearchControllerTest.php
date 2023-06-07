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

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Utility\UuidFactory;
use Cake\Utility\Hash;

class ShareSearchControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;

    public $fixtures = [
        'app.Base/Users',
        'app.Base/Gpgkeys',
        'app.Base/Profiles',
        'app.Base/Roles',
        'app.Base/Groups',
        'app.Base/GroupsUsers',
        'app.Base/Resources',
        'app.Base/Permissions',
    ];

    public function testShareSearchController_Success(): void
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $this->getJson("/share/search-users/resource/$resourceId.json");
        $aros = $this->_responseJsonBody;
        $this->assertNotEmpty($aros);
        $arosIds = Hash::extract($aros, '{n}.id');

        // Should find the user Edith
        $userEId = UuidFactory::uuid('user.id.edith');
        $userE = $aros[array_search($userEId, $arosIds)];
        $this->assertNotEmpty($userE);
        $this->assertUserAttributes($userE);

        // Should not return inactive users
        $userAId = UuidFactory::uuid('user.id.ruth');
        $this->assertFalse(array_search($userAId, $arosIds));

        // Should not return deleted users
        $userAId = UuidFactory::uuid('user.id.sofia');
        $this->assertFalse(array_search($userAId, $arosIds));

        // Should find the group creative
        $groupCId = UuidFactory::uuid('group.id.creative');
        $groupC = $aros[array_search($groupCId, $arosIds)];
        $this->assertNotEmpty($groupC);
        $this->assertGroupAttributes($groupC);
        // Contain user count field.
        $this->assertNotEmpty($groupC->user_count);

        // Should not return deleted groups
        $groupDId = UuidFactory::uuid('group.id.deleted');
        $this->assertFalse(array_search($groupDId, $arosIds));
    }

    public function testShareSearchController_Success_SearchUserWang(): void
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $filterParams = 'filter[search]=wang@passbolt';
        $this->getJson("/share/search-users/resource/$resourceId.json?$filterParams&api-version=2");
        $aros = $this->_responseJsonBody;
        $this->assertNotEmpty($aros);
        $this->assertCount(1, $aros);
        $this->assertEquals(UuidFactory::uuid('user.id.wang'), $aros[0]->id);
    }

    public function testShareSearchController_Success_SearchGroupCreative(): void
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $filterParams = 'filter[search]=Creative';
        $this->getJson("/share/search-users/resource/$resourceId.json?$filterParams&api-version=2");
        $aros = $this->_responseJsonBody;
        $this->assertNotEmpty($aros);
        $this->assertCount(1, $aros);
        $this->assertEquals(UuidFactory::uuid('group.id.creative'), $aros[0]->id);
    }

    public function testShareSearchController_Error_NotAuthenticated(): void
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
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
