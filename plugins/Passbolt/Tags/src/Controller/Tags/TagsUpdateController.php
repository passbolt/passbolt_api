<?php
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
 * @since         2.11.0
 */
namespace Passbolt\Tags\Controller\Tags;

use App\Controller\AppController;
use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;
use Passbolt\Tags\Model\Entity\Tag;

class TagsUpdateController extends AppController
{
    use TagAccessTrait;

    /**
     * Tag update action
     *
     * @param string|null $id Id of the tag to update
     * @return void
     * @throws NotFoundException If the Tag is not found or the user does not have access.
     */
    public function update(string $id = null)
    {
        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The tag id is not valid.'));
        }

        $this->loadModel('Passbolt/Tags.Tags');
        $this->loadModel('Passbolt/Tags.ResourcesTags');

        // Retrieve the tag.
        try {
            $tag = $this->Tags->get($id, [
                'contain' => ['ResourcesTags']
            ]);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The tag does not exist.'));
        }

        if ($tag->get('is_shared')) {
            $updatedTag = $this->_updateSharedTag($tag);
        } else {
            if (!$this->isPersonalTagAccessible($tag)) {
                throw new NotFoundException(__('The tag does not exist.'));
            }

            $updatedTag = $this->_updatePersonalTag($tag);
        }

        $this->success(__('The tag was updated.'), $updatedTag);
    }

    /**
     * Update shared tag
     *
     * @param Tag $tag The tag to update
     * @return Tag the updated tag
     * @throws ForbiddenException If a non admin tries to update a shared tag.
     * @throws CustomValidationException If input validation fails.
     */
    private function _updateSharedTag(Tag $tag)
    {
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('You do not have the permission to update shared tags.'));
        }

        $slug = $this->request->getData('slug');

        if (mb_substr($slug, 0, 1) !== '#') {
            throw new BadRequestException('Shared tags can not be changed into personal tags.');
        }

        $tagExists = $this->Tags->findBySlug($slug)->first();

        if ($tagExists) {
            $this->ResourcesTags->updateAll([
                'tag_id' => $tagExists->id
            ], [
                'tag_id' => $tag->id
            ]);

            $this->Tags->delete($tag);

            return $tagExists;
        }

        $this->Tags->patchEntity(
            $tag,
            [
                'slug' => $slug
            ],
            [
            'accessibleFields' => [
                'slug' => true
                ],
            ]
        );

        if (!empty($tag->getErrors())) {
            throw new CustomValidationException('Could not validate tag data.', $tag->getErrors());
        }

        $this->Tags->save($tag);

        return $tag;
    }

    /**
     * Update personal tag
     *
     * @param Tag $tag The tag to update
     * @return Tag|bool The updated tag
     * @throws BadRequestException If a non admin tries to change a personal tag into a shared tag.
     */
    private function _updatePersonalTag(Tag $tag)
    {
        $slug = $this->request->getData('slug');

        if ($this->User->role() !== Role::ADMIN && mb_substr($slug, 0, 1) === '#') {
            throw new BadRequestException('You do not have the permission to change a personal tag into shared tag.');
        }

        return $this->Tags->getConnection()->transactional(function () use ($tag, $slug) {
            $newTag = $this->Tags->findOrCreateTag($slug, $this->User->getAccessControl());

            // Update all the tag association to the new tag id
            $this->ResourcesTags->updateUserTag(
                $this->User->id(),
                $tag->get('id'),
                $newTag->get('id')
            );

            // Flush all unused tags
            $this->Tags->deleteAllUnusedTags();

            return $newTag;
        });
    }
}
