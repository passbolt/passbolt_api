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
namespace App\Service\Permissions;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use App\Utility\OpenPGP\OpenPGPBackend;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class PermissionsHealthcheckService extends AbstractHealthcheckService
{
    const CATEGORY = 'data';
    const NAME = 'Permissions';
    const CHECK_VALIDATES = 'Can validate';

    /**
     * @var PermissionsTable
     */
    private $table;

    /**
     * Permissions Healthcheck constructor.
     *
     * @param OpenPGPBackend $gpg gpg backend to use
     * @param PermissionsTable $table secret table
     */
    public function __construct($gpg = null, $table = null)
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->table = $table ?? TableRegistry::getTableLocator()->get('Permissions');
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
     * @param Permission $permission permission
     * @return void
     */
    private function canValidate(Permission $permission)
    {
        $copy = $this->table->newEntity($permission->toArray());
        $error = $copy->getErrors();

        if (count($error)) {
            $this->checks[self::CHECK_VALIDATES]->fail()
            ->addDetail(__('Validation failed for permission {0}. {1}', $permission->id, json_encode($copy->getErrors())), Healthcheck::STATUS_ERROR);
        } else {
            $this->checks[self::CHECK_VALIDATES]
                ->addDetail(__('Validation success for permission {0}', $permission->id), Healthcheck::STATUS_SUCCESS);
        }
    }
}
