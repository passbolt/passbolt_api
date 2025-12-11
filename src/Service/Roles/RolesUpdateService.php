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
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\DateTime;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Exception;

class RolesUpdateService
{
    use EventDispatcherTrait;

    public const AFTER_ROLE_UPDATE_SUCCESS_EVENT_NAME = 'Role.afterUpdate.success';

    private ?RolesTable $Roles;

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
     * @param array $data Data to save.
     * @return \App\Model\Entity\Role
     */
    public function update(UserAccessControl $uac, string $roleId, array $data): Role
    {
        $uac->assertIsAdmin();

        $role = $this->getRole($roleId);
        $oldName = $role->name;
        $role = $this->patchRoleEntity($role, $data, $uac);

        try {
            $result = $this->Roles->saveOrFail($role);
        } catch (PersistenceFailedException $e) { // @phpstan-ignore-line
            $errors = $e->getEntity()->getErrors();

            throw new CustomValidationException(
                __('The role could not be updated.'),
                $errors
            );
        } catch (Exception $e) {
            throw new InternalErrorException(__('Could not update the role, please try again later.'), null, $e);
        }

        $this->dispatchEvent(self::AFTER_ROLE_UPDATE_SUCCESS_EVENT_NAME, [
            'uac' => $uac,
            'role' => $result,
            'oldName' => $oldName,
        ]);

        return $result;
    }

    /**
     * @param string $roleId Role identifier to get.
     * @return \App\Model\Entity\Role
     * @throws \Cake\Http\Exception\NotFoundException If role doesn't exist in the database
     */
    private function getRole(string $roleId): Role
    {
        try {
            $role = $this->Roles->find('notDeleted')->where(['id' => $roleId])->firstOrFail();
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The role does not exist or deleted.'), null, $e);
        }

        return $role;
    }

    /**
     * @param \App\Model\Entity\Role $role Role entity.
     * @param array $data Data to patch.
     * @param \App\Utility\UserAccessControl $uac User Access Control.
     * @return \App\Model\Entity\Role
     */
    private function patchRoleEntity(Role $role, array $data, UserAccessControl $uac): Role
    {
        $name = Hash::get($data, 'name', '');

        $data = [
            'name' => $name,
            'modified_by' => $uac->getId(),
            'modified' => DateTime::now(),
        ];

        return $this->Roles->patchEntity($role, $data, ['accessibleFields' => [
            'name' => true,
            'modified_by' => true,
            'modified' => true,
        ]]);
    }
}
