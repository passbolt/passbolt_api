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
namespace App\Service\Roles;

use App\Model\Entity\Role;
use App\Model\Table\RolesTable;
use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use App\Utility\OpenPGP\OpenPGPBackend;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class RolesHealthcheckService extends AbstractHealthcheckService
{
    const CATEGORY = 'data';
    const NAME = 'Roles';
    const CHECK_VALIDATES = 'Can validate';

    /**
     * @var RolesTable
     */
    private $table;

    /**
     * Roles Healthcheck constructor.
     *
     * @param OpenPGPBackend $gpg gpg backend to use
     * @param RolesTable $table secret table
     */
    public function __construct($gpg = null, $table = null)
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->table = $table ?? TableRegistry::getTableLocator()->get('Roles');
        $this->checks[self::CHECK_VALIDATES] = $this->healthcheckFactory(self::CHECK_VALIDATES, true);
    }

    /**
     * @inheritDoc
     */
    public function check()
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
     * @param Role $role role
     * @return void
     */
    private function canValidate(Role $role)
    {
        $copy = $this->table->newEntity($role->toArray());
        $error = $copy->getErrors();

        if (count($error)) {
            $this->checks[self::CHECK_VALIDATES]->fail()
            ->addDetail(__('Validation failed for role {0}. {1}', $role->id, json_encode($copy->getErrors())), Healthcheck::STATUS_ERROR);
        } else {
            $this->checks[self::CHECK_VALIDATES]
                ->addDetail(__('Validation success for role {0}', $role->id), Healthcheck::STATUS_SUCCESS);
        }
    }
}
