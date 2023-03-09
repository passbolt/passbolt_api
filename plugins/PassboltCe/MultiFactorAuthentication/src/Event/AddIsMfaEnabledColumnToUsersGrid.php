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
 * @since         2.12.0
 */
namespace Passbolt\MultiFactorAuthentication\Event;

use App\Controller\Component\QueryStringComponent;
use App\Controller\Events\ControllerFindIndexOptionsBeforeMarshal;
use App\Controller\Users\UsersIndexController;
use Cake\Event\EventListenerInterface;
use Passbolt\MultiFactorAuthentication\Service\Query\IsMfaEnabledQueryService;

class AddIsMfaEnabledColumnToUsersGrid implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            ControllerFindIndexOptionsBeforeMarshal::EVENT_NAME => 'addIsMfaEnabledColumnToUsersGrid',
        ];
    }

    /**
     * On User Index Controller, add options.
     * MFA options are visible to admins only
     *
     * @param \App\Controller\Events\ControllerFindIndexOptionsBeforeMarshal $event Before Marschal Event
     * @return void
     */
    public function addIsMfaEnabledColumnToUsersGrid(ControllerFindIndexOptionsBeforeMarshal $event): void
    {
        $controller = $event->getController();
        if (!$controller instanceof UsersIndexController) {
            return;
        }
        if (!$controller->User->getAccessControl()->isAdmin()) {
            return;
        }

        $options = $event->getOptions();

        $options->allowFilter(IsMfaEnabledQueryService::IS_MFA_ENABLED_FILTER_NAME);
        $options->allowContain(IsMfaEnabledQueryService::IS_MFA_ENABLED_PROPERTY);
        $options->addFilterValidator(IsMfaEnabledQueryService::IS_MFA_ENABLED_FILTER_NAME, function ($value) {
            $filterName = IsMfaEnabledQueryService::IS_MFA_ENABLED_FILTER_NAME;

            return QueryStringComponent::validateFilterBoolean($value, $filterName);
        });
    }
}
