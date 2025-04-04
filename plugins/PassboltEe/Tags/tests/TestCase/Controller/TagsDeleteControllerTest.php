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
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Utility\Hash;
use Passbolt\Tags\Middleware\TagsReadOnlyModeMiddleware;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class TagsDeleteControllerTest extends TagPluginIntegrationTestCase
{
    /**
     * A user not logged in should not be able to delete tags
     *
     * @group pro
     * @group tag
     * @group tagDelete
     */
    public function testTagsDeleteNotLoggedIn()
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
    public function testTagsDeleteInvalidTagId()
    {
        $this->logInAsUser();
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
    public function testTagsDeleteNonExistingTagId()
    {
        $this->logInAsUser();
        $tagId = UuidFactory::uuid('tag.id.nope');
        $this->deleteJson("/tags/$tagId.json?api-version=v2");
        $this->assertError(404, 'The tag does not exist.');
    }

    public function testTagsDelete_Read_Only_Mode()
    {
        Configure::write(TagsReadOnlyModeMiddleware::PASSBOLT_PLUGINS_TAGS_READ_ONLY_MODE, true);
        $this->authenticateAs('ada');
        $tagId = UuidFactory::uuid();
        $this->deleteJson("/tags/$tagId.json?api-version=v2");
        $this->assertForbiddenError('The tags plugin is in read-only mode.');
    }

    /**
     * A user should not be able to delete a shared tag
     *
     * @group pro
     * @group tag
     * @group tagDelete
     */
    public function testTagsDeleteUserCanNotDeleteSharedTag()
    {
        $user = $this->logInAsUser();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()->isSharedFor($resource)->persist();
        $this->deleteJson("/tags/{$tag->id}.json?api-version=v2");
        $this->assertForbiddenError('You do not have the permission to delete shared tags.');
    }

    /**
     * A user should not be able to delete a tag without providing CSRF token
     *
     * @group pro
     * @group tag
     * @group tagDelete
     */
    public function testTagsDeleteUserCanNotDeleteWithoutCsrfToken()
    {
        $this->disableCsrfToken();

        $user = $this->logInAsUser();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make()->isPersonalFor($resource, $user)->persist();
        $this->delete("/tags/{$tag->id}.json?api-version=v2");
        $this->assertResponseCode(403);
        $result = $this->_getBodyAsString();
        $this->assertStringContainsString('Missing or incorrect CSRF cookie type.', $result);
    }

    /**
     * An admin should be able to delete a personal tag
     *
     * @group pro
     * @group tag
     * @group tagDelete
     * @group admin
     */
    public function testTagsDeleteAdminCanNotDeletePersonalTag()
    {
        // Make sure ada has access to personal tag hotel
        $ada = $this->logInAsUser();
        $resource = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
        $tag = TagFactory::make(['slug' => 'hotel'])->isPersonalFor($resource, $ada)->persist();

        $this->getJson('/tags.json?api-version=v2');
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertContains('hotel', $results);

        // Admin tries to delete it
        $this->logInAsAdmin();
        $this->deleteJson("/tags/{$tag->id}.json?api-version=v2");
        $this->assertError(404, 'The tag does not exist.');

        // Make sure ada still sees the tag in index
        $this->logInAs($ada);
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
    public function testTagsDeleteUserCanDeletePersonalTag()
    {
        $ada = $this->logInAsUser();
        $resource = ResourceFactory::make()->withPermissionsFor([$ada])->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tagToDelete */
        $tagToDelete = TagFactory::make(['slug' => 'hotel'])->isPersonalFor($resource, $ada)->persist();
        /** @var \Passbolt\Tags\Model\Entity\Tag $tagToKeep */
        $tagToKeep = TagFactory::make(['slug' => 'motel'])->isPersonalFor($resource, $ada)->persist();

        $this->deleteJson("/tags/{$tagToDelete->id}.json?api-version=v2");
        $this->assertSuccess();

        // Make sure we do not see the deleted tag in index
        $this->getJson('/tags.json?api-version=v2');
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertNotContains('hotel', $results);
        $this->assertContains('motel', $results);

        $this->assertSame(1, TagFactory::find()->where(['id' => $tagToKeep->id])->all()->count());
        $this->assertSame(1, ResourcesTagFactory::find()->where(['tag_id' => $tagToKeep->id])->all()->count());
        $this->assertSame(0, TagFactory::find()->where(['id' => $tagToDelete->id])->all()->count());
        $this->assertSame(0, ResourcesTagFactory::find()->where(['tag_id' => $tagToDelete->id])->all()->count());
    }
}
