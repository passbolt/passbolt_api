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
use App\Service\Resources\ResourcesAddService;
use Cake\Core\Configure;
use Cake\Event\EventInterface;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class ResourcesAddController extends AppController
{
    /**
     * @var \App\Service\Resources\ResourcesAddService
     */
    protected $resourcesAddService;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        /** @phpstan-ignore-next-line */
        $this->Resources = $this->fetchTable('Resources');

        $this->resourcesAddService = new ResourcesAddService();

        $this->createAfterSaveEvent();
    }

    /**
     * Resource Add action
     *
     * @return void
     * @throws \Exception
     * @throws \App\Error\Exception\ValidationException if the resource is not valid.
     * @throws \Cake\Http\Exception\ServiceUnavailableException if parallel requests lead to a table lock albeit multiple attempts.
     */
    public function add()
    {
        $this->assertJson();

        $resource = $this->resourcesAddService->add(
            $this->User->id(),
            $this->getRequest()->getData()
        );

        // Retrieve the saved resource.
        $options = [
            'contain' => [
                'creator' => true, 'favorite' => true, 'modifier' => true,
                'secret' => true, 'permission' => true,
            ],
        ];
        if (Configure::read('passbolt.plugins.tags.enabled')) {
            $options['contain']['tag'] = true;
        }

        $resource = $this->Resources->findView($this->User->id(), $resource->id, $options)->first();

        $this->success(__('The resource has been added successfully.'), $resource);
    }

    /**
     * Create the after save events on the Resources table.
     *
     * @return void
     */
    protected function createAfterSaveEvent(): void
    {
        $this->Resources->getEventManager()->on(
            'Model.afterSave',
            ['priority' => 1],
            function (EventInterface $event, $resource) {
                $this->resourcesAddService->afterSave(
                    $resource,
                    $this->User->getAccessControl(),
                    $this->getRequest()->getData()
                );
            }
        );
    }
}
