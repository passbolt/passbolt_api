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
    private $Users;

    private array $usernameDuplicates = [];

    /**
     * Users Healthcheck constructor.
     */
    public function __construct()
    {
        parent::__construct(self::NAME, self::CATEGORY);
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->checks[self::CHECK_VALIDATES] = $this->healthcheckFactory(self::CHECK_VALIDATES, true);
    }

    /**
     * @inheritDoc
     */
    public function check(): array
    {
        $records = $this->Users->find()->all();
        $this->usernameDuplicates = $this->Users->listDuplicateUsernames()->toArray();

        foreach ($records as $record) {
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
        $copy = $this->Users->newEntity($user->toArray(), ['validate' => 'healthcheck',]);

        if (array_key_exists($user->id, $this->usernameDuplicates)) {
            $msg = __('The username {0} is a duplicate.', $this->usernameDuplicates[$user->id]);
            $copy->setError(
                'username',
                ['uniqueUsername' => $msg]
            );
        }

        if (count($copy->getErrors())) {
            $msg = __('Validation failed for user {0}. {1}', $user->id, json_encode($copy->getErrors()));
            $this->checks[self::CHECK_VALIDATES]->fail()
            ->addDetail($msg, Healthcheck::STATUS_ERROR);
        } else {
            $this->checks[self::CHECK_VALIDATES]
                ->addDetail(__('Validation success for user {0}', $user->id), Healthcheck::STATUS_SUCCESS);
        }
    }
}
