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
 * @since         2.12.0
 */
namespace Passbolt\MultiFactorAuthentication\EventListener;

use App\Controller\Component\QueryStringComponent;
use App\Controller\Events\ControllerFindIndexOptionsBeforeMarshal;
use App\Controller\Users\UsersIndexController;
use Cake\Event\EventListenerInterface;
use Passbolt\MultiFactorAuthentication\Model\Query\IsMfaEnabledQueryDecorator;
use Passbolt\MultiFactorAuthentication\Utility\EntityMapper\User\MfaEntityMapper;

class AddIsMfaEnabledColumnToUsersGrid implements EventListenerInterface
{
    /**
     * @param ControllerFindIndexOptionsBeforeMarshal $event Event
     * @return void
     */
    public function __invoke(ControllerFindIndexOptionsBeforeMarshal $event)
    {
        if (!$event->getController() instanceof UsersIndexController) {
            return;
        }

        $options = $event->getOptions();

        $options->allowFilter(IsMfaEnabledQueryDecorator::IS_MFA_ENABLED_FILTER_NAME);
        $options->allowContain(MfaEntityMapper::IS_MFA_ENABLED_PROPERTY);
        $options->addFilterValidator(IsMfaEnabledQueryDecorator::IS_MFA_ENABLED_FILTER_NAME, function ($value) {
            return QueryStringComponent::validateFilterBoolean($value, IsMfaEnabledQueryDecorator::IS_MFA_ENABLED_FILTER_NAME);
        });
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            ControllerFindIndexOptionsBeforeMarshal::EVENT_NAME => $this,
        ];
    }
}
