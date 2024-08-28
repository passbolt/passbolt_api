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
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

class ResourcesAddControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->setEmailNotificationsSetting('password.create', true);
        Configure::write('passbolt.v5.enabled', true);
        ResourceTypeFactory::make()->default()->persist();
    }

    public function testResourcesAddController_Metadata_Enabled_Success(): void
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);

        $metadataKeyId = UuidFactory::uuid();
        $metadata = 'metadata';
        $metadataKeyType = 'user_key';
        $data = [
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'secrets' => [
                [
                    'data' => $this->getDummyGpgMessage(),
                ],
            ],
        ];

        $this->postJson('/resources.json', $data);
        $this->assertSuccess();

        $resource = ResourceFactory::firstOrFail();
        $this->assertSame($metadataKeyId, $resource->metadata_key_id);
        $this->assertSame($metadata, $resource->metadata);
        $this->assertSame($metadataKeyType, $resource->metadata_key_type);
        $this->assertObjectNotHasAttribute('name', $this->_responseJsonBody);
    }

    public function testResourcesAddController_Metadata_Enabled_Mix_v4_and_v5_Fields_Should_Throw_An_Error(): void
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);

        $metadataKeyId = UuidFactory::uuid();
        $metadata = 'metadata';
        $metadataKeyType = 'user_key';
        $data = $this->getDummyResourcesPostData([
            'metadata_key_id' => $metadataKeyId,
            'metadata' => $metadata,
            'metadata_key_type' => $metadataKeyType,
            'name' => '新的專用資源名稱',
            'username' => 'username@domain.com',
            'uri' => 'https://www.域.com',
            'description' => '新的資源描述',
        ]);

        $this->postJson('/resources.json', $data);
        $this->assertBadRequestError('The following fields are not supported in v5: name, username, uri, description.');
        $this->assertSame(0, ResourceFactory::count());
    }

    public function testResourcesAddController_Metadata_Disabled_Mix_v4_and_v5_Fields_Should_Not_Throw_An_Error(): void
    {
        Configure::write('passbolt.v5.enabled', false);
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);

        $metadataKeyId = UuidFactory::uuid();
        $data = $this->getDummyResourcesPostData([
            'metadata_key_id' => $metadataKeyId,
            'name' => '新的專用資源名稱',
            'username' => 'username@domain.com',
            'uri' => 'https://www.域.com',
            'description' => '新的資源描述',
        ]);

        $this->postJson('/resources.json', $data);
        $this->assertSuccess();

        $resource = ResourceFactory::firstOrFail();
        $this->assertNull($resource->metadata_key_id);
        $this->assertObjectNotHasAttribute('metadata', $this->_responseJsonBody);
    }
}
