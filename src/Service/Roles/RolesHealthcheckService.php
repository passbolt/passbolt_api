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
namespace App\Service\Roles;

use App\Model\Entity\Role;
use App\Model\Table\RolesTable;
use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use Cake\ORM\TableRegistry;

class RolesHealthcheckService extends AbstractHealthcheckService
{
    public const CATEGORY = 'data';
    public const NAME = 'Roles';
    public const CHECK_VALIDATES = 'Can validate';

    /**
     * @var \App\Model\Table\RolesTable
     */
    private $table;

    /**
     * Roles Healthcheck constructor.
     *
     * @param \App\Model\Table\RolesTable|null $table secret table
     */
    public function __construct(?RolesTable $table = null)
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->table = $table ?? TableRegistry::getTableLocator()->get('Roles');
        $this->checks[self::CHECK_VALIDATES] = $this->healthcheckFactory(self::CHECK_VALIDATES, true);
    }

    /**
     * @inheritDoc
     */
    public function check(): array
    {
        $records = $this->table->find()->all();
        if (count($records) != 4) {
            $this->checks[self::CHECK_VALIDATES]
                ->addDetail(__('There should be at least 4 roles'), Healthcheck::STATUS_ERROR);
        }
        foreach ($records as $i => $record) {
            $this->canValidate($record);
        }

        return $this->getHealthchecks();
    }

    /**
     * Validates
     *
     * @param \App\Model\Entity\Role $role role
     * @return void
     */
    private function canValidate(Role $role): void
    {
        $copy = $this->table->newEntity($role->toArray());
        $error = $copy->getErrors();

        if (count($error)) {
            $msg = __('Validation failed for role {0}. {1}', $role->id, json_encode($copy->getErrors()));
            $this->checks[self::CHECK_VALIDATES]->fail()->addDetail($msg, Healthcheck::STATUS_ERROR);
        } else {
            $this->checks[self::CHECK_VALIDATES]
                ->addDetail(__('Validation success for role {0}', $role->id), Healthcheck::STATUS_SUCCESS);
        }
    }
}
