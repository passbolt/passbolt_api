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
 * @since         5.8.0
 */
namespace Passbolt\Rbacs\Event;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use App\Service\Roles\RolesAddService;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\DateTime;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\TableRegistry;
use Exception;
use InvalidArgumentException;

class CreateRbacsOnRoleCreateListener implements EventListenerInterface
{
    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            RolesAddService::AFTER_ROLE_CREATE_SUCCESS_EVENT_NAME => 'createRbacsOnRoleCreate',
        ];
    }

    /**
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     */
    public function createRbacsOnRoleCreate(EventInterface $event): void
    {
        /** @var \App\Utility\UserAccessControl $uac */
        $uac = $event->getData('uac');
        if (!$uac) {
            throw new InvalidArgumentException('`uac` is missing from event data.');
        }

        /** @var \App\Model\Entity\Role $role */
        $role = $event->getData('role');
        if (!$role) {
            throw new InvalidArgumentException('`role` is missing from event data.');
        }

        $rolesTable = TableRegistry::getTableLocator()->get('Roles');
        $rbacsTable = TableRegistry::getTableLocator()->get('Passbolt/Rbacs.Rbacs');
        $subquery = $rolesTable->find()->select(['id'])->where(['name' => Role::USER]);
        $existingRbacs = $rbacsTable->find()->where(['role_id' => $subquery])->all();

        if ($existingRbacs->isEmpty()) {
            return;
        }

        $entities = [];

        /** @var array<\Passbolt\Rbacs\Model\Entity\Rbac> $existingRbacs */
        foreach ($existingRbacs as $existingRbac) {
            $entities[] = $rbacsTable->newEntity(
                [
                    'role_id' => $role->id,
                    'control_function' => $existingRbac->control_function,
                    'foreign_model' => $existingRbac->foreign_model,
                    'foreign_id' => $existingRbac->foreign_id,
                    'created' => DateTime::now(),
                    'modified' => DateTime::now(),
                    'created_by' => $uac->getId(),
                    'modified_by' => $uac->getId(),
                ],
                ['accessibleFields' => [
                    'role_id' => true,
                    'control_function' => true,
                    'foreign_model' => true,
                    'foreign_id' => true,
                    'created' => true,
                    'modified' => true,
                    'created_by' => true,
                    'modified_by' => true,
                ]]
            );
        }

        try {
            $rbacsTable->saveManyOrFail($entities);
        } catch (PersistenceFailedException $e) {
            $errors = $e->getEntity()->getErrors();

            throw new CustomValidationException(
                __('The rbacs could not be saved.'),
                $errors
            );
        } catch (Exception $e) {
            throw new InternalErrorException(__('Could not save the rbacs, please try again later.'), null, $e);
        }
    }
}
