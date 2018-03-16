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
namespace Passbolt\Tags\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;
use Cake\Validation\Validation;

class ResourcesTagsAddController extends AppController
{
    /**
     * Add tags for a given resource.
     * Providing an empty list of tags delete all the personal tags
     *
     * @param string $resourceId The identifier of the resource to add a comment to
     * @throws BadRequestException
     * @throws NotFoundException
     * @return void
     */
    public function addPost($resourceId)
    {
        // check if resourceId is valid
        if (!Validation::uuid($resourceId)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }
        $data = $this->request->getData('Tags', []);

        // check if user has read access to the resource
        // and get all the tags for a given resource
        $this->loadModel('Resources');
        $options = ['contain' => ['tag' => 1, 'permission' => 1]];
        $resource = $this->Resources->findView($this->User->id(), $resourceId, $options)->first();
        if (empty($resource)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        // Make sure all the provided tags are valid
        $this->loadModel('Passbolt/Tags.Tags');
        $requestTags = $this->Tags->buildEntitiesOrFail($data);

        // make diff to get all the tags to create and delete
        $tags = $this->Tags->calculateChanges($resource->tags, $requestTags);
        $success = $this->Tags->saveChanges($tags, $resource, $this->User->id());

        // Build answer
        if ($success) {
            $resource = $this->Resources->findView($this->User->id(), $resourceId, $options)->first();
            $this->success(__('The operation was successful.'), $resource->tags);
        } else {
            throw new InternalErrorException(__('The tags could not be saved. Try again later.'));
        }
    }
}
