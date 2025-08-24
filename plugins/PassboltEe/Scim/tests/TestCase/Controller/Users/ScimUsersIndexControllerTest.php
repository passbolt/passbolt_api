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
 * @since        5.5.0
 */

namespace Passbolt\Scim\Test\TestCase\Controller\Users;

use App\Test\Factory\RoleFactory;
use App\Test\Lib\AppIntegrationTestCase;
use Passbolt\Scim\ScimPlugin;
use Passbolt\Scim\Test\Factory\ScimEntryFactory;

/**
 * @covers \App\Controller\Users\UsersIndexController
 */
class ScimUsersIndexControllerTest extends AppIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
    }

    public function testScimUsersIndexController_Success_As_Admin_Contain_Scim_Entry(): void
    {
        $this->enableFeaturePlugin(ScimPlugin::class);
        $scimEntry = ScimEntryFactory::make()->withUser()->persist();

        $admin = $this->logInAsAdmin();
        $this->getJson('/users.json?contain[scim_entry]=1');
        $this->assertSuccess();
        $users = $this->getResponseBodyAsArray();
        foreach ($users as $user) {
            if ($user['id'] === $admin->id) {
                $this->assertNull($user['scim_entry']);
            } else {
                $this->assertSame($scimEntry['id'], $user['scim_entry']['id']);
            }
        }
    }

    public function testScimUsersIndexController_Success_As_Admin_Plugin_Disabled_Should_Not_Contain_Scim_Entry(): void
    {
        ScimEntryFactory::make()->withUser()->persist();

        $this->logInAsUser();
        $this->getJson('/users.json?contain[scim_entry]=1');
        $this->assertSuccess();
        $users = $this->getResponseBodyAsArray();
        foreach ($users as $user) {
            $this->assertArrayNotHasKey('scim_entry', $user);
        }
    }

    public function testScimUsersIndexController_Success_Not_Admin_Does_Not_Contain_Scim_Entry(): void
    {
        $this->enableFeaturePlugin(ScimPlugin::class);
        ScimEntryFactory::make()->withUser()->persist();

        $this->logInAsUser();
        $this->getJson('/users.json?contain[scim_entry]=1');
        $this->assertSuccess();
        $users = $this->getResponseBodyAsArray();
        foreach ($users as $user) {
            $this->assertArrayNotHasKey('scim_entry', $user);
        }
    }
}
