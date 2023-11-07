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
 * @since         3.8.0
 */

namespace App\Service\Comments;

use App\Model\Table\CommentsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

class CommentsViewService
{
    use \Cake\ORM\Locator\LocatorAwareTrait;
    use \Cake\Event\EventDispatcherTrait;

    /**
     * @var \App\Model\Table\CommentsTable
     */
    private $Comments;

    /**
     * CommentsAddService constructor.
     */
    public function __construct()
    {
        $this->Comments = TableRegistry::getTableLocator()->get('Comments');
    }

    /**
     * Comments View action
     *
     * @throws \Cake\Http\Exception\BadRequestException if the sanity checks failed
     * @throws \Cake\Http\Exception\NotFoundException if the foreignKey can't be found
     * @param string $userId The currently logged in user's ID
     * @param string $foreignModelName name of the foreign model used for the comment
     * @param string $foreignKey uuid Identifier of the model
     * @param array $options Query options
     * @return \Cake\ORM\Query
     */
    public function view(string $userId, string $foreignModelName, string $foreignKey, array $options = []): Query
    {
        $foreignModelName = ucfirst($foreignModelName);
        // Check model sanity.
        if (!in_array($foreignModelName, CommentsTable::ALLOWED_FOREIGN_MODELS)) {
            throw new BadRequestException('Invalid model name');
        }

        // Check uuid sanity.
        if (!Validation::uuid($foreignKey)) {
            throw new BadRequestException('Invalid id');
        }

        try {
            $comments = $this->Comments->findViewForeignComments(
                $userId,
                $foreignModelName,
                $foreignKey,
                $options
            );
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('Could not find comments for the requested model.'));
        }

        return $comments;
    }
}
