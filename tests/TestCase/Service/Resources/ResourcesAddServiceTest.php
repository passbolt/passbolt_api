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
 * @since         3.3.0
 */

namespace App\Test\TestCase\Service\Resources;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Resource;
use App\Model\Entity\Secret;
use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use App\Service\Resources\ResourcesAddService;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Exception;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\ResourceTypes\Model\Table\ResourceTypesTable;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * Part of the logic of this test is handled in the ResourcesAddControllerTest.
 *
 * @covers \App\Service\Resources\ResourcesAddService
 * @see \App\Controller\Resources\ResourcesAddController
 */
class ResourcesAddServiceTest extends TestCase
{
    use FeaturePluginAwareTrait;
    use ResourcesModelTrait;
    use TruncateDirtyTables;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private $Resources;

    /**
     * @var \App\Model\Table\SecretsTable
     */
    private $Secrets;

    private ResourcesAddService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
        ResourceTypeFactory::make()->default()->persist();
        $this->service = new ResourcesAddService();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->service);
    }

    public static function dataForTestResourceAddSuccess(): array
    {
        return [
            [self::getDummyResourcesPostData([
                'name' => '新的專用資源名稱',
                'username' => 'username@domain.com',
                'uri' => 'https://www.域.com',
                'description' => '新的資源描述',
            ])], //chinese
            [self::getDummyResourcesPostData([
                'name' => 'Новое имя частного ресурса',
                'username' => 'username@domain.com',
                'uri' => 'https://www.домен.com',
                'description' => 'Новое описание частного ресурса',
            ])], //slavic
            [self::getDummyResourcesPostData([
                'name' => 'Nouveau nom de resource privée',
                'username' => 'username@domain.com',
                'uri' => 'https://www.mon-domain.com',
                'description' => 'Nouvelle description de resource privée',
            ])], //french
            [self::getDummyResourcesPostData([
                'name' => "\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}",
                'username' => 'username@domain.com',
                'uri' => 'https://www.domain.com',
                'description' => "\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}",
            ])], //emoticon
        ];
    }

    /**
     * @dataProvider dataForTestResourceAddSuccess
     * @param array $data Data passed
     */
    public function testResourceAddServiceSuccess(array $data)
    {
        $uac = UserFactory::make()->persistedUAC();
        $resource = $this->service->add($uac, new MetadataResourceDto($data));

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertInstanceOf(Secret::class, $resource->secrets[0]);

        $this->assertSame(1, ResourceFactory::count());
        $this->assertSame(1, SecretFactory::count());
        $this->assertSame($data['description'], $resource->get('description'));
    }

    public function testResourceAddService_Error_ResourceTypeDeleted(): void
    {
        $uac = UserFactory::make()->persistedUAC();
        $resourceType = ResourceTypeFactory::make()->standaloneTotp()->deleted()->persist();
        $data = $this->getDummyResourcesPostData(['resource_type_id' => $resourceType->id]);

        try {
            $this->service->add($uac, new MetadataResourceDto($data));
        } catch (Exception $e) {
            $this->assertInstanceOf(ValidationException::class, $e);
            $this->assertArrayHasKey('resource_type_id', $e->getErrors());
            $this->assertArrayHasKey('resource_type_is_not_soft_deleted', $e->getErrors()['resource_type_id']);
        }
    }

    public function testResourceAddServiceNoUser()
    {
        $uac = UserFactory::make()->nonPersistedUAC();
        $this->expectException(ValidationException::class);
        // This UAC is not persisted
        $this->service->add($uac, new MetadataResourceDto());
    }

    public function testResourceAddServiceInvalidResourceType()
    {
        $data = $this->getDummyResourcesPostData();
        $data['resource_type_id'] = 'invalid';
        $uac = UserFactory::make()->persistedUAC();

        $this->expectException(ValidationException::class);
        $this->service->add($uac, new MetadataResourceDto($data));
    }

    public function testResourceAddServiceTooManySecrets()
    {
        $data = $this->getDummyResourcesPostData(['secrets' => [
            0 => ['data' => $this->getDummyGpgMessage()],
            1 => ['user_id' => UuidFactory::uuid(), 'data' => $this->getDummyGpgMessage()],
        ]]);
        $uac = UserFactory::make()->persistedUAC();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Could not validate resource data.');
        $this->service->add($uac, new MetadataResourceDto($data));
    }

    public function testResourceAddService_Secret_Is_A_String()
    {
        $data = $this->getDummyResourcesPostData(['secrets' => $this->getDummyGpgMessage()]);
        $uac = UserFactory::make()->persistedUAC();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Could not validate resource data.');
        $this->service->add($uac, new MetadataResourceDto($data));
    }

    public function testResourceAddServiceSoftDeletedUser()
    {
        $data = $this->getDummyResourcesPostData();
        $uac = UserFactory::make()->user()->deleted()->persistedUAC();

        $this->expectException(ValidationException::class);
        $resource = $this->service->add($uac, new MetadataResourceDto($data));

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertInstanceOf(Secret::class, $resource->secrets[0]);

        $this->assertSame(0, ResourceFactory::count());
        $this->assertSame(0, SecretFactory::count());
    }

    public function testResourceAddService_With_Password_And_Description_Type()
    {
        ResourceTypeFactory::make()->passwordAndDescription()->persist();
        $data = $this->getDummyResourcesPostData();
        $data['description'] = 'Foo description';
        $data['resource_type_id'] = ResourceTypesTable::getPasswordAndDescriptionTypeId();

        $uac = UserFactory::make()->user()->persistedUAC();

        $resource = $this->service->add($uac, new MetadataResourceDto($data));

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertInstanceOf(Secret::class, $resource->secrets[0]);

        $this->assertSame(1, ResourceFactory::count());
        $this->assertSame(1, SecretFactory::count());

        $this->assertSame(ResourceTypesTable::getPasswordAndDescriptionTypeId(), $resource->resource_type_id);
        $this->assertNull($resource->description);
    }

    public function testResourceAddService_With_Expiry_Date_Should_Not_Throw_Bad_Request_Exception_When_Password_Expiry_Plugin_Is_Not_Enabled()
    {
        $uac = UserFactory::make()->persistedUAC();
        $this->expectExceptionMessage('Could not validate resource data.');
        $this->expectException(ValidationException::class);
        $this->service->add($uac, new MetadataResourceDto([PasswordExpiryValidationServiceInterface::PASSWORD_EXPIRED_DATE => 'foo']));
    }
}
