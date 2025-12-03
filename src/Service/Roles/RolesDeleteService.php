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
namespace App\Service\Roles;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use App\Model\Table\RolesTable;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\DateTime;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Exception;

class RolesDeleteService
{
    use EventDispatcherTrait;

    private ?RolesTable $Roles;

    public const AFTER_ROLE_DELETE_SUCCESS_EVENT_NAME = 'Role.afterDelete.success';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->Roles = TableRegistry::getTableLocator()->get('Roles');
    }

    /**
     * @param \App\Utility\UserAccessControl $uac UAC object.
     * @param string $roleId Role identifier to update.
     * @return void
     */
    public function delete(UserAccessControl $uac, string $roleId): void
    {
        $uac->assertIsAdmin();

        if (!Validation::uuid($roleId)) {
            throw new BadRequestException(__('The role identifier is not valid.'));
        }

        try {
            $role = $this->Roles->find('notDeleted')->where(['id' => $roleId])->firstOrFail();
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The role does not exist or deleted.'), null, $e);
        }

        $role = $this->softDeleteRole($role, $uac);

        $this->dispatchEvent(self::AFTER_ROLE_DELETE_SUCCESS_EVENT_NAME, [
            'uac' => $uac,
            'role' => $role,
        ]);
    }

    /**
     * @param \App\Model\Entity\Role $role Role entity.
     * @param \App\Utility\UserAccessControl $uac User Access Control.
     * @return \App\Model\Entity\Role
     */
    private function softDeleteRole(Role $role, UserAccessControl $uac): Role
    {
        $data = [
            'deleted' => DateTime::now(),
            'deleted_by' => $uac->getId(),
        ];

        $role = $this->Roles->patchEntity($role, $data, ['accessibleFields' => [
            'deleted' => true,
            'deleted_by' => true,
        ]]);

        try {
            $result = $this->Roles->saveOrFail($role);
        } catch (PersistenceFailedException $e) { // @phpstan-ignore-line
            $errors = $e->getEntity()->getErrors();

            throw new CustomValidationException(
                __('The role could not be deleted.'),
                $errors
            );
        } catch (Exception $e) {
            throw new InternalErrorException(__('Could not delete the role, please try again later.'), null, $e);
        }

        return $result;
    }
}
