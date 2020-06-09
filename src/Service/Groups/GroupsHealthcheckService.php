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
 * @since         2.13.0
 */
namespace App\Service\Groups;

use App\Model\Entity\Group;
use App\Model\Table\GroupsTable;
use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use App\Utility\OpenPGP\OpenPGPBackend;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class GroupsHealthcheckService extends AbstractHealthcheckService
{
    const CATEGORY = 'data';
    const NAME = 'Groups';
    const CHECK_VALIDATES = 'Can validate';

    /**
     * @var GroupsTable
     */
    private $table;

    /**
     * Groups Healthcheck constructor.
     *
     * @param OpenPGPBackend $gpg gpg backend to use
     * @param GroupsTable $table secret table
     */
    public function __construct($gpg = null, $table = null)
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->table = $table ?? TableRegistry::getTableLocator()->get('Groups');
        $this->checks[self::CHECK_VALIDATES] = $this->healthcheckFactory(self::CHECK_VALIDATES, true);
    }

    /**
     * @inheritDoc
     */
    public function check()
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
     * @param Group $group group
     * @return void
     */
    private function canValidate(Group $group)
    {
        $copy = $this->table->newEntity($group->toArray());
        $error = $copy->getErrors();

        if (count($error)) {
            $this->checks[self::CHECK_VALIDATES]->fail()
            ->addDetail(__('Validation failed for group {0}. {1}', $group->id, json_encode($copy->getErrors())), Healthcheck::STATUS_ERROR);
        } else {
            $this->checks[self::CHECK_VALIDATES]
                ->addDetail(__('Validation success for group {0}', $group->id), Healthcheck::STATUS_SUCCESS);
        }
    }
}
