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

    public function testResourcesTagsAddResourceDoesNotExistError()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.nope');
        ;
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', []);
        $this->assertError(404);
    }

    public function testResourcesTagsAddNoResourcePermissionError()
    {
        $this->authenticateAs('dame');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        ;
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2');
        $this->assertError(404);
    }

    public function testResourcesTagsAddSuccess()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        ;
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', ['#bravo', 'biloute', '#stup']);
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertEquals($results, ['#bravo', '#stup', 'biloute']);
    }

    public function testResourcesTagsAddSuccessDelete()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        ;
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', []);
        $response = json_decode($this->_getBodyAsString());
        $results = Hash::extract($response->body, '{n}.slug');
        $this->assertEquals($results, []);
    }

    public function testResourcesTagsAddSuccessDeleteKeepsOtherPeoplePersonalTags()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.chai');
        ;
        $this->postJson('/tags/' . $resourceId . '.json?api-version=2', []);
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
