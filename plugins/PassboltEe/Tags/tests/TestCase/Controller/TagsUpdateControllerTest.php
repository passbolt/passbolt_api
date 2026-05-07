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
 * @since         2.11.0
 */
namespace Passbolt\Tags\Test\TestCase\Controller;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Utility\Hash;
use Passbolt\Tags\Middleware\TagsReadOnlyModeMiddleware;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

/**
 * @covers \Passbolt\Tags\Controller\Tags\TagsUpdateController
 */
class TagsUpdateControllerTest extends TagPluginIntegrationTestCase
{
    /**
     * A user not logged in should not be able to update tags
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUpdateNotLoggedIn()
    {
        $this->putJson('/tags/0507cbbb-eb14-5121-9105-05380dbe64ff.json?api-version=v2');
        $this->assertResponseError();
        $this->assertAuthenticationError();
    }

    /**
     * Request with and invalid uuid should fail and give error
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUpdateInvalidTagId()
    {
        $this->logInAsUser();
        $this->putJson('/tags/invalid-tag-id.json?api-version=v2');
        $this->assertBadRequestError('The tag id is not valid.');
    }

    /**
     * Request with and non existing tagId should fail and give error
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUpdateNonExistingTagId()
    {
        $this->logInAsUser();
        $tagId = UuidFactory::uuid('tag.id.nope');
        $this->putJson("/tags/$tagId.json?api-version=v2");
        $this->assertError(404, 'The tag does not exist.');
    }

    public function testTagUpdate_Read_Only_Mode()
    {
        Configure::write(TagsReadOnlyModeMiddleware::PASSBOLT_PLUGINS_TAGS_READ_ONLY_MODE, true);
        $this->logInAsUser();
        $tagId = UuidFactory::uuid();
        $this->putJson("/tags/$tagId.json?api-version=v2");
        $this->assertForbiddenError('The tags plugin is in read-only mode.');
    }

    /**
     * A user should not be able to update a shared tag
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUpdateUserCanNotUpdateSharedTag()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make(['slug' => '#bravo'])->isSharedFor($resource)->persist();
        $this->putJson("/tags/{$tag->id}.json?api-version=v2");
        $this->assertForbiddenError('You do not have the permission to update shared tags.');
    }

    /**
     * A tag with too long slug should not be saved
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUpdatePersonalTagSlugTooLong()
    {
        $user = $this->logInAsUser();
        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag $resourceTag */
        $resourceTag = ResourcesTagFactory::make()
            ->with('Users', $user)
            ->with('Tags', ['slug' => 'firefox'])
            ->persist();
        $tagId = $resourceTag->tag->get('id');

        $this->putJson("/tags/$tagId.json?api-version=v2", [
            'slug' => str_repeat('a', 129),
        ]);

