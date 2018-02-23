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

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class ResourcesTagsAddControllerTest extends AppIntegrationTestCase
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
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', []);
        $this->assertError(404);
    }

    // A "not found" error is returned if the user does not have read access on the resource
    public function testResourcesTagsAddNoResourcePermissionError()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2');
        $this->assertError(404);
    }

    // A user can add personal tags on a resource with read access
    public function testResourcesTagsAddReadPermissionPersonalTagSuccess()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', ['tag1', '🤔']);
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
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', ['#tag1']);
        $this->assertError(400);
    }

    // A user can not add shared tags on a resource with read access
    public function testResourcesTagValidationError()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $tag = bin2hex(openssl_random_pseudo_bytes(256));
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', [$tag]);
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
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', ['#bravo', 'flip', '#stup']);
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
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', ['#bravo', 'stup', 'flip']);
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
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', ['#golf', 'stup', 'flip']);
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
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', []);
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
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', []);
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
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', []);
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
}
