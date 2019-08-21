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

class TagsDeleteControllerTest extends TagPluginIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Roles', 'app.Base/Resources', 'app.Base/Groups',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions',
        'plugin.Passbolt/Tags.Base/Tags', 'plugin.Passbolt/Tags.Alt0/ResourcesTags'
    ];

    /**
     * A user not logged in should not be able to delete tags
     *
     * @group pro
     * @group tag
     * @group tagDelete
     */
    public function testTagDeleteNotLoggedIn()
    {
        $this->deleteJson('/tags/0507cbbb-eb14-5121-9105-05380dbe64ff.json?api-version=v2');
        $this->assertAuthenticationError();
    }

    /**
     * Request with and invalid uuid should fail and give error
     *
     * @group pro
     * @group tag
     * @group tagDelete
     */
    public function testTagDeleteInvalidTagId()
    {
        $this->authenticateAs('ada');
        $this->deleteJson('/tags/invalid-tag-id.json?api-version=v2');
        $this->assertBadRequestError('The tag id is not valid.');
    }

    /**
     * Request with and non existing tagId should fail and give error
     *
     * @group pro
     * @group tag
     * @group tagDelete
     */
    public function testTagDeleteNonExistingTagId()
    {
        $this->authenticateAs('ada');
        $tagId = UuidFactory::uuid('tag.id.nope');
        $this->deleteJson("/tags/$tagId.json?api-version=v2");
        $this->assertError(404, 'The tag does not exist.');
    }

    /**
     * A user should not be able to delete a shared tag
     *
     * @group pro
     * @group tag
     * @group tagDelete
     */
    public function testTagDeleteUserCanNotDeleteSharedTag()
    {
        $this->authenticateAs('ada');
        $tagId = UuidFactory::uuid('tag.id.#bravo');
        $this->deleteJson("/tags/$tagId.json?api-version=v2");
        $this->assertForbiddenError('You do not have the permission to delete shared tags.');
    }

    /**
     * A user should not be able to delete a tag without providing CSRF token
     *
     * @group pro
     * @group tag
     * @group tagDelete
     */
    public function testTagDeleteUserCanNotDeleteWithoutCsrfToken()
    {
        $this->disableCsrfToken();

        $this->authenticateAs('ada');
        $tagId = UuidFactory::uuid('tag.id.hotel');
        $this->delete("/tags/$tagId.json?api-version=v2");
        $this->assertResponseCode(403);
        $result = ($this->_getBodyAsString());
        $this->assertContains('Missing CSRF token cookie', $result);
    }

    /**
     * An admin should be able to delete a personal tag
     *
     * @group pro
     * @group tag
     * @group tagDelete
     * @group admin
     */
    public function testTagDeleteAdminCanNotDeletePersonalTag()
    {
        // Make sure ada has access to personal tag hotel
        $this->authenticateAs('ada');
        $this->getJson('/tags.json?api-version=v2');
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertContains('hotel', $results);

        // Admin tries to delete it
        $this->authenticateAs('admin');
        $tagId = UuidFactory::uuid('tag.id.hotel');
        $this->deleteJson("/tags/$tagId.json?api-version=v2");
        $this->assertError(404, 'The tag does not exist.');

        // Make sure ada still sees the tag in index
        $this->authenticateAs('ada');
        $this->getJson('/tags.json?api-version=v2');
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertContains('hotel', $results);
    }

    /**
     * A user should be able to delete a personal tag
     *
     * @group pro
     * @group tag
     * @group tagDelete
     */
    public function testTagDeleteUserCanDeletePersonalTag()
    {
        $this->authenticateAs('ada');
        $tagId = UuidFactory::uuid('tag.id.fox-trot');
        $this->deleteJson("/tags/$tagId.json?api-version=v2");
        $this->assertSuccess();

        // Make sure we do not see the deleted tag in index
        $this->getJson('/tags.json?api-version=v2');
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertNotContains('fox-trot', $results);
    }
}
