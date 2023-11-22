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
 * @since         4.5.0
 */

namespace App\ServiceProvider;

use App\Command\HealthcheckCommand;
use App\Command\InstallCommand;
use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;
use Cake\Http\Client;

class CommandServiceProvider extends ServiceProvider
{
    protected $provides = [
        HealthcheckCommand::class,
        InstallCommand::class,
    ];

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(HealthcheckCommand::class)->addArgument(Client::class);
        $container->add(InstallCommand::class)->addArgument(Client::class);
    }
}
