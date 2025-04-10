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

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class ResourcesUpdateControllerTest extends TagPluginIntegrationTestCase
{
    public function testTagsResourcesUpdateSuccess()
    {
        ResourceTypeFactory::make()->passwordString()->persist();
        RoleFactory::make()->guest()->persist();
        $user = $this->logInAsUser();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();

        $success = [
            'resource' => [], // Nothing to update for now, we just check the update controller return value.
        ];

        foreach ($success as $data) {
            $this->putJson("/resources/{$resource->id}.json?api-version=2", $data);
            $this->assertSuccess();

            // Check the server response.
            $resource = $this->_responseJsonBody;

            // Check the resource attributes.
            $this->assertObjectHasAttribute('tags', $resource);
            $this->assertTrue(is_array($resource->tags));
        }
    }
}
