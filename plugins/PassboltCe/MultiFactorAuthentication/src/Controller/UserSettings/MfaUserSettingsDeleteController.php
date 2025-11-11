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
namespace Passbolt\MultiFactorAuthentication\Controller\UserSettings;

use App\Model\Table\UsersTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Validation\Validation;
use Passbolt\MultiFactorAuthentication\Controller\MfaController;
use Passbolt\MultiFactorAuthentication\Service\MfaUserSettings\MfaUserSettingsDeleteService;

/**
 * MfaUserSettingsDeleteController Class
 */
class MfaUserSettingsDeleteController extends MfaController
{
    /**
     * @var \App\Model\Table\UsersTable
     */
    protected UsersTable $Users;

    /**
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->Users = $this->fetchTable('Users');
    }

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        $userId = $this->getRequest()->getParam('userId', null);

        if (!$this->isAllowed($userId)) {
            throw new ForbiddenException(__('You are not allowed to access this location.'));
        }

        parent::beforeFilter($event);
    }

    /**
     * @param string|null $userId UUID of the user for which MFA config must be deleted
     * @return void
     */
    public function delete(?string $userId = null)
    {
        if (!Validation::uuid($userId)) {
            throw new BadRequestException(__('The user id is not valid.'));
        }

        try {
            /** @var \App\Model\Entity\User $user */
            $user = $this->Users->findView($userId, $this->User->role())->find('locale')->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new BadRequestException(__('The user id is not valid.'));
        }

        $message = __('No multi-factor authentication settings defined for the user.');
        try {
            $mfaUserSettingsDisableService = new MfaUserSettingsDeleteService();
            if ($mfaUserSettingsDisableService->disableUserSettings($user, $this->User->getAccessControl())) {
                $message = __('The multi-factor authentication settings for the user were deleted.');
            }
        } catch (RecordNotFoundException $exception) {
            // No MFA settings found for user
        }

        $this->success($message);
    }

    /**
     * @param string|null $userId UUID of the user
     * @return bool
     */
    private function isAllowed(?string $userId = null)
    {
        return isset($userId) && ($this->User->isAdmin() || $userId === $this->User->id());
    }
}
