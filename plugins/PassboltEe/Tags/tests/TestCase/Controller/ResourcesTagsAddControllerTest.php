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
namespace Passbolt\Tags\Test\TestCase\Controller;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Utility\Hash;
use Passbolt\Tags\Middleware\TagsReadOnlyModeMiddleware;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class ResourcesTagsAddControllerTest extends TagPluginIntegrationTestCase
{
    // A "not found" error is returned if the resource does not exist

    public function testResourcesTagsAddController_ResourceDoesNotExistError()
    {
        $this->logInAsUser();
        $resourceId = UuidFactory::uuid();
        $data = ['tags' => []];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $this->assertError(404);
    }

    public function testTagsResourcesTagsAdd_Read_Only_Mode()
    {
        Configure::write(TagsReadOnlyModeMiddleware::PASSBOLT_PLUGINS_TAGS_READ_ONLY_MODE, true);
        $this->logInAsUser();
        $resourceId = UuidFactory::uuid();
        $data = ['tags' => []];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $this->assertForbiddenError('The tags plugin is in read-only mode.');
    }

    // A "not found" error is returned if the user does not have read access on the resource

    public function testResourcesTagsAddController_NoResourcePermissionError()
    {
        $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->persist();
        $data = ['tags' => []];
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $this->assertError(404);
    }

    // A user can add personal tags on a resource with read access

    public function testResourcesTagsAddController_ReadPermissionPersonalTagSuccess()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $data = ['tags' => ['tag1', '🤔']];
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertCount(2, $results);
        $this->assertContains('tag1', $results);
        $this->assertContains('🤔', $results);
        $this->assertSame(2, TagFactory::count());
        $this->assertSame(2, ResourcesTagFactory::count());
    }

    // A user can not add shared tags on a resource with read access

    public function testResourcesTagsAddController_ReadPermissionSharedTagError()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user], Permission::READ)
            ->persist();
        $data = ['tags' => ['#tag1']];
        $this->postJson("/tags/{$resource->id}.json?api-version=2", $data);
        $this->assertBadRequestError('You do not have the permission to edit shared tags on this resource');
    }

    // A user can not add shared tags on a resource with read access

    public function testResourcesTagsAddController_TagValidationError()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->persist();
        $data = ['tags' => [bin2hex(openssl_random_pseudo_bytes(256))]];
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $this->assertError(400);
        $response = json_decode($this->_getBodyAsString());
        $msg = 'The tag length should be maximum 128 characters.';
        $this->assertEquals($response->body[0]->slug->maxLength, $msg);
        $this->assertSame(0, TagFactory::count());
        $this->assertSame(0, ResourcesTagFactory::count());
    }

    // A user can add shared and personal tags on a resource it owns via direct permission

    public function testResourcesTagsAddController_Success()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->persist();
        $data = ['tags' => ['#bravo', 'flip', '#stup', 'hotel']];
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertCount(4, $results);
        $this->assertContains('#bravo', $results);
        $this->assertContains('#stup', $results);
        $this->assertContains('flip', $results);
        $this->assertContains('hotel', $results);
        $this->assertSame(4, TagFactory::count());
        $this->assertSame(4, ResourcesTagFactory::count());
    }

    // A user can add shared and personal tags on a resource it owns via group permission

    public function testResourcesTagsAddController_SuccessGroupOwnership()
    {
        $user = $this->logInAsUser();
        $group = GroupFactory::make()->withGroupsUsersFor([$user])->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->persist();

        $data = ['tags' => ['#bravo', 'stup', 'flip']];
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $response = json_decode($this->_getBodyAsString());
        $this->assertSuccess();
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertCount(3, $results);
        $this->assertContains('#bravo', $results);
        $this->assertContains('stup', $results);
        $this->assertContains('flip', $results);
        $this->assertSame(3, TagFactory::count());
        $this->assertSame(3, ResourcesTagFactory::count());
    }

    // A user cannot add shared tags on a resource it can read via group permission

    public function testResourcesTagsAddController_FailedAddingSharedTagsOnGroupReadPermission()
    {
        $user = $this->logInAsUser();
        $group = GroupFactory::make()->withGroupsUsersFor([$user])->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();

        $data = ['tags' => ['#golf', 'stup', 'flip']];
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $this->assertBadRequestError('You do not have the permission to edit shared tags on this resource.');
        $this->assertSame(0, TagFactory::count());
        $this->assertSame(0, ResourcesTagFactory::count());
    }

    // A user can add shared tags on a resource it can read via group permission

    public function testResourcesTagsAddController_SuccessGroupOwner()
    {
        $user = $this->logInAsUser();
        $group = GroupFactory::make()->withGroupsUsersFor([$user])->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->persist();

        $data = ['tags' => ['#golf', 'stup', 'flip']];
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $response = json_decode($this->_getBodyAsString());
        $this->assertSuccess();
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertCount(3, $results);
        $this->assertContains('#golf', $results);
        $this->assertContains('stup', $results);
        $this->assertContains('flip', $results);
        $this->assertSame(3, TagFactory::count());
        $this->assertSame(3, ResourcesTagFactory::count());
    }

    // A user can delete shared and personal tags on a resource it owns via direct permission

    public function testResourcesTagsAddController_SuccessDelete()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user])
            ->persist();
        TagFactory::make()->isPersonalFor($resource, $user)->persist();
        TagFactory::make()->isSharedFor($resource)->persist();
        $data = ['tags' => []];
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertEquals([], $results);
        $this->assertSame(0, TagFactory::count());
        $this->assertSame(0, ResourcesTagFactory::count());
    }

    // A user can delete shared and personal tags on a resource it owns via group permission

    public function testResourcesTagsAddController_SuccessDeleteGroupOwnership()
    {
        $user = $this->logInAsUser();
        $group = GroupFactory::make()->withGroupsUsersFor([$user])->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$group])
            ->persist();
        TagFactory::make()->isPersonalFor($resource, $user)->persist();
        TagFactory::make()->isSharedFor($resource)->persist();

        $data = ['tags' => []];
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertEquals([], $results);
        $this->assertSame(0, TagFactory::count());
        $this->assertSame(0, ResourcesTagFactory::count());
    }

    // A user deleting personal tags on a resource should not delete other users personal tags

    public function testResourcesTagsAddController_SuccessDeleteKeepsOtherPeoplePersonalTags()
    {
        [$user1, $user2] = UserFactory::make(2)->persist();
        $this->logInAs($user1);

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user1])->persist();
        TagFactory::make()
            ->isPersonalFor($resource, $user1)
            ->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $user2Tag */
        $user2Tag = TagFactory::make()
            ->isPersonalFor($resource, $user2)
            ->persist();

        $data = ['tags' => []];
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $this->assertSuccess();

        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertEquals([], $results);
        ResourcesTagFactory::firstOrFail([
            'resource_id' => $resource->id,
            'user_id' => $user2->id,
        ]);
        TagFactory::get($user2Tag->id);
        $this->assertSame(1, TagFactory::count());
        $this->assertSame(1, ResourcesTagFactory::count());
    }

    // Unused tags should be deleted

    public function testResourcesTagsAddController_CleanupSuccess()
    {
        [$user1, $user2] = UserFactory::make(2)->persist();
        $this->logInAs($user1);

        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user1])->persist();
        $resource2 = ResourceFactory::make()->withPermissionsFor([$user2])->persist();
        TagFactory::make()
            ->isPersonalFor($resource, $user1)
            ->persist();
        TagFactory::make()
            ->isSharedFor($resource)
            ->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $personalTagToBeKept */
        $personalTagToBeKept = TagFactory::make()
            ->isPersonalFor($resource, $user2)
            ->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $sharedTagToBeKept */
        $sharedTagToBeKept = TagFactory::make()
            ->isSharedFor($resource)
            ->isSharedFor($resource2)
            ->persist();

        $data = ['tags' => []];
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $this->assertSuccess();

        // Check tag cleanup
        TagFactory::firstOrFail(['id' => $personalTagToBeKept->id]);
        TagFactory::firstOrFail(['id' => $sharedTagToBeKept->id]);

        // Personal tag unshared should have been deleted (not in used anymore)
        // Shared tag unshared too
        $this->assertSame(2, TagFactory::count());
    }

    public function testResourcesTagsAddController_Success_SlugWithDifferentCase()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag $resourceTag */
        $resourceTag = ResourcesTagFactory::make(['resource_id' => $resource->id])
            ->with('Users', $user)
            ->with('Tags', ['slug' => 'test'])
            ->persist();
        ResourcesTagFactory::make()
            ->with('Users')
            ->with('Tags', $resourceTag->tag)
            ->persist();

        $this->postJson("/tags/{$resource->id}.json?api-version=2", [
            'tags' => ['TEST'],
        ]);

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        $this->assertCount(1, $responseArray);
        $this->assertNotSame($resourceTag->tag_id, $responseArray[0]['id']);
        $this->assertCount(2, TagFactory::find()->where(['UPPER(slug)' => 'TEST']));
    }

    public function testResourcesTagsAddController_Add_Two_Unshared_Tags_With_Identical_Name()
    {
        $users = [$user1, $user2] = UserFactory::make(2)->persist();
        GroupFactory::make()->withGroupsUsersFor($users)->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$user1])
            ->withPermissionsFor([$user2], Permission::READ)
            ->persist();

        $tag = 'Foo';
        $data = ['tags' => [$tag]];

        $this->logInAs($user1);
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $this->assertSuccess();
        $tag1Id = $this->_responseJsonBody[0]->id;

        $this->logInAs($user2);
        $this->postJson('/tags/' . $resource->id . '.json?api-version=2', $data);
        $this->assertSuccess();
        $tag2Id = $this->_responseJsonBody[0]->id;

        // Two tags should be in the DB
        $this->assertSame(2, TagFactory::count());
        $this->assertNotSame($tag1Id, $tag2Id);
        // Two pivot table entries should be saved with each the respective user_id
        $this->assertSame(2, ResourcesTagFactory::count());
        $conditions = [
            'resource_id' => $resource->id,
            'tag_id' => $tag1Id,
            'user_id' => $user1->id,
        ];
        $this->assertSame(1, ResourcesTagFactory::find()->where($conditions)->count());
        $conditions = [
            'resource_id' => $resource->id,
            'tag_id' => $tag2Id,
            'user_id' => $user2->id,
        ];
        $this->assertSame(1, ResourcesTagFactory::find()->where($conditions)->count());
    }
}
