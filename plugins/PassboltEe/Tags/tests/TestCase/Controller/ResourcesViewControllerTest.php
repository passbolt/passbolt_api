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

use App\Test\Factory\ResourceFactory;
use Cake\Validation\Validation;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class ResourcesViewControllerTest extends TagPluginIntegrationTestCase
{
    public function testTagsResourcesViewContainSuccess()
    {
        $user = $this->logInAsUser();
        $expected = ['alpha', '#bravo',];
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        TagFactory::make(['slug' => 'alpha'])->isPersonalFor($resource, $user)->persist();
        TagFactory::make(['slug' => '#bravo'])->isSharedFor($resource)->persist();
        $this->getJson("/resources/{$resource->id}.json?api-version=2&contain[tag]=1");
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
