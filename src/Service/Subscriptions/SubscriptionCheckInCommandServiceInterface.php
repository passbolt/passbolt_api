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
 * @since         4.8.0
 */

namespace App\Service\Subscriptions;

use App\Command\PassboltCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

interface SubscriptionCheckInCommandServiceInterface
{
    /**
     * Checks if the passbolt subscription is valid.
     *
     * @param \App\Command\PassboltCommand $command The command requesting the check.
     * @param \Cake\Console\Arguments $args The arguments passed to the $command
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return bool
     */
    public function check(PassboltCommand $command, Arguments $args, ConsoleIo $io): bool;
}
