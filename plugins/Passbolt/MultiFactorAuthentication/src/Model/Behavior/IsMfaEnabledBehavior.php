<?php
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
use App\Model\Table\UsersTable;
use Cake\ORM\Behavior;
use Passbolt\MultiFactorAuthentication\Model\Query\IsMfaEnabledQueryDecorator;
use Passbolt\MultiFactorAuthentication\Service\GetMfaAccountSettingsService;
use Passbolt\MultiFactorAuthentication\Service\GetMfaOrgSettingsService;
use Passbolt\MultiFactorAuthentication\Service\IsMfaEnabledService;
use Passbolt\MultiFactorAuthentication\Utility\EntityMapper\User\MfaEntityMapper;

/**
 * @method UsersTable getTable()
 */
class IsMfaEnabledBehavior extends Behavior
{
    /**
     * @var IsMfaEnabledQueryDecorator
     */
    private $isMfaEnabledQueryDecorator;

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            TableFindIndexBefore::EVENT_NAME => 'addIsMfaEnabledBehavior'
        ];
    }

    /**
     * @param array $config Config
     * @return void
     */
    public function initialize(array $config)
    {
        $isMfaEnabledService = new IsMfaEnabledService(new GetMfaOrgSettingsService(), new GetMfaAccountSettingsService());

        $this->isMfaEnabledQueryDecorator = new IsMfaEnabledQueryDecorator(
            $this->getTable(),
            new MfaEntityMapper($isMfaEnabledService)
        );

        parent::initialize($config);
    }

    /**
     * @param TableFindIndexBefore $event Event
     * @return void
     */
    public function addIsMfaEnabledBehavior(TableFindIndexBefore $event)
    {
        $this->isMfaEnabledQueryDecorator->apply($event->getQuery(), $event->getOptions());
    }
}
