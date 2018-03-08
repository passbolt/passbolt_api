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

use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class ResourceIndexControllerTest extends TagPluginIntegrationTestCase
{
    public $fixtures = [
        'app.Base/users', 'app.Base/roles', 'app.Base/resources', 'app.Base/groups',
        'app.Alt0/groups_users', 'app.Alt0/permissions',
        'plugin.passbolt/tags.Base/tags', 'plugin.passbolt/tags.Alt0/resourcesTags'];

    // Success with currect personal and shared tags for resource with direct and group permissions
    public function testTagResourcesIndexContainSuccess()
    {
        $this->authenticateAs('ada');
        $expected = [
            'apache' => ['alpha', '#echo', '#bravo', 'fox-trot'],
            'april' => ['alpha', '#bravo'],
            'bower' => [],
            'cakephp' => ['#charlie'],
            'chai' => ['alpha', 'hotel'],
            'grogle' => ['#golf', 'firefox']
        ];

        $this->getJson('/resources.json?api-version=2&contain[tag]=1');
        $this->assertSuccess();

        $response = json_decode($this->_getBodyAsString());
        foreach ($response->body as $i => $resource) {
            if (isset($expected[$resource->name])) {
                foreach ($resource->tags as $j => $tag) {
                    $this->assertTrue(is_bool($tag->is_shared));
                    $this->assertTrue(Validation::uuid($tag->id));
                    $this->assertTrue(in_array($tag->slug, $expected[$resource->name]));
                }
            }
        }
    }

    // Success on filter by personal tag without contain
    public function testTagResourcesIndexFilterSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?api-version=2&filter[has-tag]=alpha');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $resources = Hash::extract($response->body, "{n}.name");
        $expected = ['apache', 'april', 'chai'];
        $this->assertEquals($resources, $expected);

        // Tags data should not be set
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertTrue(!isset($response['body'][0]['tags']));
    }

    // Success on filter by personal tag without contain on a tag used by someone else
    public function testTagResourcesIndexFilterSuccessPersonalTagUsedBySomeoneElse()
    {
        $this->authenticateAs('betty');
        $this->getJson('/resources.json?api-version=2&filter[has-tag]=alpha');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $resources = Hash::extract($response->body, "{n}.name");
        $expected = ['chai'];
        $this->assertEquals($resources, $expected);
    }

    // Success on filter by personal tag with contain
    public function testTagResourcesIndexFilterContainSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?api-version=2&contain[tag]=1&filter[has-tag]=alpha');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $resources = Hash::extract($response->body, "{n}.name");
        $expected = ['apache', 'april', 'chai'];
        $this->assertEquals($resources, $expected);

        // Tags data should be set
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertTrue(isset($response['body'][0]['tags']));
    }

    // Success on filter by shared tag with contain
    public function testTagResourcesIndexFilterSharedTagSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?api-version=2&contain[tag]=1&filter[has-tag]=%23bravo');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $resources = Hash::extract($response->body, "{n}.name");
        $expected = ['apache', 'april'];
        $this->assertEquals($resources, $expected);
    }

    // Success with empty result set is returned when filtering on a tag that does not exist
    public function testTagResourcesIndexFilterNonExistingTagEmptySuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?api-version=2&filter[has-tag]=परदेशीपरदेशी');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $this->assertEmpty($response->body);
    }

    // Success with tag in non latin character
    public function testTagResourcesIndexFilterExistingUtf8TagSuccess()
    {
        $this->authenticateAs('ada');
        $this->getJson('/resources.json?api-version=2&filter[has-tag]=परदेशी-परदेशी');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $this->assertNotEmpty($response->body);
    }

    // Success with empty result set is returned when filtering on a tag I do not have resource for
    public function testTagResourcesIndexFilterNotMyTagEmptySuccess()
    {
        $this->authenticateAs('betty');
        $this->getJson('/resources.json?api-version=2&filter[has-tag]=fox-trot');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $this->assertEmpty($response->body);
    }

    // An error message should be shown if the value in the tag filter is empty
    public function testTagResourcesIndexFilterEmptyError()
    {
        $this->authenticateAs('betty');
        $this->getJson('/resources.json?api-version=2&filter[has-tag]=&contain[tag]=');
        $this->assertError(400);
        $response = json_decode($this->_getBodyAsString());
        $this->assertContains('Invalid filter.', $response->header->message);
    }

    // An error message should be shown if the tag in the tag filter is too long
    public function testTagResourcesIndexFilterTooLongError()
    {
        $this->authenticateAs('betty');
        $tag = bin2hex(openssl_random_pseudo_bytes(256));
        $this->getJson('/resources.json?api-version=2&filter[has-tag]=&contain[tag]=' . $tag);
        $this->assertError(400);
        $response = json_decode($this->_getBodyAsString());
        $this->assertContains('Invalid filter.', $response->header->message);
    }
}
