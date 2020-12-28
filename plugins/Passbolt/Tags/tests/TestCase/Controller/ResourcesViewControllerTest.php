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
 * @since         2.12.0
 */
namespace Passbolt\Tags\Test\TestCase\Controller;

use App\Utility\UuidFactory;
use Cake\Validation\Validation;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class ResourcesViewControllerTest extends TagPluginIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Roles', 'app.Base/Resources', 'app.Base/Groups',
        'app.Alt0/GroupsUsers', 'app.Alt0/Permissions',
        'plugin.Passbolt/Tags.Base/Tags', 'plugin.Passbolt/Tags.Alt0/ResourcesTags',
    ];

    public function testTagsResourcesViewContainSuccess()
    {
        $this->authenticateAs('ada');
        $expected = ['alpha', '#echo', '#bravo', 'fox-trot'];
        $id = UuidFactory::uuid('resource.id.apache');
        $this->getJson("/resources/{$id}.json?api-version=2&contain[tag]=1");
        $this->assertSuccess();

        $response = json_decode($this->_getBodyAsString());
        $resource = $response->body;
        foreach ($resource->tags as $tag) {
            $this->assertTrue(is_bool($tag->is_shared));
            $this->assertTrue(Validation::uuid($tag->id));
            $this->assertContains($tag->slug, $expected);
        }
    }
}
