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
use Passbolt\Tags\Test\Lib\TagPluginIntegrationTestCase;

class ResourceUpdateControllerTest extends TagPluginIntegrationTestCase
{
    public $fixtures = [
        'app.Base/users', 'app.Base/profiles', 'app.Base/gpgkeys', 'app.Base/avatars', 'app.Base/roles',
        'app.Base/groups', 'app.Alt0/groups_users',
        'app.Base/resources', 'app.Base/secrets', 'app.Base/favorites', 'app.Alt0/permissions',
        'plugin.passbolt/tags.Base/tags', 'plugin.passbolt/tags.Alt0/resourcesTags',
        'app.Base/email_queue'];

    public function testResourcesUpdateSuccess()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $success = [
            'resource' => [] // Nothing to update for now, we just check the update controller return value.
        ];

        foreach ($success as $case => $data) {
            $this->putJson("/resources/$resourceId.json?api-version=2", $data);
            $this->assertSuccess();

            // Check the server response.
            $resource = $this->_responseJsonBody;

            // Check the resource attributes.
            $this->assertObjectHasAttribute('tags', $resource);
            $this->assertTrue(is_array($resource->tags));
        }
    }
}
