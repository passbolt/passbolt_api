<?php
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

namespace App\Test\TestCase\Controller\Notifications;

use App\Model\Entity\Permission;
use App\Test\TestCase\Controller\Share\ShareControllerTest;
use App\Utility\UuidFactory;

class ShareNotificationTest extends ShareControllerTest
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Gpgkeys', 'app.Base/Profiles', 'app.Base/Roles',
        'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Permissions', 'app.Base/Secrets',
        'app.Base/EmailQueue', 'app.Base/Avatars', 'app.Base/Favorites'
    ];

    public function testShareNotificationSuccess()
    {
        // Define actors of this tests
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        // Users
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userEId = UuidFactory::uuid('user.id.edith');
        $userFId = UuidFactory::uuid('user.id.frances');
        // Groups
        $groupBId = UuidFactory::uuid('group.id.board');
        $groupFId = UuidFactory::uuid('group.id.freelancer');
        $groupAId = UuidFactory::uuid('group.id.accounting');

        // Expected results.
        $expectedAddedUsersIds = [];
        $expectedRemovedUsersIds = [];

        // Build the changes.
        $data = ['permissions' => []];

        // Users permissions changes.
        // Change the permission of the user Ada to read (no users are expected to be added or removed).
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$userAId"), 'type' => Permission::READ];
        // Delete the permission of the user Betty.
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$userBId"), 'delete' => true];
        $expectedRemovedUsersIds[] = $userBId;
        // Add an owner permission for the user Edith
        $data['permissions'][] = ['aro' => 'User', 'aro_foreign_key' => $userEId, 'type' => Permission::OWNER];
        $data['secrets'][] = ['user_id' => $userEId, 'data' => $this->getValidSecret()];
        $expectedAddedUsersIds[] = $userEId;

        // Groups permissions changes.
        // Change the permission of the group Board (no users are expected to be added or removed).
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$groupBId"), 'type' => Permission::OWNER];
        // Delete the permission of the group Freelancer.
        $data['permissions'][] = ['id' => UuidFactory::uuid("permission.id.$resourceId-$groupFId"), 'delete' => true];
        // Add a read permission for the group Accounting.
        $data['permissions'][] = ['aro' => 'Group', 'aro_foreign_key' => $groupAId, 'type' => Permission::READ];
        $data['secrets'][] = ['user_id' => $userFId, 'data' => $this->getValidSecret()];

        $this->authenticateAs('ada');
        $this->putJson("/share/resource/$resourceId.json", $data);
        $this->assertSuccess();

        // check email notification
        $this->get('/seleniumtests/showLastEmail/edith@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('shared a password with you');
        $this->assertResponseContains('Name: cakephp');
        $this->assertResponseContains('Username: cake');
        $this->assertResponseContains('The rapid and tasty php development framework');
        $this->assertResponseContains('URL: cakephp.org');
        $this->assertResponseContains('BEGIN PGP MESSAGE');

        $this->get('/seleniumtests/showLastEmail/frances@passbolt.com');
        $this->assertResponseCode(200);
        $this->assertResponseContains('shared a password with you');
    }
}
