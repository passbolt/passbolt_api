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
use App\Model\Entity\Role;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

class TagsDeleteController extends AppController
{
    use TagAccessTrait;

    /**
     * Tag delete action
     *
     * @param string|null $id Id of the tag to delete
     * @return void
     */
    public function delete(string $id = null)
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
             $this->_deleteSharedTag($tag->get('id'));
        } else {
            if (!$this->isPersonalTagAccessible($tag)) {
                throw new NotFoundException(__('The tag does not exist.'));
            }

             $this->_deletePersonalTag($tag->get('id'));
        }

        $this->success(__('The tag was deleted.'));
    }

    /**
     * Delete shared tag
     *
     * @param string $tagId ID of the tag to delete
     * @return void
     * @throws ForbiddenException If a non admin tries to delete a shared tag.
     */
    private function _deleteSharedTag(string $tagId)
    {
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('You do not have the permission to delete shared tags.'));
        }

        $this->Tags->getConnection()->transactional(function () use ($tagId) {
            // Delete all the association data from ResourceTags
            $this->ResourcesTags->deleteAll([
                'tag_id' => $tagId,
            ]);

            $this->Tags->deleteAll([
                'id' => $tagId
            ]);
        });
    }

    /**
     * Delete personal tag
     *
     * @param string $tagId ID of the tag to delete
     * @return void
     */
    private function _deletePersonalTag(string $tagId)
    {
        $this->Tags->getConnection()->transactional(function () use ($tagId) {
            // Delete all the association data from ResourceTags
            $this->ResourcesTags->deleteAll([
                'tag_id' => $tagId,
                'user_id' => $this->User->id()
            ]);

            // Flush all unused tags
            $this->Tags->deleteAllUnusedTags();
        });
    }
}
