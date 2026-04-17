<?php
declare(strict_types=1);

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

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class TagsResourcesIndexControllerTest extends TagPluginIntegrationTestCase
{
    // Success with currect personal and shared tags for resource with direct and group permissions

    public function testTagsResourcesIndexControllerContainSuccess()
    {
        $user = $this->logInAsUser();
        $group = GroupFactory::make()->withGroupsUsersFor([$user])->persist();
        /** @var \App\Model\Entity\Resource $resourceWithGroupPermission */
        $resourceWithGroupPermission = ResourceFactory::make('apache')
            ->withPermissionsFor([$group], Permission::READ)
            ->persist();
        /** @var \App\Model\Entity\Resource $resourceWithDirectPermission */
        $resourceWithDirectPermission = ResourceFactory::make('april')
            ->withPermissionsFor([$user])
            ->persist();
        TagFactory::make(['slug' => 'alpha'])->isPersonalFor($resourceWithDirectPermission, $user)->persist();
        TagFactory::make(['slug' => '#bravo'])->isSharedFor($resourceWithGroupPermission)->persist();
        TagFactory::make(['slug' => '#charlie'])->isSharedFor($resourceWithDirectPermission)->persist();
        $expected = [
            'apache' => ['#bravo'],
            'april' => ['#charlie', 'alpha',],
        ];

        $this->getJson('/resources.json?api-version=2&contain[tag]=1');
        $this->assertSuccess();

        $resources = (array)json_decode($this->_getBodyAsString())->body;
        foreach ($resources as $resource) {
            $expectedTags = $expected[$resource->name] ?? null;
            if (isset($expectedTags)) {
                $this->assertSame(count($expectedTags), count($resource->tags));
                foreach ($resource->tags as $i => $tag) {
                    $this->assertSame($expectedTags[$i], $tag->slug);
                    $this->assertTrue(is_bool($tag->is_shared));
                    $this->assertTrue(Validation::uuid($tag->id));
                    $this->assertTrue(in_array($tag->slug, $expected[$resource->name]));
                }
            }
        }
    }

    // Success on filter by personal tag without contain

    public function testTagsResourcesIndexControllerFilterSuccess()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resourceWithAlphaTag */
        $resourceWithAlphaTag = ResourceFactory::make('apache')
            ->withPermissionsFor([$user], Permission::READ)
            ->persist();
        /** @var \App\Model\Entity\Resource $resourceWithAlphaTag2 */
        $resourceWithAlphaTag2 = ResourceFactory::make('april')
            ->withPermissionsFor([$user])
            ->persist();
        // Resource without alpha tag
        ResourceFactory::make('chai')->withPermissionsFor([$user])->persist();

        TagFactory::make(['slug' => 'alpha'])
            ->isPersonalFor($resourceWithAlphaTag, $user)
            ->isPersonalFor($resourceWithAlphaTag2, $user)
            ->persist();

        $this->getJson('/resources.json?api-version=2&filter[has-tag]=alpha');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $resources = Hash::extract($response->body, '{n}.name');
        $expected = ['apache', 'april'];
        $this->assertEquals($expected, $resources);

        // Tags data should not be set
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertTrue(!isset($response['body'][0]['tags']));
    }

    // Success on filter by personal tag without contain on a tag used by someone else

    public function testTagsResourcesIndexControllerFilterSuccessPersonalTagUsedBySomeoneElse()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $this->logInAs($userB);

        /** @var \App\Model\Entity\Resource $resourceWithAlphaTag */
        $resourceWithAlphaTag = ResourceFactory::make('apache')
            ->withPermissionsFor([$userA], Permission::READ)
            ->persist();
        /** @var \App\Model\Entity\Resource $resourceWithAlphaTag2 */
        $resourceWithAlphaTag2 = ResourceFactory::make('chai')
            ->withPermissionsFor([$userB])
            ->persist();
        // Resource without alpha tag
        ResourceFactory::make('chai')->withPermissionsFor([$userB])->persist();

        TagFactory::make(['slug' => 'alpha'])->isPersonalFor($resourceWithAlphaTag, $userA)->persist();
        TagFactory::make(['slug' => 'alpha'])->isPersonalFor($resourceWithAlphaTag2, $userB)->persist();

        $this->getJson('/resources.json?api-version=2&filter[has-tag]=alpha');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $resources = Hash::extract($response->body, '{n}.name');
        $expected = ['chai'];
        $this->assertEquals($resources, $expected);
    }

    // Success on filter by personal tag with contain

    public function testTagsResourcesIndexControllerFilterContainSuccess()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resourceWithAlphaTag */
        $resourceWithAlphaTag = ResourceFactory::make('apache')
            ->withPermissionsFor([$user], Permission::READ)
            ->persist();
        /** @var \App\Model\Entity\Resource $resourceWithAlphaTag2 */
        $resourceWithAlphaTag2 = ResourceFactory::make('april')
            ->withPermissionsFor([$user])
            ->persist();
        // Resource without alpha tag
        ResourceFactory::make('chai')->withPermissionsFor([$user])->persist();

        TagFactory::make(['slug' => 'alpha'])
            ->isPersonalFor($resourceWithAlphaTag, $user)
            ->isPersonalFor($resourceWithAlphaTag2, $user)
            ->persist();

        $this->getJson('/resources.json?api-version=2&contain[tag]=1&filter[has-tag]=alpha');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $resources = Hash::extract($response->body, '{n}.name');
        $expected = ['apache', 'april',];
        $this->assertEquals($resources, $expected);

        // Tags data should be set
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertTrue(isset($response['body'][0]['tags']));
    }

    // Success on filter by shared tag with contain

    public function testTagsResourcesIndexControllerFilterSharedTagSuccess()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resourceWithBravoTag */
        $resourceWithBravoTag = ResourceFactory::make('apache')
            ->withPermissionsFor([$user], Permission::READ)
            ->persist();
        /** @var \App\Model\Entity\Resource $resourceWithBravoTag2 */
        $resourceWithBravoTag2 = ResourceFactory::make('april')
            ->withPermissionsFor([$user])
            ->persist();
        // Resource without alpha tag
        ResourceFactory::make('chai')->withPermissionsFor([$user])->persist();

        TagFactory::make(['slug' => '#bravo'])
            ->isPersonalFor($resourceWithBravoTag, $user)
            ->isPersonalFor($resourceWithBravoTag2, $user)
            ->persist();

        $this->getJson('/resources.json?api-version=2&contain[tag]=1&filter[has-tag]=%23bravo');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $resources = Hash::extract($response->body, '{n}.name');
        $expected = ['apache', 'april'];
        $this->assertEquals($resources, $expected);

        // Tags data should be set
        $response = json_decode($this->_getBodyAsString(), true);
        $this->assertTrue(isset($response['body'][0]['tags']));
    }

    // Success with empty result set is returned when filtering on a tag that does not exist

    public function testTagsResourcesIndexControllerFilterNonExistingTagEmptySuccess()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resourceWithTag */
        $resourceWithTag = ResourceFactory::make()
            ->withPermissionsFor([$user], Permission::READ)
            ->persist();
        TagFactory::make(['slug' => 'alpha'])->isPersonalFor($resourceWithTag, $user)->persist();

        $this->getJson('/resources.json?api-version=2&filter[has-tag]=परदेशीपरदेशी');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $this->assertEmpty($response->body);
    }

    // Success with tag in non latin character

    public function testTagsResourcesIndexControllerFilterExistingUtf8TagSuccess()
    {
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resourceWithTag */
        $resourceWithTag = ResourceFactory::make()
            ->withPermissionsFor([$user], Permission::READ)
            ->persist();
        TagFactory::make(['slug' => 'परदेशी-परदेशी'])->isPersonalFor($resourceWithTag, $user)->persist();

        $this->getJson('/resources.json?api-version=2&filter[has-tag]=परदेशी-परदेशी');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $this->assertNotEmpty($response->body);
    }

    // Success with empty result set is returned when filtering on a tag I do not have resource for

    public function testTagsResourcesIndexControllerFilterNotMyTagEmptySuccess()
    {
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        $this->logInAs($userB);

        /** @var \App\Model\Entity\Resource $resourceWithAlphaTag */
        $resourceWithAlphaTag = ResourceFactory::make('apache')
            ->withPermissionsFor([$userA], Permission::READ)
            ->persist();
        /** @var \App\Model\Entity\Resource $resourceWithBetaTag */
        $resourceWithBetaTag = ResourceFactory::make('chai')
            ->withPermissionsFor([$userB])
            ->persist();

        TagFactory::make(['slug' => 'alpha'])->isPersonalFor($resourceWithAlphaTag, $userA)->persist();
        TagFactory::make(['slug' => 'beta'])->isPersonalFor($resourceWithBetaTag, $userB)->persist();

        $this->getJson('/resources.json?api-version=2&filter[has-tag]=alpha');
        $this->assertSuccess();
        $response = json_decode($this->_getBodyAsString());
        $this->assertEmpty($response->body);
    }

    // An error message should be shown if the value in the tag filter is empty

    public function testTagsResourcesIndexControllerFilterEmptyError()
    {
        $this->logInAsUser();
        $this->getJson('/resources.json?api-version=2&filter[has-tag]=&contain[tag]=');
        $this->assertError(400);
        $response = json_decode($this->_getBodyAsString());
        $this->assertStringContainsString('Invalid filter.', $response->header->message);
    }

    // An error message should be shown if the tag in the tag filter is too long
    public function testTagsResourcesIndexControllerFilterTooLongError()
    {
        $this->logInAsUser();
        $tag = bin2hex(openssl_random_pseudo_bytes(256));
        $this->getJson('/resources.json?api-version=2&filter[has-tag]=&contain[tag]=' . $tag);
        $this->assertError(400);
        $response = json_decode($this->_getBodyAsString());
        $this->assertStringContainsString('Invalid filter.', $response->header->message);
    }
}
