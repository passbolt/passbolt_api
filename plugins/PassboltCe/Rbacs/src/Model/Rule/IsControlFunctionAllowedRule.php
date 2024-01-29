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

use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Rbacs\Model\Entity\Rbac;
use Passbolt\Rbacs\Model\Entity\UiAction;

class IsControlFunctionAllowedRule
{
    use LocatorAwareTrait;

    /**
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options)
    {
        if ($entity->get('control_function') === null || $entity->get('foreign_id') === null) {
            return false;
        }

        // Validate only for UI Action for now
        if ($entity->get('foreign_model') !== Rbac::FOREIGN_MODEL_UI_ACTION) {
            return true;
        }

        $uiActionsTable = $this->fetchTable('Passbolt/Rbacs.UiActions');

        try {
            /** @var \Passbolt\Rbacs\Model\Entity\UiAction $uiAction */
            $uiAction = $uiActionsTable
                ->find()
                ->select(['name'])
                ->where(['id' => $entity->get('foreign_id')])
                ->firstOrFail();
        } catch (RecordNotFoundException $e) {
            return false;
        }

        if (!isset(UiAction::CONTROL_FUNCTION_MAPPING[$uiAction->name])) {
            return false;
        }

        return in_array($entity->get('control_function'), UiAction::CONTROL_FUNCTION_MAPPING[$uiAction->name]);
    }
}
