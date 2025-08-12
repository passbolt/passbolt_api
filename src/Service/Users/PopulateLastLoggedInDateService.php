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
 * @since         5.4.0
 */
namespace App\Service\Users;

use App\Model\Entity\Role;
use App\Model\Table\UsersTable;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

/**
 * Service to put logic for V540PopulateLastLoggedInDate migration.
 */
class PopulateLastLoggedInDateService
{
    private UsersTable $Users;

    /**
     * Constructor.
     */
    public function __construct()
    {
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $this->Users = $usersTable;
    }

    /**
     * Fill last logged in value of users table for users who don't have it.
     *
     * @return void
     */
    public function populate(): void
    {
        $users = $this->Users
            ->find('lastLoggedIn')
            ->where([
                'last_logged_in IS' => null,
                // Filter out guests, inactive and deleted users
                'active' => true,
                'deleted' => false,
                'role_id <>' => $this->Users->Roles->getIdByName(Role::GUEST),
            ])
            ->all();

        if ($users->count() === 0) {
            return;
        }

        $users
            ->chunk(100)
            ->each(function ($usersBatch): void {
                $this->updateLastLoggedIn($usersBatch);
            });
    }

    /**
     * @param array<\App\Model\Entity\User> $users Users data to update.
     * @return void
     */
    private function updateLastLoggedIn(array $users): void
    {
        foreach ($users as $user) {
            if (is_null($user->get('action_logs_last_logged_in'))) {
                // do not update if no action logs associated, i.e. when user just joined and didn't logged-in yet.
                continue;
            }

            $result = $this->Users->updateAll(
                ['last_logged_in' => $user->get('action_logs_last_logged_in')],
                ['id' => $user->id]
            );
            if ($result === 0) {
                Log::error(sprintf('Could not update last_logged_in for the user ID: %s', $user->id));
            }
        }
    }
}
