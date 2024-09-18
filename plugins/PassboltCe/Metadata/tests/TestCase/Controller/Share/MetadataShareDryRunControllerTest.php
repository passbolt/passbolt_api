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

namespace Passbolt\Metadata\Test\TestCase\Controller\Share;

use App\Model\Entity\Permission;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @covers \App\Controller\Share\ShareController
 */
class MetadataShareDryRunControllerTest extends AppIntegrationTestCaseV5
{
    public function testMetadataShareDryRunController_Error_InvalidMetadataKeyType(): void
    {
        $user = UserFactory::make()->user()->persist();
        $user2 = UserFactory::make()->user()->persist();
        $user3 = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->v5Fields()
            ->withCreatorAndPermission($user)
            ->with('ResourceTypes', ResourceTypeFactory::make()->v5Default())
            ->persist();
        PermissionFactory::make()->acoResource($resource)->typeRead()->aroUser($user2)->persist();
        $this->logInAs($user);

        $resourceId = $resource->id;
        $data = [
            'permissions' => [
                ['id' => $resource->permission->id, 'type' => Permission::READ],
                ['aro' => 'User', 'aro_foreign_key' => $user3->get('id'), 'type' => Permission::OWNER],
            ],
        ];
        $this->postJson("/share/simulate/resource/{$resourceId}.json", $data);

        $this->assertBadRequestError('Resource metadata key type is invalid');
    }

    public function testMetadataShareDryRunController_Success(): void
    {
        $user = UserFactory::make()->user()->persist();
        $user2 = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->v5Fields(true)
            ->withCreatorAndPermission($user)
            ->with('ResourceTypes', ResourceTypeFactory::make()->v5Default())
            ->persist();
        $this->logInAs($user);

        $resourceId = $resource->id;
        $data = [
            'permissions' => [
                ['aro' => 'User', 'aro_foreign_key' => $user2->get('id'), 'type' => Permission::OWNER],
            ],
        ];
        $this->postJson("/share/simulate/resource/{$resourceId}.json", $data);

        $this->assertSuccess();
        $result = $this->getResponseBodyAsArray();
        $this->assertNotEmpty($result);
        $this->assertNotEmpty($result['changes']['added']);
    }
}
