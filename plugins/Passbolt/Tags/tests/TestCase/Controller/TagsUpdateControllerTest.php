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
 * @since         2.11.0
 */
namespace Passbolt\Tags\Test\TestCase\Controller;

use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Tags\Model\Table\ResourcesTagsTable;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class TagsUpdateControllerTest extends TagPluginIntegrationTestCase
{
    public $fixtures = [
        'app.Base/OrganizationSettings',
        'app.Base/Users', 'app.Base/Roles', 'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Favorites',
        'app.Base/Profiles', 'app.Base/Groups', 'app.Alt0/GroupsUsers', 'app.Alt0/Permissions',
        'plugin.Passbolt/Tags.Base/Tags', 'plugin.Passbolt/Tags.Alt0/ResourcesTags',
        'app.Base/Groups', 'app.Base/Avatars', 'app.Base/Favorites', 'app.Base/EmailQueue',
    ];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Passbolt/Tags.ResourcesTags') ? [] : ['className' => ResourcesTagsTable::class];
        $this->ResourcesTags = TableRegistry::getTableLocator()->get('Passbolt/Tags.ResourcesTags', $config);
    }

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
        $this->authenticateAs('ada');
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
        $this->authenticateAs('ada');
        $tagId = UuidFactory::uuid('tag.id.nope');
        $this->putJson("/tags/$tagId.json?api-version=v2");
        $this->assertError(404, 'The tag does not exist.');
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
        $this->authenticateAs('betty');
        $tagId = UuidFactory::uuid('tag.id.#bravo');
        $this->putJson("/tags/$tagId.json?api-version=v2");
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
        $this->authenticateAs('ada');
        $tagId = UuidFactory::uuid('tag.id.firefox');
        $this->putJson("/tags/$tagId.json?api-version=v2", [
            'slug' => str_repeat('a', 129),
        ]);
        $this->assertBadRequestError('Could not validate tag data.');
        $response = json_decode(json_encode($this->_responseJsonBody), true);
        $this->assertTrue(Hash::check($response, 'slug.maxLength'));
        $this->assertEquals('Tag can not be more than 128 characters in length.', Hash::get($response, 'slug.maxLength'));
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

        $this->authenticateAs('ada');
        $tagId = UuidFactory::uuid('tag.id.firefox');
        $this->put("/tags/$tagId.json?api-version=v2", [
            'slug' => 'brave',
        ]);
        $this->assertResponseCode(403);
        $result = ($this->_getBodyAsString());
        $this->assertContains('Missing CSRF token cookie', $result);
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

        foreach ($success as $test => $case) {
            $this->authenticateAs($case['user']);
            $tagId = UuidFactory::uuid($case['tag']);
            $this->putJson("/tags/$tagId.json?api-version=v2", $case['data']);
            $this->assertEquals('success', $this->_responseJsonHeader->status, $test);
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
        $this->authenticateAs('ada');
        $tagId = UuidFactory::uuid('tag.id.alpha');
        $this->putJson("/tags/$tagId.json?api-version=v2", [
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
        $this->authenticateAs('ada');
        $tagId = UuidFactory::uuid('tag.id.firefox');
        $this->putJson("/tags/$tagId.json?api-version=v2", [
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
        $this->authenticateAs('ada');
        $resourceData = ResourcesModelTrait::getDummyResourceData();
        $resourceData['secrets'][0] = SecretsModelTrait::getDummySecretData();
        $resource = $this->_addTestResource($resourceData);
        $tags = $this->_addTestTag($resource->id, ['test-tag-1', 'test-tag-2']);
        $this->assertCount(2, $tags);

        $resourcesTagsCount = $this->ResourcesTags->find()->where([
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'resource_id' => $resource->id,
        ])->count();
        $this->assertEquals(2, $resourcesTagsCount);

        $this->putJson("/tags/{$tags[0]->id}.json?api-version=v2", [
            'slug' => $tags[1]->slug,
        ]);
        $this->assertSuccess();

        $resourcesTags = $this->ResourcesTags->find()->where([
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'resource_id' => $resource->id,
        ])->all()->toArray();
        $this->assertCount(1, $resourcesTags);
        $this->assertEquals($tags[1]->id, $resourcesTags[0]->tag_id);
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
        $this->authenticateAs('ada');
        $tagId = UuidFactory::uuid('tag.id.firefox');
        $this->putJson("/tags/$tagId.json?api-version=v2", [
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
        $this->authenticateAs('ada');
        $tagId = UuidFactory::uuid('tag.id.alpha');
        $this->putJson("/tags/$tagId.json?api-version=v2", [
            'slug' => 'updated-slug',
        ]);

        // Make sure this doesn't affect other users
        $this->authenticateAs('betty');
        $this->getJson('/tags.json?api-version=v2');
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertContains('alpha', $results);
        $this->assertNotContains('updated-slug', $results);
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
        $this->authenticateAs('admin');
        $tagId = UuidFactory::uuid('tag.id.alpha');
        $this->putJson("/tags/$tagId.json?api-version=v2", [
            'slug' => 'updated-slug',
        ]);

        // Make sure this doesn't affect other users
        $this->authenticateAs('betty');
        $this->getJson('/tags.json?api-version=v2');
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertContains('alpha', $results);
        $this->assertNotContains('updated-slug', $results);
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
        $this->authenticateAs('admin');
        $resourceData = ResourcesModelTrait::getDummyResourceData();
        $resourceData['secrets'][0] = SecretsModelTrait::getDummySecretData();
        $resource = $this->_addTestResource($resourceData);
        $tags = $this->_addTestTag($resource->id, ['admin-personal']);
        $tag = $tags[0];

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
     * Add a test resource
     *
     * @param array $data resource data
     * @return mixed ID of the new resource
     */
    protected function _addTestResource(array $data = [])
    {
        $this->postJson("/resources.json?api-version=v2", $data);

        return $this->_responseJsonBody;
    }

    /**
     * Add a test tag
     *
     * @param string $resourceId ID of the resource where tag is to be added
     * @param array $tags List of tags to be added
     * @return Object List of tags
     */
    protected function _addTestTag(string $resourceId, array $tags = [])
    {
        $data = ['Tags' => $tags];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);

        return $this->_responseJsonBody;
    }
}
