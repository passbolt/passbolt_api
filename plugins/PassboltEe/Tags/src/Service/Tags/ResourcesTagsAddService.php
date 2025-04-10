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

namespace Passbolt\Tags\Service\Tags;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Model\Table\ResourcesTable;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use Exception;
use Passbolt\Tags\Model\Dto\MetadataTagDto;
use Passbolt\Tags\Model\Table\TagsTable;

class ResourcesTagsAddService
{
    use LocatorAwareTrait;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private ResourcesTable $Resources;

    /**
     * @var \Passbolt\Tags\Model\Table\TagsTable
     */
    private TagsTable $Tags;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->Resources = $this->fetchTable('Resources');
        $this->Tags = $this->fetchTable('Passbolt/Tags.Tags');
    }

    /**
     * 1. Patch tags entities
     * 2. Saves resource entity along with tags as joinData
     * 3. Delete unused tags
     * 4. Returns update resource's tags (refreshed, due to step #3 cleanup)
     *
     * @param \App\Utility\UserAccessControl $uac User access control object.
     * @param \App\Model\Entity\Resource $resource Resource entity.
     * @param array $data Data to save.
     * @return array
     */
    public function add(UserAccessControl $uac, Resource $resource, array $data): array
    {
        $this->patchTagsEntities($uac, $resource, $data);

        $saveOptions = ['associated' => ['Tags', 'Tags._joinData']];
        try {
            $this->Resources->saveOrFail($resource, $saveOptions);
        } catch (PersistenceFailedException $e) { // @phpstan-ignore-line
            throw new ValidationException(
                __('Could not save the tags, try again later.'),
                $resource,
                $this->Resources
            );
        } catch (Exception $e) {
            $msg = __('Could not save the tags, try again later.');
            $msg .= ' ' . $e->getMessage();
            throw new InternalErrorException($msg, null, $e);
        }

        $this->Tags->deleteAllUnusedTags();

        $options = ['contain' => ['tag' => 1, 'permission' => 1]];
        $resource = $this->Resources->findView($uac->getId(), $resource->id, $options)->first();

        return $resource->get('tags');
    }

    /**
     * Patch the resource tags entities of a resource for a given user.
     *
     * @param \App\Utility\UserAccessControl $uac User access control.
     * @param \App\Model\Entity\Resource $resource The resource to patch the tags for
     * @param array $data The list
     * @return void
     */
    private function patchTagsEntities(UserAccessControl $uac, Resource $resource, array $data): void
    {
        $userId = $uac->getId();
        $isOwner = $resource->permission->type === Permission::OWNER;

        [$clearTextTags, $encryptedTags] = $this->extractClearTextAndEncryptedTags($data);

        // Do not link tag again if already linked with the resource
        foreach ($resource->get('tags') as $i => $tag) {
            // Do not patch tags owned by other users.
            if (!is_null($tag->_joinData->user_id) && $tag->_joinData->user_id != $userId) {
                continue;
            }

            if (!is_null($tag->slug)) {
                // V4
                $tagFoundIndex = array_search($tag->slug, $clearTextTags);
                if ($tagFoundIndex === false) {
                    // If the user is not owner of the resource they cannot unlink shared tags
                    if ($tag->is_shared && !$isOwner) {
                        $msg = __('You do not have the permission to edit shared tags on this resource.');
                        throw new BadRequestException($msg);
                    }
                    unset($resource['tags'][$i]);
                } else {
                    unset($clearTextTags[$tagFoundIndex]);
                }
            } else {
                // For V5 unlink anyway
                unset($resource['tags'][$i]);
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
            $existingTags = $this->Tags->findAllBySlugsOrIds($uac, $clearTextTags, $encryptedTagsIds)->all()->toArray();
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
