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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Test\TestCase\Controller\Resources;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class ResourcesUpdateControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->setEmailNotificationsSetting('password.create', true);
        Configure::write('passbolt.v5.enabled', true);
        ResourceTypeFactory::make()->default()->persist();
        RoleFactory::make()->guest()->persist();
    }

    public function testResourcesUpdateController_Metadata_Enabled_Success(): void
    {
        $user = $this->logInAsUser();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();

        $metadataKeyId = UuidFactory::uuid();
        $metadata = 'metadata';
        $metadataKeyType = 'user_key';
        $data = [
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
        ];
        $this->putJson("/resources/{$resource->get('id')}.json", $data);
        $this->assertSuccess();

        // Check the server response.
        $response = $this->_responseJsonBody;

        // Check the resource attributes.
        $this->assertResourceV5Attributes($response);
        $this->assertObjectNotHasAttribute('name', $response);

        $this->assertEquals($data['metadata_key_id'], $response->metadata_key_id);
        $this->assertEquals($data['metadata'], $response->metadata);
        $this->assertEquals($data['metadata_key_type'], $response->metadata_key_type);
        $this->assertEquals($resource->get('created_by'), $response->created_by);
        $this->assertEquals($user->id, $response->modified_by);
    }

    public function testResourcesUpdateController_Metadata_Enabled_Mix_v4_and_v5_Fields_Should_Throw_An_Error(): void
    {
        $user = $this->logInAsUser();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();

        $metadataKeyId = UuidFactory::uuid();
        $metadata = 'metadata';
        $metadataKeyType = 'user_key';
        $data = [
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'name' => '新的專用資源名稱',
        ];
        $this->putJson("/resources/{$resource->get('id')}.json", $data);
        $this->assertBadRequestError('The following fields are not supported in v5: name.');
    }

    public function testResourcesUpdateController_Metadata_Disabled_Mix_v4_and_v5_Fields_Should_Not_Throw_An_Error(): void
    {
        Configure::write('passbolt.v5.enabled', false);
        $user = $this->logInAsUser();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();

        $metadataKeyId = UuidFactory::uuid();
        $metadata = 'metadata';
        $metadataKeyType = 'user_key';
        $data = [
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'name' => '新的專用資源名稱',
        ];
        $this->putJson("/resources/{$resource->get('id')}.json", $data);
        $this->assertSuccess();

        $resource = ResourceFactory::firstOrFail();
        $this->assertNull($resource->metadata_key_id);
        $this->assertObjectNotHasAttribute('metadata', $this->_responseJsonBody);
    }
}
