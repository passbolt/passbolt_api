<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Controller\Users;

use App\Controller\AppController;
use App\Error\Exception\ValidationRuleException;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validation;

class UsersDeleteController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->loadModel('Users');
        $this->loadModel('Resources');
        $this->loadModel('Groups');
        $this->loadModel('GroupsUsers');
        $this->loadModel('Permissions');

        return parent::beforeFilter($event);
    }

    /**
     * User delete dry run action
     *
     * @param string $id user uuid
     * @return void
     */
    public function dryrun($id)
    {
        $this->_validateRequestData($id);
        $this->success(__('The user can be deleted.'));
    }

    /**
     * User delete action
     *
     * @param string $id user uuid
     * @return void
     */
    public function delete($id)
    {
        $user = $this->_validateRequestData($id);

        // keep list of group the user was a member of
        // useful to notify the group managers
        $groupIds = $this->GroupsUsers->findGroupsWhereUserNotOnlyMember($id);

        if (!$this->Users->softDelete($user, ['checkRules' => false])) {
            throw new InternalErrorException(__('Could not delete the user, please try again later.'));
        }
        $this->_notifyUsers($user, $groupIds);
        $this->success(__('The user was deleted successfully.'));
    }

    /**
     * Assert request sanity and return the sanitized data
     *
     * @param string $id user uuid
     * @throws ForbiddenException if current user is not an admin
     * @throws BadRequestException if the user uuid id invalid
     * @throws BadRequestException if the user tries to delete themselves
     * @throws NotFoundException if the user does not exist or is already deleted
     * @throws ValidationRuleException if the user is sole manager of a group
     * @throws ValidationRuleException if the user is sole owner of a shared resource
     * @return object $user entity
     */
    protected function _validateRequestData($id)
    {
        // Admin can delete all users
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('You are not authorized to access that location.'));
        }
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The user id must be a valid uuid.'));
        }
        // An admin cannot delete themeselves
        if ($id === $this->User->id()) {
            throw new BadRequestException(__('You are not allowed to delete yourself.'));
        }
        $user = $this->Users->findDelete($id, $this->User->role())->first();
        if (empty($user)) {
            throw new NotFoundException(__('The user does not exist or has been already deleted.'));
        }

        // Check business rules
        // Returning the validation error is not meaningful enough so we may need to
        // build the proper anwer with list of incriminated groups / resources
        if (!$this->Users->checkRules($user, RulesChecker::DELETE)) {
            $errors = $user->getErrors();
            $msg = __('The user cannot be deleted.') . ' ';

            if (isset($errors['id']['soleManagerOfNonEmptyGroup'])) {
                $groupIds = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($id);
                $body = $this->Groups->findAllByIds($groupIds);
                $msg .= $errors['id']['soleManagerOfNonEmptyGroup'];
                throw new ValidationRuleException($msg, $body, $this->Groups);
            }

            if (isset($errors['id']['soleOwnerOfSharedResource'])) {
                $resourceIds = $this->Permissions->findSharedResourcesUserIsSoleOwner($id);
                $body = $this->Resources->findAllByIds($id, $resourceIds);
                $msg .= $errors['id']['soleOwnerOfSharedResource'];
                throw new ValidationRuleException($msg, $body, $this->Resources);
            }

            if (isset($errors['id']['soleManagerOfGroupOwnerOfSharedResource'])) {
                $resourceIds = $this->Permissions->findSharedResourcesSoleGroupManagerIsSoleOwner($id);
                $body = $this->Resources->findAllByIds($id, $resourceIds);
                $msg .= $errors['id']['soleManagerOfGroupOwnerOfSharedResource'];
                throw new ValidationRuleException($msg, $body, $this->Resources);
            }
        }

        return $user;
    }

    /**
     * Send email notification
     *
     * @param User $user entity
     * @param array $groupIds list of Group entity user was member of
     * @return void
     */
    protected function _notifyUsers(User $user, array $groupIds)
    {
        $event = new Event('UsersDeleteController.delete.success', $this, [
            'user' => $user,
            'groupsIds' => $groupIds,
            'deletedBy' => $this->User->id(),
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
