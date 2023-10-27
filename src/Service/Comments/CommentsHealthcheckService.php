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
 * @since         2.13.0
 */
namespace App\Service\Comments;

use App\Model\Entity\Comment;
use App\Model\Table\CommentsTable;
use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use Cake\ORM\TableRegistry;

class CommentsHealthcheckService extends AbstractHealthcheckService
{
    public const CATEGORY = 'data';
    public const NAME = 'Comments';
    public const CHECK_VALIDATES = 'Can validate';

    /**
     * @var \App\Model\Table\CommentsTable
     */
    private $table;

    /**
     * Comments Healthcheck constructor.
     *
     * @param \App\Model\Table\CommentsTable|null $table secret table
     */
    public function __construct(?CommentsTable $table = null)
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->table = $table ?? TableRegistry::getTableLocator()->get('Comments');
        $this->checks[self::CHECK_VALIDATES] = $this->healthcheckFactory(self::CHECK_VALIDATES, true);
    }

    /**
     * @inheritDoc
     */
    public function check(): array
    {
        $records = $this->table->find()->all();

        foreach ($records as $i => $record) {
            $this->canValidate($record);
        }

        return $this->getHealthchecks();
    }

    /**
     * Validates
     *
     * @param \App\Model\Entity\Comment $comment comment
     * @return void
     */
    private function canValidate(Comment $comment): void
    {
        $copy = $this->table->newEntity($comment->toArray());
        $error = $copy->getErrors();

        if (count($error)) {
            $msg = __('Validation failed for comment {0}. {1}', $comment->id, json_encode($copy->getErrors()));
            $this->checks[self::CHECK_VALIDATES]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        } else {
            $msg = __('Validation success for comment {0}', $comment->id);
            $this->checks[self::CHECK_VALIDATES]->addDetail($msg, Healthcheck::STATUS_SUCCESS);
        }
    }
}
