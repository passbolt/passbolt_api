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
 * @since         5.16.0
 */
namespace App\ServiceProvider;

use App\Service\RecordsStrategy\DeletedGroupsMaxRecordsStrategy;
use App\Service\RecordsStrategy\DeletedResourcesMaxRecordsStrategy;
use App\Service\RecordsStrategy\DeletedUsersMaxRecordsStrategy;
use App\Service\RecordsStrategy\GroupsAddRecordsStrategyInterface;
use App\Service\RecordsStrategy\GroupsDeleteRecordsStrategyInterface;
use App\Service\RecordsStrategy\NonDeletedGroupsMaxRecordsStrategy;
use App\Service\RecordsStrategy\NonDeletedResourcesMaxRecordsStrategy;
use App\Service\RecordsStrategy\NonDeletedUsersMaxRecordsStrategy;
use App\Service\RecordsStrategy\ResourcesAddRecordsStrategyInterface;
use App\Service\RecordsStrategy\ResourcesDeleteRecordsStrategyInterface;
use App\Service\RecordsStrategy\UsersAddRecordsStrategyInterface;
use App\Service\RecordsStrategy\UsersDeleteRecordsStrategyInterface;
use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;

class RecordsStrategyServiceProvider extends ServiceProvider
{
    public const STRATEGIES = [
        UsersAddRecordsStrategyInterface::class => NonDeletedUsersMaxRecordsStrategy::class,
        UsersDeleteRecordsStrategyInterface::class => DeletedUsersMaxRecordsStrategy::class,
        GroupsAddRecordsStrategyInterface::class => NonDeletedGroupsMaxRecordsStrategy::class,
        GroupsDeleteRecordsStrategyInterface::class => DeletedGroupsMaxRecordsStrategy::class,
        ResourcesAddRecordsStrategyInterface::class => NonDeletedResourcesMaxRecordsStrategy::class,
        ResourcesDeleteRecordsStrategyInterface::class => DeletedResourcesMaxRecordsStrategy::class,
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->provides = array_keys(self::STRATEGIES);
    }

    /**
     * @inheritDoc
     */
    public function services(ContainerInterface $container): void
    {
        foreach (self::STRATEGIES as $strategyInterface => $strategyClass) {
            $container->add($strategyInterface, $strategyClass);
        }
    }
}
