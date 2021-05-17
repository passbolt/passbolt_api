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
namespace App\Service\Users;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Utility\Healthchecks\AbstractHealthcheckService;
use App\Utility\Healthchecks\Healthcheck;
use Cake\ORM\TableRegistry;

class UsersHealthcheckService extends AbstractHealthcheckService
{
    public const CATEGORY = 'data';
    public const NAME = 'Users';
    public const CHECK_VALIDATES = 'Can validate';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $table;

    /**
     * Users Healthcheck constructor.
     *
     * @param \App\Model\Table\UsersTable|null $table secret table
     */
    public function __construct(?UsersTable $table = null)
    {
        parent::__construct(self::NAME, self::CATEGORY);
        /** @phpstan-ignore-next-line  */
        $this->table = $table ?? TableRegistry::getTableLocator()->get('Users');
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
     * @param \App\Model\Entity\User $user user
     * @return void
     */
    private function canValidate(User $user): void
    {
        $copy = $this->table->newEntity($user->toArray());
        $error = $copy->getErrors();

        // Ignore profile
        unset($error['profile']);

        if (count($error)) {
            $msg = __('Validation failed for user {0}. {1}', $user->id, json_encode($copy->getErrors()));
            $this->checks[self::CHECK_VALIDATES]->fail()
            ->addDetail($msg, Healthcheck::STATUS_ERROR);
        } else {
            $this->checks[self::CHECK_VALIDATES]
                ->addDetail(__('Validation success for user {0}', $user->id), Healthcheck::STATUS_SUCCESS);
        }
    }
}
