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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Test\TestCase\Controller\Users;

use App\Test\Factory\RoleFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsUsersModelTrait;
use Passbolt\Scim\ScimPlugin;
use Passbolt\Scim\Test\Factory\ScimEntryFactory;

class ScimUsersViewControllerTest extends AppIntegrationTestCase
{
    use GroupsUsersModelTrait;

    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
    }

    public function testScimUsersViewController_Success_As_Admin_Contain_Scim_Entry(): void
    {
        $this->enableFeaturePlugin(ScimPlugin::class);
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $scimEntry = ScimEntryFactory::make()->withUser()->persist();
        $userId = $scimEntry->user->id;

        $this->logInAsAdmin();
        $this->getJson("/users/$userId.json?contain[scim_entry]=1");
        $this->assertSuccess();

        $user = $this->getResponseBodyAsArray();
        $this->assertSame($scimEntry['id'], $user['scim_entry']['id']);
    }

    public function testScimUsersViewController_Success_As_Admin_Plugin_Disabled_Should_Not_Contain_Scim_Entry(): void
    {
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $scimEntry = ScimEntryFactory::make()->withUser()->persist();
        $userId = $scimEntry->user->id;

        $this->logInAsAdmin();
        $this->getJson("/users/$userId.json?contain[scim_entry]=1");
        $this->assertSuccess();

        $user = $this->getResponseBodyAsArray();
        $this->assertArrayNotHasKey('scim_entry', $user);
    }

    public function testScimUsersIndexController_Success_Not_Admin_Does_Not_Contain_Scim_Entry(): void
    {
        $this->enableFeaturePlugin(ScimPlugin::class);
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry */
        $scimEntry = ScimEntryFactory::make()->withUser()->persist();
        $userId = $scimEntry->user->id;

        $this->logInAsUser();
        $this->getJson("/users/$userId.json?contain[scim_entry]=1");
        $this->assertSuccess();

        $user = $this->getResponseBodyAsArray();
        $this->assertArrayNotHasKey('scim_entry', $user);
    }
}
