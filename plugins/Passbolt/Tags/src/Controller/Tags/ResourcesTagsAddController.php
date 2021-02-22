<?php
declare(strict_types=1);

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
namespace Passbolt\Tags\Controller\Tags;

use App\Controller\AppController;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

/**
 * @property \App\Model\Table\ResourcesTable $Resources
 * @property \Passbolt\Tags\Model\Table\TagsTable $Tags
 */
class ResourcesTagsAddController extends AppController
{
    /**
     * Add tags for a given resource.
     * Providing an empty list of tags delete all the personal tags
     *
     * @param string $resourceId The identifier of the resource to add a comment to
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Http\Exception\NotFoundException
     * @return void
     */
    public function addPost(string $resourceId)
    {
        if (!Validation::uuid($resourceId)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }

        $this->loadModel('Resources');
        $this->loadModel('Passbolt/Tags.Tags');
        $userId = $this->User->id();
        $data = $this->_formatRequestData();

        $options = ['contain' => ['all_tags' => 1, 'permission' => 1]];
        $resource = $this->Resources->findView($userId, $resourceId, $options)->first();
        if (empty($resource)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        $this->patchTagsEntities($resource, $data);
        $saveOptions = ['associated' => ['Tags', 'Tags._joinData']];
        if ($this->Resources->save($resource, $saveOptions)) {
            $this->Tags->deleteAllUnusedTags();
            $options = ['contain' => ['tag' => 1, 'permission' => 1]];
            $resource = $this->Resources->findView($userId, $resourceId, $options)->first();
            $this->success(__('The operation was successful.'), $resource->tags);
        } else {
            throw new InternalErrorException(__('The tags could not be saved. Try again later.'));
        }
    }

    /**
     * Get and format the request data.
     *
     * @return array
     */
    protected function _formatRequestData()
    {
        $data = $this->request->getData();

        // Data given in V1 format.
        // @deprecated with v2
        if (isset($data['Tags'])) {
            return $data['Tags'];
        }
        if (isset($data['tags'])) {
            return $data['tags'];
        }

        return [];
    }

    /**
     * Patch the resource tags entities of a resource for a given user
     *
     * @param \App\Model\Entity\Resource $resource The resource to patch the tags for
     * @param array $data The list
     * @return void
     */
    private function patchTagsEntities(Resource $resource, array $data): void
    {
        $userId = $this->User->id();
        $isOwner = $resource->permission->type === Permission::OWNER;

        foreach ($resource->tags as $i => $tag) {
            // Do not patch tags owned by other users.
            if ($tag->_joinData->user_id != $userId && !is_null($tag->_joinData->user_id)) {
                continue;
            }
            $tagFoundIndex = array_search($tag->slug, $data);
            if ($tagFoundIndex === false) {
                // If the user is not owner of the resource he cannot edit shared tags
                if ($tag->is_shared && !$isOwner) {
                    $msg = __('You do not have the permission to edit shared tags on this resource.');
                    throw new BadRequestException($msg);
                } else {
                    unset($resource->tags[$i]);
                }
            } else {
                unset($data[$tagFoundIndex]);
            }
        }

        // If the user is not owner of the resource he cannot edit shared tags
        if (!$isOwner) {
            if (!empty(preg_grep('/(^#|,#)/', $data))) {
                $msg = __('You do not have the permission to edit shared tags on this resource.');
                throw new BadRequestException();
            }
        }

        // The tag the user is adding already exist, associate it to the resource.
        if (!empty($data)) {
            $existingTags = $this->Tags->findAllBySlugs($data)->all()->toArray();
            foreach ($existingTags as $existingTag) {
                // @codingStandardsIgnoreStart
                $notShared = @mb_substr($existingTag->slug, 0, 1, 'utf-8') !== '#';
                // @codingStandardsIgnoreEnd
                unset($data[array_search($existingTag->slug, $data)]);
                if ($notShared) {
                    $existingTag->_joinData = new \StdClass();
                    $existingTag->_joinData = $this->Tags->ResourcesTags->newEntity([
                        'user_id' => $userId,
                    ]);
                }
                array_push($resource->tags, $existingTag);
            }
        }

        // Create the new tags.
        $requestTags = $this->Tags->buildEntitiesOrFail($userId, $data);
        $resource->tags = array_merge($resource->tags, $requestTags);
        $resource->setDirty('tags', true);
        $resource->setAccess('tags', true);
    }
}
