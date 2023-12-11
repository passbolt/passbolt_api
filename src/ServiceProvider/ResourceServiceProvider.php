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
 * @since         4.1.0
 */

namespace App\ServiceProvider;

use App\Service\Resources\PasswordExpiryDefaultValidationService;
use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use App\Service\Resources\ResourcesAddService;
use App\Service\Resources\ResourcesShareService;
use App\Service\Resources\ResourcesUpdateService;
use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;

class ResourceServiceProvider extends ServiceProvider
{
    protected $provides = [
        PasswordExpiryValidationServiceInterface::class,
        ResourcesAddService::class,
        ResourcesUpdateService::class,
        ResourcesShareService::class,
    ];

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        $container
            ->add(ResourcesAddService::class)
            ->addArgument(PasswordExpiryValidationServiceInterface::class);
        $container
            ->add(ResourcesUpdateService::class)
            ->addArgument(PasswordExpiryValidationServiceInterface::class);
        $container
            ->add(PasswordExpiryValidationServiceInterface::class, PasswordExpiryDefaultValidationService::class);
        $container->add(ResourcesShareService::class);
    }
}
