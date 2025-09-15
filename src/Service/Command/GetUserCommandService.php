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
 * @since         5.5.0
 */

namespace App\Service\Command;

use App\Model\Entity\User;
use Cake\Console\Arguments;
use Cake\Console\Exception\StopException;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * GetUserCommandService class
 */
class GetUserCommandService
{
    use LocatorAwareTrait;

    /**
     * Get user from given username argument option.
     *
     * @throws \Cake\Console\Exception\StopException If user doesn't exist.
     */
    public function getUser(Arguments $args): User
    {
        $username = $args->getOption('username');
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');

        /** @var \App\Model\Entity\User|null $user */
        $user = $usersTable
            ->findByUsername($username)
            ->find('activeNotDeleted')
            ->find('notDisabled')
            ->first();
        if ($user === null) {
            throw new StopException(
                sprintf('The user with username `%s`does not exist, is not active or is disabled.', $username)
            );
        }

        return $user;
    }
}
