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

namespace Passbolt\Rbacs\Model\Rule;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Table;
use Passbolt\Rbacs\Model\Entity\Rbac;
use Passbolt\Rbacs\Model\Entity\UiAction;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;

class IsControlFunctionAllowedRule
{
    use LocatorAwareTrait;

    private string $errorMessage = '';

    /**
     * @param \Passbolt\Rbacs\Model\Entity\Rbac $rbac The entity to check
     * @param array $options Options passed to the check
     * @return string|bool
     */
    public function __invoke(Rbac $rbac, array $options): bool|string
    {
        if ($rbac->control_function === null || $rbac->foreign_id === null) {
            return false;
        }

        return match ($rbac->get('foreign_model')) {
            Rbac::FOREIGN_MODEL_UI_ACTION => $this->isUiActionValid($rbac),
            Rbac::FOREIGN_MODEL_ACTION => $this->isActionValid($rbac),
            default => true,
        };
    }

    /**
     * @param \Passbolt\Rbacs\Model\Entity\Rbac $rbac rbac to check
     * @return string|bool
     */
    private function isUiActionValid(Rbac $rbac): bool|string
    {
        $uiActionsTable = $this->fetchTable('Passbolt/Rbacs.UiActions');
        $map = UiAction::CONTROL_FUNCTION_MAPPING;
        $this->errorMessage = __('The control function is not allowed for this UI Action.');

        return $this->isControlFunctionAllowed($rbac, $uiActionsTable, $map);
    }

    /**
     * @param \Passbolt\Rbacs\Model\Entity\Rbac $rbac rbac to check
     * @return string|bool
     */
    private function isActionValid(Rbac $rbac): bool|string
    {
        $uiActionsTable = $this->fetchTable('Passbolt/Log.Actions');
        $map = RbacsControlledActionsInsertService::RBACS_CONTROLLED_ACTIONS;
        $this->errorMessage = __('The control function is not allowed for this Action.');

        return $this->isControlFunctionAllowed($rbac, $uiActionsTable, $map);
    }

    /**
     * @param \Passbolt\Rbacs\Model\Entity\Rbac $rbac Rbac to check
     * @param \Cake\ORM\Table $table actions or ui_actions table to check
     * @param array $controlFunctionMap map of the control function allowed for that table
     * @return string|bool
     */
    private function isControlFunctionAllowed(Rbac $rbac, Table $table, array $controlFunctionMap): bool|string
    {
        try {
            /** @var \Passbolt\Rbacs\Model\Entity\UiAction|\Passbolt\Log\Model\Entity\Action $entity */
            $entity = $table
                ->find()
                ->select(['name'])
                ->where(['id' => $rbac->foreign_id])
                ->firstOrFail();
        } catch (RecordNotFoundException) {
            return $this->errorMessage;
        }

        if (!isset($controlFunctionMap[$entity->name])) {
            return $this->errorMessage;
        }

        if (in_array($rbac->control_function, $controlFunctionMap[$entity->name])) {
            return true;
        }

        return $this->errorMessage;
    }
}
