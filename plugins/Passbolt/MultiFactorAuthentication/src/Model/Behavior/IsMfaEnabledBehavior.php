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
 * @since         2.0.0
 */

namespace Passbolt\MultiFactorAuthentication\Model\Behavior;

use App\Model\Event\TableFindIndexBefore;
use Cake\ORM\Behavior;
use Passbolt\MultiFactorAuthentication\Model\EntityMapper\User\MfaEntityMapper;
use Passbolt\MultiFactorAuthentication\Model\Query\IsMfaEnabledQueryDecorator;
use Passbolt\MultiFactorAuthentication\Service\IsMfaEnabledService;

/**
 * @method \App\Model\Table\UsersTable table()
 */
class IsMfaEnabledBehavior extends Behavior
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Model\Query\IsMfaEnabledQueryDecorator
     */
    private $isMfaEnabledQueryDecorator;

    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config): void
    {
        $isMfaEnabledService = new IsMfaEnabledService();
        $this->isMfaEnabledQueryDecorator = new IsMfaEnabledQueryDecorator(
            $this->table(),
            new MfaEntityMapper($isMfaEnabledService)
        );
        $this->addIsMfaEnabledBehaviorOnTableFindIndexBeforeEvent();

        parent::initialize($config);
    }

    /**
     * Listens to the TableFindIndexBefore::EVENT_NAME, and apply the
     * Mfa decoration if triggered.
     *
     * @return void
     */
    public function addIsMfaEnabledBehaviorOnTableFindIndexBeforeEvent(): void
    {
        $this->table()->getEventManager()->on(
            TableFindIndexBefore::EVENT_NAME,
            function (TableFindIndexBefore $event) {
                $this->isMfaEnabledQueryDecorator->apply($event->getQuery(), $event->getOptions());
            }
        );
    }
}
