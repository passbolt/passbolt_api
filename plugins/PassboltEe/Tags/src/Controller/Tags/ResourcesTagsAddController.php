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
use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Passbolt\Metadata\Utility\MetadataSettingsAwareTrait;
use Passbolt\Tags\Form\MetadataResourcesAddExistingTagForm;
use Passbolt\Tags\Form\MetadataResourcesTagsAddForm;
use Passbolt\Tags\Model\Dto\MetadataTagDto;
use Passbolt\Tags\Service\Metadata\MetadataTagsRenderService;

/**
 * @property \App\Model\Table\ResourcesTable $Resources
 * @property \Passbolt\Tags\Model\Table\TagsTable $Tags
 */
class ResourcesTagsAddController extends AppController
{
    use MetadataSettingsAwareTrait;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Resources = $this->fetchTable('Resources');
        $this->Tags = $this->fetchTable('Passbolt/Tags.Tags');
    }

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
            throw new BadRequestException(__('The resource identifier should be a valid UUID.'));
        }

        $userId = $this->User->id();
        $data = $this->_formatRequestData();
        $data = $this->validateRequestData($data);

        $options = ['contain' => ['all_tags' => 1, 'permission' => 1]];
        /** @var Resource|null $resource */
        $resource = $this->Resources->findView($userId, $resourceId, $options)->first();
        if (is_null($resource)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        $this->patchTagsEntities($resource, $data);
        $saveOptions = ['associated' => ['Tags', 'Tags._joinData']];

        try {
            $this->Resources->saveOrFail($resource, $saveOptions);
        } catch (PersistenceFailedException $e) { // @phpstan-ignore-line
            throw new ValidationException(
                __('Could not save the tags, try again later.'),
                $resource,
                $this->Resources
            );
        } catch (\Exception $e) {
            $msg = __('Could not save the tags, try again later.');
            $msg .= ' ' . $e->getMessage();
            throw new InternalErrorException($msg, null, $e);
        }

        $this->Tags->deleteAllUnusedTags();
        $options = ['contain' => ['tag' => 1, 'permission' => 1]];
        $resource = $this->Resources->findView($userId, $resourceId, $options)->first();

        $tags = $resource->get('tags');
        $tags = (new MetadataTagsRenderService())->renderTags($tags);
        $this->success(__('The operation was successful.'), $tags);
    }

    /**
     * Get and format the request data.
     *
     * @return array
     */
    protected function _formatRequestData()
    {
        $data = $this->getRequest()->getData();

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
     * Validates the request data.
     *
     * @param array $data Data to validate.
     * @return array Valid request data
     * @throws \App\Error\Exception\CustomValidationException If data is invalid.
     */
    private function validateRequestData(array $data): array
    {
        $errors = [];
        foreach ($data as $i => $tag) {
            $errors[$i] = [];

            if (!is_string($tag) && !is_array($tag)) {
                $errors[$i][] = __('The tags data should be a string or an array.');
                continue;
            }

            if (is_array($tag)) {
                $this->assertV5TagCreationEnabled();

                if (array_key_exists('id', $tag)) {
                    $form = new MetadataResourcesAddExistingTagForm($this->User->getAccessControl());
                } else {
                    $form = new MetadataResourcesTagsAddForm();
                }

                if (!$form->validate($tag)) {
                    $errors[$i] = array_merge($errors[$i], $form->getErrors());
                }
            } else {
                $this->assertV4TagCreationEnabled();
            }

            if (empty($errors[$i])) {
                unset($errors[$i]);
            }
        }

        if (!empty($errors)) {
            throw new CustomValidationException(
                __('Could not validate request data.'),
                $errors,
                null,
                400
            );
        }

        return $data;
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

        [$clearTextTags, $encryptedTags] = $this->extractClearTextAndEncryptedTags($data);

        // Do not link tag again if already linked with the resource
        foreach ($resource->get('tags') as $i => $tag) {
            // Do not patch tags owned by other users.
            if ($tag->_joinData->user_id != $userId && !is_null($tag->_joinData->user_id)) {
                continue;
            }
            $tagFoundIndex = array_search($tag->slug, $clearTextTags);
            if ($tagFoundIndex === false) {
                // If the user is not owner of the resource he cannot edit shared tags
                if ($tag->is_shared && !$isOwner) {
                    $msg = __('You do not have the permission to edit shared tags on this resource.');
                    throw new BadRequestException($msg);
                } else {
                    unset($resource['tags'][$i]);
                }
            } else {
                unset($clearTextTags[$tagFoundIndex]);
            }
        }

        // If the user is not owner of the resource he cannot edit shared tags
        if (!$isOwner) {
            if (!empty(preg_grep('/(^#|,#)/', $clearTextTags))) {
                $msg = __('You do not have the permission to edit shared tags on this resource.');
                throw new BadRequestException($msg);
            }
        }

        // The tag the user is adding already exist, associate it to the resource.
        $encryptedTagsIds = Hash::extract($encryptedTags, '{n}.id');
        if ($clearTextTags || $encryptedTagsIds) {
            $existingTags = $this->Tags->findAllBySlugsOrIds($clearTextTags, $encryptedTagsIds)->all()->toArray();
            foreach ($existingTags as $existingTag) {
                $tagDto = MetadataTagDto::fromArray($existingTag->toArray());

                // To prevent duplication, unset from array so it don't get build as a new entity
                if (!$tagDto->isV5()) {
                    unset($clearTextTags[array_search($existingTag->slug, $clearTextTags)]);
                } else {
                    $encryptedTags = Hash::remove($encryptedTags, "{n}[id={$existingTag->id}]");
                }

                if ($tagDto->isPersonal()) {
                    $existingTag->_joinData = $this->Tags->ResourcesTags->newEntity([
                        'user_id' => $userId,
                    ]);
                }

                $tags = $resource->get('tags') ?? [];
                $tags[] = $existingTag;
                $resource->set('tags', $tags);
            }
        }

        // Create the new tags.
        $newTags = array_merge($clearTextTags, $encryptedTags);
        $requestTags = $this->Tags->buildEntitiesOrFail($userId, $newTags);
        $resource->set('tags', array_merge($resource->get('tags'), $requestTags));
        $resource->setDirty('tags');
        $resource->setAccess('tags', true);
    }

    /**
     * Extracts tags data into two separate arrays.
     *
     * @param array $data Data to extract from.
     * @return array
     */
    private function extractClearTextAndEncryptedTags(array $data): array
    {
        $clearTextTags = [];
        $encryptedTags = [];

        foreach ($data as $tag) {
            if (is_array($tag)) {
                $encryptedTags[] = $tag;
            } elseif (is_string($tag)) {
                $clearTextTags[] = $tag;
            } else {
                throw new BadRequestException(__('The tags data should be a string or an array.'));
            }
        }

        return [$clearTextTags, $encryptedTags];
    }
}
