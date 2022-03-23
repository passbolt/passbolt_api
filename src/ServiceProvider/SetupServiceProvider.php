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
 * @since         3.5.0
 */

namespace App\ServiceProvider;

use App\Service\Setup\RecoverCompleteService;
use App\Service\Setup\RecoverCompleteServiceInterface;
use App\Service\Setup\RecoverStartService;
use App\Service\Setup\RecoverStartServiceInterface;
use App\Service\Setup\SetupCompleteService;
use App\Service\Setup\SetupCompleteServiceInterface;
use App\Service\Setup\SetupStartService;
use App\Service\Setup\SetupStartServiceInterface;
use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;
use Cake\Http\ServerRequest;

class SetupServiceProvider extends ServiceProvider
{
    protected $provides = [
        RecoverStartServiceInterface::class,
        SetupStartServiceInterface::class,
        SetupCompleteServiceInterface::class,
        RecoverCompleteServiceInterface::class,
    ];

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container->add(RecoverStartServiceInterface::class, RecoverStartService::class)
            ->addArgument(ServerRequest::class);
        $container->add(SetupStartServiceInterface::class, SetupStartService::class)
            ->addArgument(ServerRequest::class);
        $container
            ->add(SetupCompleteServiceInterface::class, SetupCompleteService::class)
            ->addArgument(ServerRequest::class);
        $container
            ->add(RecoverCompleteServiceInterface::class, RecoverCompleteService::class)
            ->addArgument(ServerRequest::class);
    }
}
