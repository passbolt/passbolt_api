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
 * @since         2.0.0
 */

namespace App\Controller\Resources;

use App\Controller\AppController;
use App\Service\Resources\ResourcesUpdateService;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\Folders\Model\Behavior\FolderizableBehavior;
use Passbolt\Metadata\Controller\Attribute\MetadataRequestToDto;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\Metadata\Service\MetadataResourcesRenderService;

/**
 * ResourcesUpdateController Class
 */
class ResourcesUpdateController extends AppController
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return parent::implementedEvents() + ['Controller.startup' => 'startup'];
    }

    /**
     * Asserts the request before it is mapped to the DTO.
     *
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException if request is not JSON
     * @throws \Cake\Http\Exception\BadRequestException If the resource id is not a valid uuid.
     */
    public function startup(): void
    {
        $this->assertJson();

        if (!Validation::uuid($this->getRequest()->getParam('id'))) {
            throw new BadRequestException(__('The resource identifier should be a valid UUID.'));
        }
    }

    /**
     * Resource Update action
     *
     * @param string $id The identifier of the resource to update.
     * @param \Passbolt\Metadata\Model\Dto\MetadataResourceDto $resourceDto The resource data to update.
     * @param \App\Service\Resources\ResourcesUpdateService $resourcesUpdateService The service updating the resource.
     * @throws \Cake\Http\Exception\NotFoundException If the resource is soft deleted.
     * @throws \Cake\Http\Exception\NotFoundException If the user does not have access to the resource.
     * @throws \Cake\Http\Exception\BadRequestException If the resource id is not a valid uuid.
     * @throws \Exception If an unexpected error occurred
     * @throws \Cake\Http\Exception\NotFoundException If the resource does not exist.
     * @return void
     */
    public function update(
        string $id,
        #[MetadataRequestToDto]
        MetadataResourceDto $resourceDto,
        ResourcesUpdateService $resourcesUpdateService,
    ): void {
        $uac = $this->User->getAccessControl();
        $resource = $resourcesUpdateService->update($uac, $id, $resourceDto);

        // Retrieve the updated resource.
        $options = [
            'contain' => [
                'creator' => true, 'favorite' => true, 'modifier' => true, 'secret' => true, 'permission' => true,
            ],
        ];
        if (Configure::read('passbolt.plugins.tags.enabled')) {
            $options['contain']['tag'] = true;
        }
        /** @var \App\Model\Table\ResourcesTable $resourcesTable */
        $resourcesTable = $this->fetchTable('Resources');
        $resource = $resourcesTable->findView($this->User->id(), $resource->id, $options)->firstOrFail();
        $resource = FolderizableBehavior::unsetPersonalPropertyIfNull($resource->toArray());
        $resource = (new MetadataResourcesRenderService())->renderResource($resource, $resourceDto->isV5());

        $this->success(__('The resource has been updated successfully.'), $resource);
    }
}
