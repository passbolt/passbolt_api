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

namespace Passbolt\Metadata\Test\TestCase\Service\Resources;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Resource;
use App\Service\Resources\ResourcesUpdateService;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Metadata\MetadataPlugin;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * Part of the logic of this test is handled in the ResourcesAddControllerTest.
 *
 * @covers \App\Service\Resources\ResourcesAddService
 * @see \App\Controller\Resources\ResourcesAddController
 */
class MetadataResourcesUpdateServiceTest extends TestCase
{
    use FeaturePluginAwareTrait;
    use ResourcesModelTrait;
    use TruncateDirtyTables;
    use UserAccessControlTrait;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private $Resources;

    /**
     * @var \App\Model\Table\SecretsTable
     */
    private $Secrets;

    private ResourcesUpdateService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
        $this->service = new ResourcesUpdateService();
        $this->enableFeaturePlugin(MetadataPlugin::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
    }

    public function testMetadataResourceUpdateServiceSuccess()
    {
        $user = UserFactory::make()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$user])->persist();

        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'user_key',
            MetadataResourceDto::METADATA => $this->getDummyGpgMessage(),
            MetadataResourceDto::METADATA_KEY_ID => UuidFactory::uuid(),
            'resource_type_id' => ResourceTypeFactory::make()->v5Default()->persist()->get('id'),
        ];

        $resource = $this->service->update(
            $this->makeUac($user),
            $resource->get('id'),
            new MetadataResourceDto($payload)
        );

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertSame(1, ResourceFactory::count());
    }

    public function testMetadataResourceUpdateService_Metadata_Key_Is_Personal_On_Shared_Resource()
    {
        /** @var \App\Model\Entity\User[] $users */
        $users = UserFactory::make(2)->persist();
        $resource = ResourceFactory::make()->withPermissionsFor($users)->persist();

        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'user_key',
            MetadataResourceDto::METADATA => $this->getDummyGpgMessage(),
            MetadataResourceDto::METADATA_KEY_ID => UuidFactory::uuid(),
            'resource_type_id' => ResourceTypeFactory::make()->v5Default()->persist()->get('id'),
        ];

        try {
            $this->service->update(
                $this->makeUac($users[0]),
                $resource->get('id'),
                new MetadataResourceDto($payload)
            );
        } catch (ValidationException $exception) {
            $this->assertSame(
                'A resource of type personal cannot be shared with other users or a group.',
                $exception->getErrors()['metadata_key_type']['IsMetadataKeyTypeSharedOnSharedResource']
            );
        }
    }

    public function testMetadataResourceUpdateService_Metadata_Key_Is_Personal_On_Resource_Shared_With_Group()
    {
        $user = UserFactory::make()->persist();
        $group = GroupFactory::make()->withGroupsUsersFor([$user])->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$group])->persist();

        $payload = [
            MetadataResourceDto::METADATA_KEY_TYPE => 'user_key',
            MetadataResourceDto::METADATA => $this->getDummyGpgMessage(),
            MetadataResourceDto::METADATA_KEY_ID => UuidFactory::uuid(),
            'resource_type_id' => ResourceTypeFactory::make()->v5Default()->persist()->get('id'),
        ];

        try {
            $this->service->update(
                $this->makeUac($user),
                $resource->get('id'),
                new MetadataResourceDto($payload)
            );
        } catch (ValidationException $exception) {
            $this->assertSame(
                'A resource of type personal cannot be shared with other users or a group.',
                $exception->getErrors()['metadata_key_type']['IsMetadataKeyTypeSharedOnSharedResource']
            );
        }
    }
}