        $this->assertBadRequestError('Could not validate tag data.');
        $response = $this->getResponseBodyAsArray();
        $this->assertTrue(Hash::check($response, 'slug.maxLength'));
        $this->assertEquals('The tag length should be maximum 128 characters.', Hash::get($response, 'slug.maxLength'));
    }

    /**
     * A user should not be able to delete a tag without providing CSRF token
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUpdateUserCanNotDeleteWithoutCsrfToken()
    {
        $this->disableCsrfToken();

        $user = $this->logInAsUser();
        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag $resourceTag */
        $resourceTag = ResourcesTagFactory::make()
            ->with('Users', $user)
            ->with('Tags', ['slug' => 'firefox'])
            ->persist();
        $tagId = $resourceTag->tag->get('id');

        $this->put("/tags/$tagId.json?api-version=v2", [
            'slug' => 'brave',
        ]);

        $this->assertResponseCode(403);
        $result = $this->_getBodyAsString();
        $this->assertStringContainsString('Missing or incorrect CSRF cookie type.', $result);
    }

    /**
     * A user should be able to use unicode text in a tag
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUpdatePersonalTagSupportsUnicode()
    {
        $success = [
            'chinese' => [
                'user' => 'ada',
                'tag' => 'tag.id.alpha',
                'data' => [
                    'slug' => '新的專用資源名稱',
                ],
            ],
            'slavic' => [
                'user' => 'ada',
                'tag' => 'tag.id.firefox',
                'data' => [
                    'slug' => 'Новое имя частного ресурса',
                ],
            ],
            'french' => [
                'user' => 'betty',
                'tag' => 'tag.id.alpha',
                'data' => [
                    'slug' => 'Nouveau nom de resource privée',
                ],
            ],
            'emoticon' => [
                'user' => 'ada',
                'tag' => 'tag.id.hindi',
                'data' => [
                    'slug' => "\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}",
                ],
            ],
        ];

        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()->isPersonalFor($resource, $user)->persist();
        $tagId = $tag->id;
        foreach ($success as $test => $case) {
            $this->putJson("/tags/$tagId.json?api-version=v2", $case['data']);
            $this->assertEquals('success', $this->_responseJsonHeader->status, $test);
            $this->assertSame($case['data']['slug'], $this->_responseJsonBody->slug, $test);
            $tagId = $this->_responseJsonBody->id;
        }
    }

    /**
     * A tag update response should have the updated tag in it's body
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUserUpdateResponseContainsTag()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()->isPersonalFor($resource, $user)->persist();
        $this->putJson("/tags/{$tag->id}.json?api-version=v2", [
            'slug' => 'test slug',
        ]);
        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $this->assertEquals('test slug', $response->slug);
        $this->assertFalse($response->is_shared);
    }

    /**
     * A user should be able to update a personal tag
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUpdateUserCanUpdatePersonalTag()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make(['slug' => 'firefox'])->isPersonalFor($resource, $user)->persist();
        $this->putJson("/tags/{$tag->id}.json?api-version=v2", [
            'slug' => 'brave',
        ]);
        $this->assertSuccess();

        // Make sure we do not see the old tag in index
        $this->getJson('/tags.json?api-version=v2');
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertNotContains('firefox', $results);

        // Make sure we see the updated tag in index
        $this->assertContains('brave', $results);
    }

    /**
     * A user should be able to update a personal tag
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUpdateUserWithExistingTagHandleTagsAssociationDuplicate()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag[] $tags */
        [$tag1, $tag2] = TagFactory::make(2)->isPersonalFor($resource, $user)->persist();

        $resourcesTagsCount = ResourcesTagFactory::find()->where([
            'user_id' => $user->id,
            'resource_id' => $resource->id,
        ])->all()->count();
        $this->assertEquals(2, $resourcesTagsCount);

        // Rename tag 1 with the slug of tag 2
        // This should merge both tags. Only tag 2 remains, tag 1 gets deleted, pivot tables are transferred to tag 2
        $this->putJson("/tags/{$tag1->id}.json?api-version=v2", [
            'slug' => $tag2->slug,
        ]);
        $this->assertSuccess();

        ResourcesTagFactory::firstOrFail([
            'tag_id' => $tag2->id,
            'user_id' => $user->id,
            'resource_id' => $resource->id,
        ]);
        $this->assertSame(1, ResourcesTagFactory::count());
        $this->assertSame(1, TagFactory::count());
        $this->assertSame($tag2->slug, TagFactory::get($tag2->id)->slug);
    }

    /**
     * A user should not be able to update a personal tag to a shared tag
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUpdateUserCanNotUpdateToShareTag()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()->isPersonalFor($resource, $user)->persist();
        $this->putJson("/tags/{$tag->id}.json?api-version=v2", [
            'slug' => '#brave',
        ]);
        $this->assertBadRequestError('You do not have the permission to change a personal tag into shared tag.');
    }

    /**
     * A personal tag update should not affect other users
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUpdatePersonalUpdateDoesNotAffectOthers()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $this->logInAs($userA);
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$userA, $userB])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()->setField('slug', 'alpha')->isPersonalFor($resource, $userA)->persist();
        TagFactory::make()->setField('slug', 'alpha')->isPersonalFor($resource, $userB)->persist();
        $this->putJson("/tags/{$tag->id}.json?api-version=v2", [
            'slug' => 'updated-slug',
        ]);

        // Make sure this doesn't affect other users
        $this->logInAs($userB);
        $this->getJson('/tags.json?api-version=v2');
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertContains('alpha', $results);
        $this->assertNotContains('updated-slug', $results);
        $this->assertSame(2, TagFactory::count());
    }

    /**
     * A personal tag update by an admin should not affect other users
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     * @group admin
     */
    public function testTagUpdatePersonalUpdateByAdminDoesNotAffectOthers()
    {
        $admin = UserFactory::make()->admin()->persist();
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($admin);
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()->setField('slug', 'alpha')->isPersonalFor($resource, $admin)->persist();
        TagFactory::make()->setField('slug', 'alpha')->isPersonalFor($resource, $user)->persist();

        $this->putJson("/tags/{$tag->id}.json?api-version=v2", [
            'slug' => 'updated-slug',
        ]);
        $this->assertResponseSuccess();

        // Make sure this doesn't affect other users
        $this->logInAs($user);
        $this->getJson('/tags.json?api-version=v2');
        $this->assertResponseSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertContains('alpha', $results);
        $this->assertNotContains('updated-slug', $results);
        $this->assertSame(2, TagFactory::count());
    }

    /**
     * After a personal tag update by Admin, the response should contain updated tag
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     * @group admin
     */
    public function testTagAdminPersonalUpdateResponseContainsTag()
    {
        $admin = $this->logInAsAdmin();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$admin])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()->isPersonalFor($resource, $admin)->persist();

        // Update Tag
        $this->putJson("/tags/{$tag->id}.json?api-version=v2", [
            'slug' => 'updated-admin-personal',
        ]);
        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $this->assertEquals('updated-admin-personal', $response->slug);
        $this->assertFalse($response->is_shared);
    }

    /**
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagsUpdateController_RenameSlugWithDifferentCase()
    {
        $user = $this->logInAsUser();
        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag $resourceTag */
        $resourceTag = ResourcesTagFactory::make()
            ->with('Users', $user)
            ->with('Tags', ['slug' => 'test'])
            ->persist();
        ResourcesTagFactory::make()
            ->with('Users')
            ->with('Tags', $resourceTag->tag)
            ->persist();
        $tagId = $resourceTag->tag->get('id');

        $this->putJson("/tags/$tagId.json?api-version=v2", [
            'slug' => 'TEST',
        ]);

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        $this->assertSame('TEST', $responseArray['slug']);
        // Make sure new tag is created if case is different
        $this->assertNotSame($tagId, $responseArray['id']);
        // Make sure two entries are created if slug's case is different
        $this->assertSame(2, TagFactory::find()->where(['UPPER(slug)' => 'TEST'])->count());
    }

    /**
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagsUpdateController_NotVulnerableToSqlInjection()
    {
        $user = $this->logInAsUser();
        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag $resourceTag */
        $resourceTag = ResourcesTagFactory::make()
            ->with('Users', $user)
            ->with('Tags', ['slug' => 'baz'])
            ->persist();
        ResourcesTagFactory::make()
            ->with('Users')
            ->with('Tags', $resourceTag->tag)
            ->persist();
        $tagId = $resourceTag->tag->get('id');

        $this->putJson("/tags/$tagId.json?api-version=v2", [
            'slug' => 'dm\'d', // send slug that can cause SQL injection
        ]);

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        $this->assertSame('dm\'d', $responseArray['slug']);
        $this->assertSame(1, TagFactory::find()->where(['slug' => 'dm\'d'])->count());
    }

    /**
     * Make sure renaming slug with same slug doesn't remove tag for the user.
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagsUpdateController_SameSlugName()
    {
        $user = $this->logInAsUser();
        /** @var \Passbolt\Tags\Model\Entity\ResourcesTag $resourceTag */
        $resourceTag = ResourcesTagFactory::make()
            ->with('Users', $user)
            ->with('Tags', ['slug' => 'foobar'])
            ->persist();
        $tagId = $resourceTag->tag->get('id');

        $this->putJson("/tags/{$tagId}.json?api-version=v2", [
            'slug' => 'foobar',
        ]);

        $this->assertSuccess();
        $responseArray = $this->getResponseBodyAsArray();
        // Make sure new tag is not created
        $this->assertSame($tagId, $responseArray['id']);
        $this->assertSame('foobar', $responseArray['slug']);
        // Assert there is only single entry in the database
        $this->assertSame(1, TagFactory::find()->where(['slug' => 'foobar'])->count());
        $this->assertSame(
            1,
            ResourcesTagFactory::find()->where(['tag_id' => $responseArray['id'], 'user_id' => $user->id])->count()
        );
    }
}
