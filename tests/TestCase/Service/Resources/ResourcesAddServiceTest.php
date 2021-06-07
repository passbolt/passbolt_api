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
use App\Service\Resources\ResourcesAddService;
use App\Test\Factory\ResourceTypeFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Part of the logic of this test is handled in the ResourcesAddControllerTest.
 *
 * @covers \App\Service\Resources\ResourcesAddService
 * @see \App\Controller\Resources\ResourcesAddController
 */
class ResourcesAddServiceTest extends TestCase
{
    use ResourcesModelTrait;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private $Resources;

    /**
     * @var \App\Model\Table\SecretsTable
     */
    private $Secrets;

    public function setUp(): void
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
        ResourceTypeFactory::make()->default()->persist();
    }

    public function dataForTestResourceAddSuccess(): array
    {
        return [
            ['chinese' => $this->getDummyResourcesPostData([
                'name' => '新的專用資源名稱',
                'username' => 'username@domain.com',
                'uri' => 'https://www.域.com',
                'description' => '新的資源描述',
            ])],
            ['slavic' => $this->getDummyResourcesPostData([
                'name' => 'Новое имя частного ресурса',
                'username' => 'username@domain.com',
                'uri' => 'https://www.домен.com',
                'description' => 'Новое описание частного ресурса',
            ])],
            ['french' => $this->getDummyResourcesPostData([
                'name' => 'Nouveau nom de resource privée',
                'username' => 'username@domain.com',
                'uri' => 'https://www.mon-domain.com',
                'description' => 'Nouvelle description de resource privée',
            ])],
            ['emoticon' => $this->getDummyResourcesPostData([
                'name' => "\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}",
                'username' => 'username@domain.com',
                'uri' => 'https://www.domain.com',
                'description' => "\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}",
            ])],
        ];
    }

    /**
     * @dataProvider dataForTestResourceAddSuccess
     * @param array $data Data passed
     */
    public function testResourceAddServiceSuccess(array $data)
    {
        $user = UserFactory::make()->user()->persist();
        $service = new ResourcesAddService();

        $resource = $service->add($user->id, $data);

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertInstanceOf(Secret::class, $resource->secrets[0]);

        $this->assertSame(1, $this->Resources->find()->count());
        $this->assertSame(1, $this->Secrets->find()->count());
    }

    public function testResourceAddServiceNoIdUser()
    {
        $this->expectException(ValidationException::class);
        // This UAC does not have an id
        (new ResourcesAddService())->add('', []);
    }

    public function testResourceAddServiceNoUser()
    {
        $user = UserFactory::make(['id' => UuidFactory::uuid()])->user()->getEntity();
        $this->expectException(ValidationException::class);
        // This UAC is not persisted
        (new ResourcesAddService())->add($user->id, []);
    }

    public function testResourceAddServiceInvalidResourceType()
    {
        $data = $this->getDummyResourcesPostData();
        $data['resource_type_id'] = 'invalid';
        $user = UserFactory::make()->user()->persist();
        $service = new ResourcesAddService();

        $this->expectException(ValidationException::class);
        $service->add($user->id, $data);

        $this->assertSame(0, $this->Resources->find()->count());
        $this->assertSame(0, $this->Secrets->find()->count());
    }

    public function testResourceAddServiceTooManySecrets()
    {
        $data = $this->getDummyResourcesPostData(['secrets' => [
            0 => ['data' => $this->getDummyGpgMessage()],
            1 => ['user_id' => UuidFactory::uuid(), 'data' => $this->getDummyGpgMessage()],
        ]]);
        $user = UserFactory::make()->user()->persist();
        $service = new ResourcesAddService();

        $this->expectException(ValidationException::class);
        $service->add($user->id, $data);

        $this->assertSame(0, $this->Resources->find()->count());
        $this->assertSame(0, $this->Secrets->find()->count());
    }

    public function testResourceAddServiceSoftDeletedUser()
    {
        $data = $this->getDummyResourcesPostData();
        $user = UserFactory::make()->user()->patchData(['deleted' => true])->persist();
        $service = new ResourcesAddService();

        $this->expectException(ValidationException::class);
        $resource = $service->add($user->id, $data);

        $this->assertInstanceOf(Resource::class, $resource);
        $this->assertInstanceOf(Secret::class, $resource->secrets[0]);

        $this->assertSame(0, $this->Resources->find()->count());
        $this->assertSame(0, $this->Secrets->find()->count());
    }
}
