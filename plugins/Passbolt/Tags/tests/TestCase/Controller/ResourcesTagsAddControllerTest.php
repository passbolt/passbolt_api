<?php
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

use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class ResourcesTagsAddControllerTest extends TagPluginIntegrationTestCase
{
    public $fixtures = [
        'app.Base/users', 'app.Base/roles', 'app.Base/resources', 'app.Base/groups',
        'app.Alt0/groups_users', 'app.Alt0/permissions',
        'plugin.passbolt/tags.Base/tags', 'plugin.passbolt/tags.Alt0/resourcesTags'];

    // A "not found" error is returned if the resource does not exist
    public function testResourcesTagsAddResourceDoesNotExistError()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.nope');
        $data = ['Tags' => []];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $response = json_decode($this->_getBodyAsString());
        $this->assertError(404);
    }

    // A "not found" error is returned if the user does not have read access on the resource
    public function testResourcesTagsAddNoResourcePermissionError()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $data = ['Tags' => []];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $this->assertError(404);
    }

    // A user can add personal tags on a resource with read access
    public function testResourcesTagsAddReadPermissionPersonalTagSuccess()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $data = ['Tags' => ['tag1', '🤔']];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertEquals($results, ['tag1', '🤔']);
    }

    // A user can not add shared tags on a resource with read access
    public function testResourcesTagsAddReadPermissionSharedTagError()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.bower');

        $data = ['Tags' => ['#tag1']];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $this->assertError(400);
    }

    // A user can not add shared tags on a resource with read access
    public function testResourcesTagValidationError()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $data = ['Tags' => [bin2hex(openssl_random_pseudo_bytes(256))]];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $this->assertError(400);
        $response = json_decode($this->_getBodyAsString());
        $msg = 'Tag can not be more than 128 characters in length.';
        $this->assertEquals($response->body[0]->slug->maxLength, $msg);
    }

    // A user can add shared and personal tags on a resource it owns via direct permission
    public function testResourcesTagsAddSuccess()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $data = ['Tags' => ['#bravo', 'flip', '#stup']];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertEquals($results, ['#bravo', '#stup', 'flip']);
    }

    // A user can add shared and personal tags on a resource it owns via group permission
    public function testResourcesTagsAddSuccessGroupOwnership()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.kde');
        $data = ['Tags' => ['#bravo', 'stup', 'flip']];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $response = json_decode($this->_getBodyAsString());
        $this->assertSuccess();
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertEquals($results, ['#bravo', 'flip', 'stup']);
    }

    // A user can add personal tags on a resource it can read via group permission
    public function testResourcesTagsAddSuccessGroupRead()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.grogle');
        $data = ['Tags' => ['#golf', 'stup', 'flip']];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $response = json_decode($this->_getBodyAsString());
        $this->assertSuccess();
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertEquals($results, ['#golf', 'flip', 'stup']);
    }

    // A user can delete shared and personal tags on a resource it owns via direct permission
    public function testResourcesTagsAddSuccessDelete()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $data = ['Tags' => []];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertEquals($results, []);
    }

    // A user can delete shared and personal tags on a resource it owns via group permission
    public function testResourcesTagsAddSuccessDeleteGroupOwnership()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $data = ['Tags' => []];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertEquals($results, []);
    }

    // A user deleting personal tags on a resource should not delete other users personal tags
    public function testResourcesTagsAddSuccessDeleteKeepsOtherPeoplePersonalTags()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.chai');
        $data = ['Tags' => []];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertEquals($results, []);

        $ResourcesTags = TableRegistry::get('Passbolt/Tags.ResourcesTags');
        $rt = $ResourcesTags->query()
            ->where([
                'resource_id' => $resourceId,
                'user_id' => UuidFactory::uuid('user.id.betty')
            ])
            ->all();

        $this->assertNotEmpty($rt);
    }

    // Unused tags should be deleted
    public function testResourcesTagsCleanupSuccess()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $data = ['Tags' => []];
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', $data);
        $this->assertSuccess();

        // Check tag cleanup
        // #bravo and alpha should still be there
        $this->Tags = TableRegistry::get('Passbolt/Tags.Tags');
        $this->Tags->get(UuidFactory::uuid('tag.id.#bravo'));
        $this->Tags->get(UuidFactory::uuid('tag.id.alpha'));

        // Fox-trot should have been deleted (not in used anymore)
        $this->expectException(RecordNotFoundException::class);
        $this->Tags->get(UuidFactory::uuid('tag.id.fox-trot'));
    }
}
