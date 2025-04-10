<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace Passbolt\Tags\Test\TestCase\Controller;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Cake\Utility\Hash;
use Passbolt\Tags\Middleware\TagsReadOnlyModeMiddleware;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class TagsIndexControllerTest extends TagPluginIntegrationTestCase
{
    // A user not logged in should not be able to see tags

    public function testTagsIndexController_NotLoggedIn()
    {
        $this->getJson('/tags.json?api-version=v2');
        $this->assertResponseError();
        $response = json_decode($this->_getBodyAsString());
        $this->assertTextContains('error', $response->header->status);
        $this->assertTextContains('Authentication is required to continue', $response->header->message);
    }

    // A user should see personal and shared tags or resources via direct and group permissions

    public function testTagsIndexController_Success()
    {
        Configure::write(TagsReadOnlyModeMiddleware::PASSBOLT_PLUGINS_TAGS_READ_ONLY_MODE, true);
        $user = $this->logInAsUser();
        $group = GroupFactory::make()->withGroupsUsersFor([$user])->persist();
        /** @var \App\Model\Entity\Resource $resourceWithGroupPermission */
        $resourceWithGroupPermission = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->persist();
        /** @var \App\Model\Entity\Resource $resourceWithDirectPermission */
        $resourceWithDirectPermission = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->persist();
        TagFactory::make(['slug' => 'alpha'])->isPersonalFor($resourceWithDirectPermission, $user)->persist();
        TagFactory::make(['slug' => '#bravo'])->isSharedFor($resourceWithGroupPermission)->persist();
        TagFactory::make(['slug' => '#charlie'])->isSharedFor($resourceWithDirectPermission)->persist();
        $this->getJson('/tags.json?api-version=v2');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $expected = ['alpha', '#bravo', '#charlie'];
        foreach ($expected as $result) {
            $this->assertTrue(in_array($result, $results));
        }
    }

    // A user should not see other users personal tags or shared tags of resource they don't have access to
    public function testTagsIndexController_SuccessDoubleCheck()
    {
        $users = [$userA, $userB] = UserFactory::make(2)->user()->persist();

        $group = GroupFactory::make()->withGroupsUsersFor($users)->persist();
        /** @var \App\Model\Entity\Resource $resourceWithGroupPermission */
        $resourceWithGroupPermission = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->persist();
        /** @var \App\Model\Entity\Resource $resourceWithDirectPermission */
        $resourceWithDirectPermission = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->persist();
        TagFactory::make(['slug' => 'alpha'])->isPersonalFor($resourceWithDirectPermission, $userB)->persist();
        TagFactory::make(['slug' => '#bravo'])->isSharedFor($resourceWithGroupPermission)->persist();
        TagFactory::make(['slug' => '#charlie'])->isSharedFor($resourceWithDirectPermission)->persist();

        $this->logInAs($userB);
        $this->getJson('/tags.json?api-version=v2');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $expected = ['#bravo'];
        $notExpected = ['alpha', '#charlie'];
        foreach ($expected as $result) {
            $this->assertTrue(in_array($result, $results));
        }
        foreach ($notExpected as $result) {
            $this->assertFalse(in_array($result, $results));
        }
    }
}
