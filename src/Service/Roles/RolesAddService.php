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
use App\Utility\UserAccessControl;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\Exception\PersistenceFailedException;
use Cake\ORM\TableRegistry;
use Exception;

class RolesAddService
{
    use EventDispatcherTrait;

    public const AFTER_ROLE_CREATE_SUCCESS_EVENT_NAME = 'Role.afterCreate.success';

    /**
     * @param \App\Utility\UserAccessControl $uac UAC object.
     * @param array $data Data to save.
     * @return \App\Model\Entity\Role
     */
    public function add(UserAccessControl $uac, array $data): Role
    {
        $uac->assertIsAdmin();

        /** @var \App\Model\Table\RolesTable $rolesTable */
        $rolesTable = TableRegistry::getTableLocator()->get('Roles');

        $data = array_merge($data, [
            'description' => null,
            'created_by' => $uac->getId(),
            'modified_by' => $uac->getId(),
        ]);

        $role = $rolesTable->newEntity($data, ['accessibleFields' => [
            'name' => true,
            'description' => true,
            'created_by' => true,
            'modified_by' => true,
        ]]);

        try {
            $result = $rolesTable->saveOrFail($role);
        } catch (PersistenceFailedException $e) { // @phpstan-ignore-line
            $errors = $e->getEntity()->getErrors();

            throw new CustomValidationException(
                __('The role could not be saved.'),
                $errors
            );
        } catch (Exception $e) {
            throw new InternalErrorException(__('Could not save the role, please try again later.'), null, $e);
        }

        $this->dispatchEvent(self::AFTER_ROLE_CREATE_SUCCESS_EVENT_NAME, [
            'uac' => $uac,
            'role' => $result,
        ]);

        return $result;
    }
}
