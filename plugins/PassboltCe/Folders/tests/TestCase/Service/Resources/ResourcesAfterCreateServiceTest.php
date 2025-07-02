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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\TestCase\Service\Resources;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UuidFactory;
use Passbolt\Folders\Model\Entity\FoldersRelation;
use Passbolt\Folders\Service\Resources\ResourcesAfterCreateService;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

/**
 * Passbolt\Folders\Service\Folders\ResourcesAfterCreateService Test Case
 *
 * @uses \Passbolt\Folders\Service\Resources\ResourcesAfterCreateService
 */
class ResourcesAfterCreateServiceTest extends FoldersTestCase
{
    use FixtureProviderTrait;
    use FoldersModelTrait;

    /**
     * @var ResourcesAfterCreateService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ResourcesAfterCreateService();
    }

    public function testResourcesAfterCreateServiceSuccess_CreateToRoot()
    {
        $userA = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$userA])->persist();

        $this->service->afterCreate($this->makeUac($userA), $resource);

        $this->assertItemIsInTrees($resource->id, 1);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->get('id'), null);
    }

    public function testResourcesAfterCreateServiceSuccess_CreateIntoFolder()
    {
        $userA = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$userA])->persist();
        $folder = FolderFactory::make()->withPermissionsFor([$userA])->persist();

        $data['folder_parent_id'] = $folder->get('id');
        $this->service->afterCreate($this->makeUac($userA), $resource, $data);

        $this->assertItemIsInTrees($resource->id, 1);
        $this->assertFolderRelation($resource->id, FoldersRelation::FOREIGN_MODEL_RESOURCE, $userA->get('id'), $folder->get('id'));
    }

    public function testResourcesAfterCreateServiceError_FolderParentNotExist()
    {
        $userA = UserFactory::make()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()->withPermissionsFor([$userA])->persist();

        $data['folder_parent_id'] = UuidFactory::uuid();

        $this->service->afterCreate($this->makeUac($userA), $resource, $data);
        $this->assertEntityError($resource, 'folder_parent_id.folder_exists');
    }

    public function testResourcesAfterCreateServiceError_FolderParentNotAllowed()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        /** @var \App\Model\Entity\Resource $resrouce */
        $resource = ResourceFactory::make()->withPermissionsFor([$userA, $userB])->persist();
        $folder = FolderFactory::make()->withPermissionsFor([$userB])->persist();

        $data['folder_parent_id'] = $folder->get('id');

        $this->service->afterCreate($this->makeUac($userA), $resource, $data);
        $this->assertEntityError($resource, 'folder_parent_id.has_folder_access');
    }
}
