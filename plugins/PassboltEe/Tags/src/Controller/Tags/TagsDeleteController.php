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
 * @since         2.11.0
 */

namespace Passbolt\Tags\Controller\Tags;

use App\Controller\AppController;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

/**
 * @property \Passbolt\Tags\Model\Table\ResourcesTagsTable $ResourcesTags
 * @property \Passbolt\Tags\Model\Table\TagsTable $Tags
 */
class TagsDeleteController extends AppController
{
    use TagAccessTrait;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        /** @phpstan-ignore-next-line */
        $this->Tags = $this->fetchTable('Passbolt/Tags.Tags');
        /** @phpstan-ignore-next-line */
        $this->ResourcesTags = $this->fetchTable('Passbolt/Tags.ResourcesTags');
    }

    /**
     * Tag delete action
     *
     * @param string|null $id Id of the tag to delete
     * @return void
     */
    public function delete(?string $id = null)
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The tag id is not valid.'));
        }

        try {
            /** @var \Passbolt\Tags\Model\Entity\Tag $tag */
            $tag = $this->Tags->get($id, [
                'contain' => ['ResourcesTags'],
            ]);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The tag does not exist.'));
        }

        if ($tag->get('is_shared')) {
            throw new ForbiddenException(__('You do not have the permission to delete shared tags.'));
        }

        if (!$this->isPersonalTagAccessible($tag)) {
            throw new NotFoundException(__('The tag does not exist.'));
        }

        $this->_deletePersonalTag($tag->get('id'));

        $this->success(__('The tag has been deleted successfully.'));
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
                'user_id' => $this->User->id(),
            ]);

            // Flush all unused tags
            $this->Tags->deleteAllUnusedTags();
        });
    }
}
