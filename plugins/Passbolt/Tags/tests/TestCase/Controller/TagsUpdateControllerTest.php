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

use App\Utility\UuidFactory;
use Cake\Utility\Hash;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class TagsUpdateControllerTest extends TagPluginIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Roles', 'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Favorites',
        'app.Base/Profiles', 'app.Base/Groups', 'app.Alt0/GroupsUsers', 'app.Alt0/Permissions',
        'plugin.Passbolt/Tags.Base/Tags', 'plugin.Passbolt/Tags.Alt0/ResourcesTags',
        'app.Base/Groups', 'app.Base/Avatars', 'app.Base/Favorites', 'app.Base/EmailQueue'
    ];

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
            'slug' => str_repeat('a', 129)
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
            'slug' => 'brave'
        ]);
        $this->assertResponseCode(403);
        $result = ($this->_getBodyAsString());
        $this->assertContains('Missing CSRF token cookie', $result);
    }

    /**
     * A tag with too long slug should not be saved
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUpdateSharedTagSlugTooLong()
    {
        $this->authenticateAs('admin');
        $tagId = UuidFactory::uuid('tag.id.#bravo');
        $this->putJson("/tags/$tagId.json?api-version=v2", [
            'slug' => '#' . str_repeat('a', 128)
        ]);
        $this->assertBadRequestError('Could not validate tag data.');
        $response = json_decode(json_encode($this->_responseJsonBody), true);
        $this->assertTrue(Hash::check($response, 'slug.maxLength'));
        $this->assertEquals('Tag can not be more than 128 characters in length.', Hash::get($response, 'slug.maxLength'));
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
                    'slug' => '新的專用資源名稱'
                ]
            ],
            'slavic' => [
                'user' => 'ada',
                'tag' => 'tag.id.firefox',
                'data' => [
                    'slug' => 'Новое имя частного ресурса'
                ]
            ],
            'french' => [
                'user' => 'betty',
                'tag' => 'tag.id.alpha',
                'data' => [
                    'slug' => 'Nouveau nom de resource privée'
                ]
            ],
            'emoticon' => [
                'user' => 'ada',
                'tag' => 'tag.id.hindi',
                'data' => [
                    'slug' => "\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}"
                ]
            ]
        ];

        foreach ($success as $test => $case) {
            $this->authenticateAs($case['user']);
            $tagId = UuidFactory::uuid($case['tag']);
            $this->putJson("/tags/$tagId.json?api-version=v2", $case['data']);
            $this->assertEquals('success', $this->_responseJsonHeader->status, $test);
        }
    }

    /**
     * An admin should be able to use unicode text in a tag
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     */
    public function testTagUpdateSharedTagSupportsUnicode()
    {
        $success = [
            'chinese' => [
                'slug' => '#新的專用資源名稱'
            ],
            'slavic' => [
                'slug' => '#Новое имя частного ресурса'
            ],
            'french' => [
                'slug' => '#Nouveau nom de resource privée'
            ],
            'emoticon' => [
                'slug' => "#\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}"
            ]
        ];

        $this->authenticateAs('admin');
        $tagId = UuidFactory::uuid('tag.id.#bravo');

        foreach ($success as $case => $data) {
            $this->putJson("/tags/$tagId.json?api-version=v2", $data);
            $this->assertSuccess();
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
            'slug' => 'test slug'
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
            'slug' => 'brave'
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
            'slug' => '#brave'
        ]);
        $this->assertBadRequestError('You do not have the permission to change a personal tag into shared tag.');
    }

    /**
     * An admin should be able to update a personal tag to a shared tag
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     * @group admin
     */
    public function testTagUpdateAdminCanUpdatePersonalTag()
    {
        $this->authenticateAs('admin');
        $resourceId = $this->_addTestResource($this->_getDummyResourceData());
        $tagId = $this->_addTestTag($resourceId, ['admin-personal'])[0]->id;

        // Update Tag
        $this->putJson("/tags/$tagId.json?api-version=v2", [
            'slug' => 'updated-admin-personal'
        ]);

        // Make sure we do not see the old tag in index
        $this->getJson('/tags.json?api-version=v2');
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertNotContains('admin-personal', $results);

        // And see the new tag
        $this->assertContains('updated-admin-personal', $results);
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
            'slug' => 'updated-slug'
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
            'slug' => 'updated-slug'
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
     * An Admin should be able to update a shared tag
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     * @group admin
     */
    public function testTagUpdateAdminCanUpdateSharedTag()
    {
        $this->authenticateAs('admin');
        $tagId = UuidFactory::uuid('tag.id.#bravo');
        $this->putJson("/tags/$tagId.json?api-version=v2", [
            'slug' => '#update-bravo'
        ]);
        $this->assertSuccess();
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
        $resourceId = $this->_addTestResource($this->_getDummyResourceData());
        $tagId = $this->_addTestTag($resourceId, ['admin-personal'])[0]->id;

        // Update Tag
        $this->putJson("/tags/$tagId.json?api-version=v2", [
            'slug' => 'updated-admin-personal'
        ]);
        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $this->assertEquals('updated-admin-personal', $response->slug);
        $this->assertFalse($response->is_shared);
    }

    /**
     * After a shared tag update by Admin, the response should contain updated tag
     *
     * @group pro
     * @group tag
     * @group TagUpdate
     * @group admin
     */
    public function testTagAdminSharedUpdateResponseContainsTag()
    {
        $this->authenticateAs('admin');
        $tagId = UuidFactory::uuid('tag.id.#bravo');
        $this->putJson("/tags/$tagId.json?api-version=v2", [
            'slug' => '#update-bravo'
        ]);
        $this->assertSuccess();
        $response = $this->_responseJsonBody;
        $this->assertEquals('#update-bravo', $response->slug);
        $this->assertTrue($response->is_shared);
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

        return $this->_responseJsonBody->id;
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

    protected function _getDummyResourceData()
    {
        return [
            'name' => 'test',
            'secrets' => [
                [
                    'user_id' => UuidFactory::uuid('user.id.admin'),
                    'data' => '-----BEGIN PGP MESSAGE-----
Version: OpenPGP.js v4.5.1
Comment: https://openpgpjs.org

wcFMA1P90Qk1JHA+ARAAoO5thhi4EIlQWPHhoSbC7ZkqcUfTvdhaLsPDDs8z
/27WkSxaa9XZllJUojz12fOqgX6vAd1Osbf6ccvqA/MdUPU3qZ35YbXstvJX
hntbh/FWjexUdwz41rSe8pUwVRu+C1efPUoOpGkdghyLnrGnIPxvW1Z1ZKQh
IMs9YxCaDY0BPL2xQ0t6f7srF1Vn1ZhutK6FHNNEJrs7RH6JRaSKfG0AVWEd
FG2+EB7qY+gt/63vJwTT2ara+QNGpUSezHvmBgM7WXTfQgYLJWuMi34lqWzv
nUQWY0ooPMLFlzuECu+H64f1okHVpmTFrntRd9yGwZrg601C/WAJ8yYGWR3n
5bAFbtl+IIhmtr9yXvNxVzj4h0KD+hEuQiy0mboucFapDsFpkOjsx5Qta0EB
VXPvfGs4w+DXspT7Kejjz3xzB3OD2ywDNxH+Mu7OHrOqz0rfVnVCROTwc30a
ENruqr1CuGp1TYlwXQPVXtZyCEauOCWlW+TpSinq7+asNBJ9EIWdy/hNOUVV
8pd8ku4RqW4lmRJXtQWeqmNfXVuNvRr+BONIj7A2qdnNr8J5/PWPs6km4xop
TGLcOP8lBGYT89Oal230qmtm7bGb92iVwGgw5m/1/q+Ho7eBZ+sM2IWEDPbZ
Yqcb+ONCV6wgGlnvMntZD4Aiu4JXy8TJsYtPKNEhNjnSQAFdTDXzg7Cw8ypU
/uIyuaZnKUWjnVtQAI1bEhlZ1YV8LU0MoZPEWsSy2CHHDuSE4uNFFtb7QPkS
NPzwk8OlB8c=
=YH4T
-----END PGP MESSAGE-----
']
            ],
        ];
    }
}
