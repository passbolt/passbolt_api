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
 * @since         3.2.0
 */
namespace Passbolt\Ee;

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Passbolt\Ee\Command\SubscriptionCheckCommand;
use Passbolt\Ee\Command\SubscriptionImportCommand;

class Plugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function console($commands): CommandCollection
    {
        // Alias license_check to subscription_check for retro compatibility
        $commands->add('passbolt license_check', SubscriptionCheckCommand::class);
        $commands->add('passbolt subscription_check', SubscriptionCheckCommand::class);
        $commands->add('passbolt subscription_import', SubscriptionImportCommand::class);

        return $commands;
    }
}
